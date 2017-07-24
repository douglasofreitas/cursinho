<?php
/**
 * Classe correspondente ao Módulo Candidatos
 */
class CandidatosController extends AppController {
	//var $scaffold;
	var $layout = 'tricolor_layout';
	var $pageTitle = 'candidatos';
	var $paginate;
	var $components = array('Excel', 'Fpdf');
	var $helpers = array('Chart', 'questionario');
	var $uses = array('Candidato', 'QuestaoQuestionario');
	
	function index()
	{
        if($this->Session->read('Auth.User.group_id') == 3){
            $this->set('content_title', 'Processo seletivo');

            $aviso_email = false;

            $candidatos = $this->Candidato->getByCPF($this->Session->read('Auth.User.username'));

            //gerar lista de códigos das faturas
            $this->loadModel('Fatura');
            $faturas = array();

            foreach($candidatos as $c){
                $faturas_candidato = $this->Fatura->getByCadidatoId($c['Candidato']['candidato_id']);
                $faturas[$c['Candidato']['candidato_id']] = array();
                foreach($faturas_candidato as $item){
                    $faturas[$c['Candidato']['candidato_id']][] = $item;
                }

                if(empty($c['Candidato']['email'])){
                    $aviso_email = true;
                }
            }

            //obter dados do processo seletivo
            $this->loadModel('ProcessoSeletivo');
            //$processo = $this->ProcessoSeletivo->getProcessoAtual();
            $processo = $this->ProcessoSeletivo->getProcessoAtivo();

			//busca por mensalidades se for estudante
			$candidatos_id = array();
			foreach($candidatos as $c){
				$candidatos_id[$c['Candidato']['candidato_id']] = $c['Candidato']['candidato_id'];
			}
			$estudantes = $this->Candidato->Estudante->getEstudanteIds($candidatos_id);
			$estudantes_id = array();
            if($estudantes)
                foreach($estudantes as $e){
                    $estudantes_id[$e['Estudante']['estudante_id']] = $e['Estudante']['estudante_id'];
                }
            if($estudantes_id)
			    $mensalidades = $this->Candidato->Estudante->Fatura->getMensalidadesEstudantes($estudantes_id);
			else
                $mensalidades = null;
            $this->set('candidatos', $candidatos);
            $this->set('faturas', $faturas);

            $this->set('processo', $processo);
            $this->set('exibe_conteudo', true);
            $this->set('aviso_email', $aviso_email);
			$this->set('mensalidades', $mensalidades);
        }else{
            $this->set('content_title', 'Módulo Candidato');
            $this->set('exibe_conteudo', false);
        }
	}
	/* Esta função é responsável por inserir um novo candidato no banco de dados. */
	/* Ela verifica se já existe um candidato com o número de inscrição e/ou ano informados pelo usuário,
	 * e impede que a inserção seja feita. Também é verificado se já existe um processo seletivo para o ano informado. */
	function inserir()
	{
		// Define o título do conteúdo da página (da barra laranja)
		$this->set('content_title', 'Cadastrar Novo Candidato');
        App::import('Model', 'ProcessoSeletivo');
		$_SESSION['valida_cpf'] = true;
		// Verifica se algum dado foi enviado do formulário
		if (!empty($this->data))
		{
                        //verificar se foi marcadp a opção de gerar o número de inscrição automaticamente.
                        if(!empty($this->data['Candidato']['gerador_numero_inscricao'])){
                            $this->data['Candidato']['numero_inscricao'] = $this->Candidato->geraNumeroInscricao($this->data['Candidato']['ano']);
                        }
			$this->Candidato->create();
			// Verifica se não existe um candidato com o número de inscrição e ano informados
			if ($this->Candidato->naoExiste($this->data['Candidato']['numero_inscricao'], $this->data['Candidato']['ano']))
			{
				//verifica se ja existe o processo seletivo para este candidato
				$this->ProcessoSeletivo = new ProcessoSeletivo();
				if($this->data['Candidato']['ano'] == '' || $this->ProcessoSeletivo->existe($this->data['Candidato']['ano']))
				{
					$this->data['Candidato']['processo_seletivo_id'] = $this->ProcessoSeletivo->obterId($this->data['Candidato']['ano']);
					//setar para maiuscula os textos do formulário
					$this->data['Candidato']['nome'] = strtoupper($this->data['Candidato']['nome']);
					$this->data['Candidato']['nome_mae'] = strtoupper($this->data['Candidato']['nome_mae']);
					$this->data['Candidato']['nome_pai'] = strtoupper($this->data['Candidato']['nome_pai']);
					$this->data['Candidato']['orgao_emissor_rg'] = strtoupper($this->data['Candidato']['orgao_emissor_rg']);
					$this->data['Candidato']['endereco'] = strtoupper($this->data['Candidato']['endereco']);
					$this->data['Candidato']['numero'] = strtoupper($this->data['Candidato']['numero']);
					$this->data['Candidato']['complemento'] = strtoupper($this->data['Candidato']['complemento']);
					$this->data['Candidato']['bairro'] = strtoupper($this->data['Candidato']['bairro']);
					$this->Candidato->set($this->data);	
					//salvando o candidato
					if ($this->Candidato->save())
					{   
                                            $candidato_id = $this->Candidato->getInsertId();
                                            $this->Session->setFlash('Candidato cadastrado com sucesso');
                                            $cand = $this->Candidato->getCandidatoById($candidato_id);
                                            $this->redirect('/candidatos/visualizar/'.$cand['Candidato']['numero_inscricao'].'/'.$cand['Candidato']['ano']);
					}
					else
					{
                                            $this->Session->setFlash('Candidato não pode ser inserido. Tente novamente ou notifique um técnico');
					}
				}
				else
				{
					//caso não tenha o processo seletivo, deve informar ao usuário para iniciar o processo.
					$this->Session->setFlash('Candidato não pode ser inserido. Favor iniciar o Processo Seletivo pela coordenação!');
					$this->redirect('/candidatos/index');
				}
			}
			else
			{
				$this->Session->setFlash('ATENÇÃO! Já existe um candidato com o número de inscrição e o ano fornecidos.
					Por favor informe outro número de inscrição e/ou ano.');
			}
		}
		// Pega a lista de estados do banco de dados para colocar um combobox no formulário
		$estados = $this->Candidato->Cidade->Estado->find('list', array('recursive' => '0'));
		$this->set('estados', $estados);
		$this->set('estado_selecionado', 'SP');
		// Pega a lista de todas as cidades do estado SP para colocar um combobox no formulário
		$cidades = $this->Candidato->Cidade->find('list', array('conditions' => array('Cidade.estado_id' => 'SP'),
                    'fields' => array('nome')));
                // Unidades
		$array_unidade = $this->Candidato->Unidade->getSelectForm();
		// Seleciona a cidade de São Carlos como padrão
		$cidade_selecionada = $this->Candidato->Cidade->obtemId('SAO CARLOS', 'SP');
		$this->set('cidades', $cidades);
                $this->set('unidades', $array_unidade);
		$this->set('cidade_selecionada', $cidade_selecionada);

        $this->ProcessoSeletivo = new ProcessoSeletivo();
        $this->set('processos_seletivos', $this->ProcessoSeletivo->find('all', array('conditions' => array('ConfiguracaoProcessoSeletivo.ativo' => 1), 'recursive' => 2 )));
	}
	/* Esta função é responsável por visualizar os dadosde um candidato específico.
	 * Ela recebe como parâmetros o número de inscrição e o ano do candidato. */
	function visualizar($numero_inscricao = null, $ano = null)
	{
		// Define o título do conteúdo da página (da barra laranja)
		$this->set('content_title', 'Ficha de inscrição do candidato');
		$this->Candidato->recursive = '1';
		// Obtém o id (que é a chave primário real) do candidato
		$this->Candidato->id = $this->Candidato->obterId($numero_inscricao, $ano);		
		$candidato = $this->Candidato->read();
        $this->loadModel('Fatura');
        $faturas = $this->Fatura->getByCadidatoId($candidato['Candidato']['candidato_id']);

		$this->set('candidato', $candidato);
        $this->set('faturas', $faturas);
	}
	/* Esta função é responsável por visualizar as respostas da prova de um candidato específico.
	 * Ela recebe como parâmetros o número de inscrição e o ano do candidato. */
	function visualizar_respostas_questao_prova($numero_inscricao, $ano)
	{
		// Se o candidato informado existe, redireciona para o controller resposta_questao_provas chamando a função visualizar
		if ($this->Candidato->existe($numero_inscricao, $ano))
		{
			$this->redirect('/resposta_questao_provas/visualizar/'.$numero_inscricao.'/'.$ano);
		}
		else
		{
			$this->Session->setFlash('Candidato não encontrado!');
			$this->redirect('/candidatos/index/');
		}
	}
	function gerar_pdf($numero_inscricao, $ano)
	{
		//obtendo os dados do candidato
		$candidato = $this->Candidato->getCandidato($numero_inscricao, $ano);
		// variaveis
        $font = 'arial';
        $startX = 10;
        $startY = 10;
        $width = 297;
        $height = 210;
        $margin = 20;     // margem contando ambos os lados
        $main_width = 220;
        $main_height = 150;
        $retorno_width = 57;
        $retorno_height = 190;
        $comprovante_width = 150;
        $comprovante_height = 40;
        $comprovanteobs_width = 70;
        $this->Fpdf->FpdfComponent("L", "mm", "A4");
		$pdf = $this->Fpdf; 
		//cabeçalho
		$pdf->header_tipo('ficha_inscricao');
        $pdf->SetAutoPageBreak(false);
        $pdf->AddPage();
        $pdf->SetFont($font, '', 10);
        $pdf->SetTitle("Ficha de inscrição");
        $pdf->SetSubject("Ficha de inscrição do aluno X");
        // faz caixa de fora
        $pdf->Rect($startX, $startY, $width - $margin, $height - $margin, 'D');
        // faz caixa da lateral direita
        $pdf->Rect($startX + $main_width, $startY, $retorno_width, $retorno_height, 'D');
        $pdf->Rect($startX + $main_width, $startY, $retorno_width, 40, 'D');
        $pdf->Rect($startX + $main_width + 22, $startY + 40 + 4, 30, 30, 'D');
        // faz caixa inferior
        $pdf->Rect($startX, $startY + $main_height, $comprovante_width, $comprovante_height, 'D');
        // faz caixa inferior-centro
        $pdf->Rect($startX + $comprovante_width, $startY + $main_height, $comprovanteobs_width, $comprovante_height, 'D');
        $pdf->SetFont($font, '', 12);
        $pdf->SetXY($startX + $comprovante_width + 1, $startY + $main_height + 1);
        $texto = "Este comprovante é a única garantia que você tem de efetivação de sua inscrição. Confira se seu nome e número de inscrição estão corretos. Guarde este comrpovante e apresente-o para receber sua carteirinha";
        $pdf->MultiCell($comprovanteobs_width - 2, 4.9, $texto, 0, 'J', 0);
        // parte principal
        $pdf->SetXY($startX, $startY);
        $pdf->SetFont($font, 'B', 14);
        $pdf->Cell($main_width, 12, 'Processo Seletivo - Curso Pré-vestibular da UFSCar', 0, 0, 'C', false);
        $pdf->SetFont($font, 'B', 12);
        $pdf->Text($startX + 2, $startY + 18, 'Número de inscrição:   ' . $candidato['Candidato']['numero_inscricao']);
        $pdf->Text($startX + 90, $startY + 18, 'Ano:   ' . $candidato['Candidato']['ano']);
        $pdf->SetFont($font, '', 12);
        $pdf->Text($startX + 2, $startY + 28.2, 'Nome:   ' . $candidato['Candidato']['nome']);
        $pdf->Text($startX + 2, $startY + 38.4, 'RG:   ' . $candidato['Candidato']['rg']);
        $pdf->Text($startX + 70, $startY + 38.4, 'Órgão emissor:   ' . $candidato['Candidato']['orgao_emissor_rg']);
        $pdf->Text($startX + 140, $startY + 38.4, 'CPF:   ' . $candidato['Candidato']['cpf']);
        $pdf->Text($startX + 2, $startY + 48.6, 'Nome da mãe:   ' . $candidato['Candidato']['nome']); //questionario //ERRO
        $pdf->Text($startX + 2, $startY + 58.8, 'Nome do pai:   ' . $candidato['Candidato']['nome']); //questionario //ERRO
        $pdf->Text($startX + 2, $startY + 68.8, 'Endereço:   ' . $candidato['Candidato']['endereco']);
        $pdf->Text($startX + 120, $startY + 68.8, 'Nº:   ' );
        $pdf->Text($startX + 140, $startY + 68.8, 'Complemento:   ' );
        $pdf->Text($startX + 2, $startY + 78, 'Bairro:   ' . $candidato['Candidato']['bairro']);
        $pdf->Text($startX + 120, $startY + 78, 'Cidade:   ' . $candidato['Cidade']['nome']);  //cidade
        $pdf->Text($startX + 170, $startY + 78, 'CEP:   ' . $candidato['Candidato']['cep']);
        $pdf->Text($startX + 2, $startY + 89.4, 'Telefone residencial:   ' . $candidato['Candidato']['telefone_residencial']);
        $pdf->Text($startX + 100, $startY + 89.4, 'Outro telefone:   ' . $candidato['Candidato']['telefone_outro']);
        if ($candidato['Candidato']['ano_conclusao_ensino_medio'] == 0)
          $candidato['Candidato']['ano_conclusao_ensino_medio'] = '';
        $pdf->Text($startX + 2, $startY + 99.6, 'Ano de conclusão do ensino médio:   ' . $candidato['Candidato']['ano_conclusao_ensino_medio']);
        if ($candidato['Candidato']['taxa_inscricao'] == 1) {
          $str = '[ X ] Paga    [  ] Não paga';
        }
        else if ($candidato['Candidato']['taxa_inscricao'] == 0) {
          $str = '[  ] Paga    [ X ] Não paga';
        }
        $pdf->Text($startX + 2, $startY + 109.8, 'Taxa de inscrição:   ' . $str);
        $pdf->Text($startX + 94, $startY + 109.8, 'Assinatura do candidato: _______________________________');
        $pdf->SetFont($font, 'B', 12);
        $pdf->Text($startX + 2, $startY + 121, 'Matrícula - Ano letivo:');
        $pdf->SetFont($font, '', 12);
        if ($candidato['Candidato']['turma'] == '1') { //questionario
          $str = '[ X ] 1 ano    [  ] 2 anos';
        }
        else if ($candidato['Candidato']['turma'] == '2') {  //questionario
          $str = '[  ] 1 ano    [ X ] 2 anos';
        }
        $str2 = '';
        if (!empty($candidato['Candidato']['unidade_id'])) {  
          $str2 = '[ X ] '.$candidato['Unidade']['nome'];
        }else{
          $str2 = '[ ] Sem unidade';
        }
        $pdf->Text($startX + 2, $startY + 131.2, 'Opção de curso:   '.$str.'      Unidade:   '.$str2);
        if ($candidato['Candidato']['taxa_inscricao'] == 1) {
          $str = '[ X ] Paga    [  ] Não paga';
        }
        else if ($candidato['Candidato']['taxa_inscricao'] == 0) {
          $str = '[  ] Paga    [ X ] Não paga';
        }
        $pdf->Text($startX + 2, $startY + 141.4, 'Taxa de matrícula:   '.$str.'              Assinatura do aluno: _______________________________');
        // comprovante de matrícula
        $pdf->SetXY($startX, $startY + $main_height + 5);
        $pdf->Cell($comprovante_width, 0, 'Comprovante de matrícula - CPV - UFSCar', 0, 0, 'C', false);
        $pdf->SetFont($font, '', 12);
        $pdf->SetXY($startX + 1, $startY + $main_height + 15);
        $pdf->Cell($comprovante_width, 0, 'Nº de inscrição:   ' . $candidato['Candidato']['numero_inscricao'], 0, 0, 'L', false);
        $pdf->SetXY($startX + 60, $startY + $main_height + 15);
        $pdf->Cell($comprovante_width, 0, 'Ano:   ' . $candidato['Candidato']['ano'], 0, 0, 'L', false);
        $pdf->SetXY($startX + 1, $startY + $main_height + 23);
        $pdf->Cell($comprovante_width, 0, 'Nome:   ' . $candidato['Candidato']['nome'], 0, 0, 'L', false);
        $pdf->SetXY($startX + 1, $startY + $main_height + 36);
        if ($candidato['Candidato']['taxa_inscricao'] == 1) {
          $str = '[ X ] Paga    [  ] Não paga';
        }
        else if ($candidato['Candidato']['taxa_inscricao'] == 0) {
          $str = '[  ] Paga    [ X ] Não paga';
        }
        $pdf->Cell($comprovante_width, 0, 'Taxa de matrícula:   '.$str, 0, 0, 'L', false);
        // lembrete de retorno
        $pdf->SetFont($font, '', 10);
        $pdf->WriteROtie($startX + $main_width + 14, $startY + 38, "Para todas as etapas", 90, 0);
        $pdf->WriteRotie($startX + $main_width + 19, $startY + 38, "do processo seletivo é", 90, 0);
        $pdf->WriteRotie($startX + $main_width + 24, $startY + 38, "necessário a", 90, 0);
        $pdf->WriteRotie($startX + $main_width + 29, $startY + 38, "apresentação desse", 90, 0);
        $pdf->WriteRotie($startX + $main_width + 34, $startY + 38, "comprovante e do RG", 90, 0);
        $pdf->WriteRotie($startX + $main_width + 39, $startY + 38, "ou outro documento", 90, 0);
        $pdf->WriteRotie($startX + $main_width + 44, $startY + 38, "com foto. ", 90, 0);
        $pdf->SetFont($font, '', 13);
        $pdf->WriteRotie($startX + $main_width + 36, $startY + 70, "Local para", 90, 0);
        $pdf->WriteRotie($startX + $main_width + 42, $startY + 67, "carimbo", 90, 0);
        $pdf->WriteROtie($startX + $main_width + 8, $startY + $height - $margin - 2, 'Nº de inscrição:   ' . $candidato['Candidato']['numero_inscricao'], 90, 0);
        $pdf->WriteROtie($startX + $main_width + 8, $startY + $height - $margin - 50, 'Ano:   ' . $candidato['Candidato']['ano'], 90, 0);
        $pdf->WriteROtie($startX + $main_width + 16, $startY + $height - $margin - 2, 'Nome:   ' . $candidato['Candidato']['nome'], 90, 0);
        $pdf->SetFont($font, '', 14);
        $pdf->WriteROtie($startX + $main_width + 28, $startY + $height - $margin - 2, 'Data de retorno: ___/___/______  ', 90, 0);
        $pdf->SetFont($font, '', 10);
        $pdf->WriteROtie($startX + $main_width + 32, $startY + $height - $margin - 2, '(preenchimento do questionário socioeconômico)', 90, 0);
        if ($candidato['Candidato']['taxa_inscricao'] == 1) {
          $str = '[ X ] Paga    [  ] Não paga';
        }
        else if ($candidato['Candidato']['taxa_inscricao'] == 0) {
          $str = '[  ] Paga    [ X ] Não paga';
        }
        $pdf->WriteROtie($startX + $main_width + 42, $startY + $height - $margin - 2, 'Taxa de inscrição:   '.$str, 90, 0);
        $pdf->WriteROtie($startX + $main_width + 52, $startY + $height - $margin - 2, 'Este recibo somente é válido se devidamente carimbado', 90, 0);
        //imprime a saida do arquivo..
        $pdf->Output($candidato['Candidato']['numero_inscricao'].'_'.$candidato['Candidato']['ano'].'.pdf','D');
	}
	/* Esta função é responsável por alterar os dados de um candidato específico 
	 * Ela recebe como parâmetros o número de inscrição e o ano do candidato */
	function alterar($numero_inscricao = null, $ano = null)
	{
		// Define o título do conteúdo da página (da barra laranja)
		$this->set('content_title', 'Editar a ficha de inscrição do candidato');
		// Verifica se algum dado foi enviado do formulário
		if (!empty($this->data))
		{
			$this->Candidato->set($this->data);
			// Verifica se o número de inscrição ou o ano são diferentes do anterior
			if (($this->Session->check('Candidato.numero_inscricao.beforeAlter') && 
				 $this->Session->read('Candidato.numero_inscricao.beforeAlter') != $this->data['Candidato']['numero_inscricao']) 
				 ||		 
				($this->Session->check('Candidato.ano.beforeAlter') &&
				 $this->Session->read('Candidato.ano.beforeAlter') != $this->data['Candidato']['ano']))
				 {
                                        // Verifica se já existe um candidato com o novo número de inscrição e ano informados
                                        if ($this->Candidato->naoExiste($this->data['Candidato']['numero_inscricao'], $this->data['Candidato']['ano']))
                                        {
                                                $salvar = true;
                                        }
                                        else
                                        {
                                                $salvar = false;
                                                $this->Session->setFlash('ATENÇÃO! Já existe um candidato com o número de inscrição e o ano fornecidos.
                                                        Por favor informe outro número de inscrição e/ou ano.');
                                        }
                                }
			else
			{
				$salvar = true;
			}
			if ($salvar)
			{
				if ($this->Candidato->save($this->data))
				{
					$_SESSION['valida_cpf'] = true;
					if ($this->data['Candidato']['matriculado'] == 1)
					{
						if ($this->Candidato->Estudante->find('count', array('conditions' => array('Estudante.candidato_id' => $this->data['Candidato']['candidato_id']),
														  	  'recursive' => '0')) == 0)
						{
							$data['Estudante']['candidato_id'] = $this->data['Candidato']['candidato_id'];
                                                        $data['Estudante']['ano_letivo'] = $this->data['Candidato']['ano'];
							$this->Candidato->Estudante = new $this->Candidato->Estudante();
							$this->Candidato->Estudante->save($data);
						}
					}
					$this->Session->setFlash('A ficha do candidato foi alterada com sucesso');
					$this->redirect('/candidatos/visualizar/' . $this->data['Candidato']['numero_inscricao']
					. '/' . $this->data['Candidato']['ano']);
					$this->Session->delete('Candidato.numero_inscricao.beforeAlter');
					$this->Session->delete('Candidato.ano.beforeAlter');
				}
			}
		}
                $this->Candidato->recursive = '0';
                $this->Candidato->id = $this->Candidato->obterId($numero_inscricao, $ano);
                // Colocamos os dados do candidato informado na variável $data
                $this->data = $this->Candidato->read();

		//verifica se o CPF é menor que 11 caracteres para não validar
		if(strlen($this->data['Candidato']['cpf']) < 11){
			$_SESSION['valida_cpf'] = false;
		}else{
			$_SESSION['valida_cpf'] = true;
		}

                // Pega a lista de estados para colocar em um combobox no formulário
                $estados = $this->Candidato->Cidade->Estado->find('list', array('recursive' => '0'));
                $this->set('estados', $estados);
                $this->set('estado_selecionado', $this->data['Cidade']['estado_id']);
                // Pega a lista de cidades para colocar em um combobox no formulário
                $cidades = $this->Candidato->Cidade->find('list', array('conditions' => array('Cidade.estado_id' => $this->data['Cidade']['estado_id']),
                                                                                                                  'fields' => array('nome')));
                $cidade_selecionada = $this->data['Cidade']['cidade_id'];
                $unidade_selecionada = $this->data['Candidato']['unidade_id'];
                // Unidades
                $array_unidade = $this->Candidato->Unidade->getSelectForm();
                $this->set('cidades', $cidades);
                $this->set('unidades', $array_unidade);
                $this->set('cidade_selecionada', $cidade_selecionada);
                $this->set('unidade_selecionada', $unidade_selecionada);
                $this->set('turma', $this->data['Candidato']['turma']);
                // Salva o número de inscrição e o ano do candidato em uma variável de sessão
                //  para que possamos fazer uma comparação com os novos valores informados no formulário
                $this->Session->write('Candidato.numero_inscricao.beforeAlter', $this->data['Candidato']['numero_inscricao']);
                $this->Session->write('Candidato.ano.beforeAlter', $this->data['Candidato']['ano']);
	}
	/* Esta função é responsável por pegar o número de inscrição e o ano de um candidato informador
	 * pelo usuário, e redirecionar para a página de alteração de dados do candidato */
	function editar()
	{
		// Define o título do conteúdo da página (da barra laranja)
		$this->set('content_title', 'Editar a ficha de inscrição de um candidato');
		$this->Candidato->recursive = '0';
		// Verifica se algum dado foi enviado do formulário
		if (!empty($this->data))
		{
			$condicao = array('numero_inscricao' => $this->data['Candidato']['numero_inscricao'],
							  'ano' => $this->data['Candidato']['ano']);
			if ($this->Candidato->find('first', array('conditions' => $condicao)))
			{
				// Se o candidato informado existe, redireciona para a página de alteração de dados
				$this->redirect('/candidatos/alterar/' . $this->data['Candidato']['numero_inscricao']
				. '/' . $this->data['Candidato']['ano']);
			}
			else
			{
				$this->Session->setFlash('Nenhum candidato com o número de inscrição e ano informado foi encontrado');
			}
		}
	}

	/* Esta função é responsável por fazer a listagem de todos os candidatos */
	function listar_todos()
	{
		// Define o título do conteúdo da página (da barra laranja)
		$this->set('content_title', 'Lista de Candidatos');
		$this->Candidato->recursive = '0';
		// Define as opções que serão utilizadas para fazer a paginação dos resultados
		$this->paginate = array('limit' => 100, 'order' => array('Candidato.processo_seletivo_id' => 'desc','Candidato.ano' => 'desc',
																'Candidato.numero_inscricao' => 'desc'));
		// Salva em uma variável de sessão a condição utilizada para fazer a listagem e um nome de arquivo.
		// Eles serão utilizados para exportar os resultados da listagem
		$this->Session->write('Candidatos.relatorio.condicao', array('1' => '1'));
		$this->Session->write('Candidatos.relatorio.nomeArquivo', 'listagem_candidatos.xls');
		$candidatos = $this->paginate('Candidato');
		$this->set('candidatos', $candidatos);
	}
	/* Esta função é responsável por listar que passaram na fase classificatória
	 * Ela recebe como parâmetro o ano ao qual a fase pertence */
	function listar_aprovados($ano_fase)
	{
		// Define o título do conteúdo da página (da barra laranja)
		$this->set('content_title', 'Listagem dos candidatos aprovados');
		$this->Candidato->recursive = '0';
		// Define as opções que serão utilizadas para fazer a paginação dos resultados
		$this->paginate = array('limit' => 100, 'order' => array('Candidato.numero_inscricao' => 'asc',
																'Candidato.ano' => 'asc'));
		$condicao = array('Candidato.fase_classificatoria_status' => 1, 'Candidato.ano' => $ano_fase);
		// Salva em uma variável de sessão a condição utilizada para fazer a listagem e um nome de arquivo.
		// Eles serão utilizados para exportar os resultados da listagem
		$this->Session->write('Candidatos.relatorio.condicao', $condicao);
		$this->Session->write('Candidatos.relatorio.nomeArquivo', 'listagem_candidatos_aprovados.xls');
                // Guarda na sessão a variável Candidatos.filtro com valores dos campos do formulário
                $this->Session->write('Candidatos.filtro', $condicao);
                // Redireciona para a função que cuida de exibir os resultados
                $this->redirect('/candidatos/listar_filtro');
	}
	function listar_primeira_chamada($ano_fase)
	{
		// Define o título do conteúdo da página (da barra laranja)
		$this->set('content_title', 'Candidatos da primeira chamada');
		$this->Candidato->recursive = '0';
		// Define as opções que serão utilizadas para fazer a paginação dos resultados
		$this->paginate = array('limit' => 100, 'order' => array('Candidato.numero_inscricao' => 'asc',
																'Candidato.ano' => 'asc'));
		$condicao = array('Candidato.primeira_chamada' => 1, 'Candidato.ano' => $ano_fase);
		// Salva em uma variável de sessão a condição utilizada para fazer a listagem e um nome de arquivo.
		// Eles serão utilizados para exportar os resultados da listagem
		$this->Session->write('Candidatos.relatorio.condicao', $condicao);
		$this->Session->write('Candidatos.relatorio.nomeArquivo', 'listagem_candidatos_aprovados.xls');
		// Guarda na sessão a variável Candidatos.filtro com valores dos campos do formulário
                $this->Session->write('Candidatos.filtro', $condicao);
                // Redireciona para a função que cuida de exibir os resultados
                $this->redirect('/candidatos/listar_filtro');
	}
	function listar_ultimos_aprovados($ano_fase)
	{
		$this->set('content_title', 'Listagem dos últimos candidatos aprovados');
		$this->Candidato->recursive = '0';
		$this->paginate = array('limit' => 100, 'order' => array('Candidato.numero_inscricao' => 'asc',
																'Candidato.ano' => 'asc'));
		$condicao = array('Candidato.fase_classificatoria_status' => 1, 'Candidato.ultima_chamada' => '1', 'Candidato.ano' => $ano_fase);
		$this->Session->write('Candidatos.relatorio.condicao', $condicao);
		$this->Session->write('Candidatos.relatorio.nomeArquivo', 'listagem_candidatos_aprovados.xls');
		// Guarda na sessão a variável Candidatos.filtro com valores dos campos do formulário
                $this->Session->write('Candidatos.filtro', $condicao);
                // Redireciona para a função que cuida de exibir os resultados
                $this->redirect('/candidatos/listar_filtro');
	}
	function listar_filtro()
	{
		// Define o título do conteúdo da página (da barra laranja)
		$this->set('content_title', 'Lista de Candidatos: filtro');
		$this->Candidato->recursive = '0';
		// Verifica se está salvo em uma variável de sessão um array que contém os campos que serão utilizados
		//   na condição de busca
		if ($this->Session->check('Candidatos.filtro'))
		{
			$filtro = $this->Session->read('Candidatos.filtro');
			$condicao = array();
			// Coloca no array $condicao apenas os campos que não estão vazios
			foreach ($filtro as $field => $value)
			{
				if ($value != '' && $value != '%%')
				{
					$condicao[$field] = $value;
				}
			}
			// Salva em uma variável de sessão a condição utilizada para fazer a listagem e um nome de arquivo.
			// Eles serão utilizados para exportar os resultados da listagem
			$this->Session->write('Candidatos.relatorio.condicao', $condicao);
			$this->Session->write('Candidatos.relatorio.nomeArquivo', 'resultado_filtro.xls');
			// Verifica se o array $condicao não está vazio
			if (!empty($condicao))
			{
				// Se o número de tuplas encontradas for maior que zero, exibe os resultados
				if ($this->Candidato->find('count', array('conditions' => $condicao)) > 0)
				{
					$this->paginate = array('limit' => 100, 'order' => array('Candidato.ano' => 'desc',
																			'Candidato.numero_inscricao' => 'desc'));
					$candidatos = $this->paginate('Candidato', $condicao);

                    //verificar se preencheu a ultima página
                    $questionario_pendente = array();
                    $this->loadModel('RespostaQuestaoQuestionario');
                    if($candidatos){
                        foreach($candidatos as $cand){
                            if( $this->RespostaQuestaoQuestionario->find('count', array('conditions' => array('RespostaQuestaoQuestionario.candidato_id' => $cand['Candidato']['candidato_id'], 'RespostaQuestaoQuestionario.questao_questionario_id' => 27 ), 'recursive'=>-1) ) == 0 ){
                                $questionario_pendente[$cand['Candidato']['candidato_id']] = 1;
                            }
                        }
                    }

					$this->set('candidatos', $candidatos);
                    $this->set('questionario_pendente', $questionario_pendente);
				}
				// Senão, mostra uma mensagem e exibe novamente o formulário de filtro
				else
				{
					$this->Session->setFlash('Nenhum candidato foi encontrado');
					$this->redirect('/candidatos/filtrar/formulario');
				}
			}
			// Se todos os campos do formulário de filtro estão vazios, então redireciona para
			//   a listagem de todos os candidatos
			else
			{
				$this->redirect('/candidatos/listar_todos');
			}
		}
		else
		{
			$this->redirect('/candidatos/listar_todos');
		}
	}
	/* Esta função é responsável por pegar os dados fornecidos no formulário de filtro e gerar um array
	 *   com os campos que serão utilizados.
	 *  
	 * Uma coisa importante que esta função faz também é  */
	function filtrar($acao)
	{
		// Aqui é definido 13 grupos de questões, onde cada grupo possui algumas determinadas questões
		// Esses grupos são os que aparecem dentro da opção 'Campos do questionário' na página de filtro
		$grupoQuestaoQuestionario = array('0' => array('1', '2'),
										  '1' => array('4', '5', '6'),
										  '2' => array('9', '11', '12', '13', '14', '15', '16', '17',
													   '18', '19', '20', '21', '22', '23'),
										  '3' => array('24', '25', '26'),
										  '4' => array('27', '28', '29', '30', '31', '32', '33', '34',
													   '35'),
										  '5' => array('36', '37', '38', '39', '40'),
										  '6' => array('41', '42', '43', '44'),
										  '7' => array('45', '46'),
										  '8' => array('47', '48', '49', '50', '51', '52', '53', '54',
													   '55'),
										  '9' => array('56', '57', '58'),
										  '10' => array('59', '60', '61', '62', '63', '64'),
										  '11' => array('65', '66'),
										  '12' => array('67', '68', '69'),
										  '13' => array('70', '71', '72', '73', '74', '75', '76', '77',
														'78'));
		// Há duas ações possíveis para esta função:
		//  'resultados' : indica que deve-se gerar a condição de busca com os campos fornecidos no formulário
		//      e redirecionar para a função que exibe os resultados
		//  'default' : indica que deve-se redirecionar para a página que contém o formulário do filtro
		switch ($acao)
		{
			case 'resultados' :
				// Verifica se algum dado foi enviado do formulário
				if (!empty($this->data))
				{					
					$contador = 1;
					$candidatos = '';
					// O trecho de código dentro deste foreach é um pouco complicado. A idéia principal aqui é a seguinte:
					// Como os dados das respostas do questionário estão salvos em formato XML, não é possível fazermos uma
					//   busca utilizando puramente XML de uma forma simples (pelo menos não usando MySql). Então, primeiramente,
					//   pegamos os dados dos campos referentes às questões do questionário e geramos uma string com formato XML.
					// Com essa string, fazemos uma busca na tabela de respostas das questões do questionário (que salva a resposta
					//   com uma string XML) utilizando um LIKE com a string gerada, e obtemos o id dos candidatos que satisfazem a essa condição.
					// Esse processo é feito para cada questão do questionário que foi preenchida no formulário e, a cada interação, o espaço
					//   de busca é restringido. Por exemplo, quando obtemos os ids dos candidatos que satisfazem à condição de uma questão,
					//   eles são colocados em um array. Na próxima interação, o espaço de busca será restringido para apenas os candidatos que
					//   estão nesse array.	
					foreach ($grupoQuestaoQuestionario as $grupo => $questoes)
					{						
						foreach ($questoes as $questao_id)
						{
							$resposta = '';
							foreach ($this->params['form'] as $campo => $valor)
							{
								// Procura pelo campo cujo id é igual ao id da questão atual, e guarda o seu valor
								if (preg_match("/^qid" . $questao_id . "_.*/", $campo, $matches))
									if ($valor != '')
										$resposta[$campo] = $valor;
							}
							// A função obterCandidatoPelaResposta recebe como parâmetros o id da questão, a resposta obtida do formulário
							//   e um conjunto de ids de candidatos que restringe o espaço de busca
							// O resultado da chamada dessa função é guardado na variável $temp
							$temp = $this->QuestaoQuestionario->RespostaQuestaoQuestionario->obterCandidatoPelaResposta($questao_id, $resposta, $candidatos);
							// Se há algo na variável $temp, significa que foram encontrados candidatos com as restrições fornecidas
							if ($temp)
							{
								$candidatos = $temp;
							}
						}
					}
					// Guarda o conjunto de ids dos candidatos na variável data
					$this->data['Candidato']['candidato_id'] = $candidatos;
					// Retira a definição do campo estado do array data, pois ele não será utilizado
					unset($this->data['Candidato']['estado']);
					$postConditions = array('nome' => 'LIKE',
                                                'endereco' => 'LIKE',
                                                'bairro' => 'LIKE');
					$condicao = $this->postConditions($this->data, $postConditions);
					if ($this->data['Candidato']['pontuacao_social_minima'] != '')
						$condicao['Candidato.pontuacao_social >='] = $this->data['Candidato']['pontuacao_social_minima'];
					if ($this->data['Candidato']['pontuacao_social_maxima'] != '')
						$condicao['Candidato.pontuacao_social <='] = $this->data['Candidato']['pontuacao_social_maxima'];
					if ($this->data['Candidato']['pontuacao_economica_minima'] != '')
						$condicao['Candidato.pontuacao_economica >='] = $this->data['Candidato']['pontuacao_economica_minima'];
					if ($this->data['Candidato']['pontuacao_economica_maxima'] != '')
						$condicao['Candidato.pontuacao_economica <='] = $this->data['Candidato']['pontuacao_economica_maxima'];
					// Retirar a definição de alguns campos do array $condicao que não serão utilizados
					unset($condicao['Candidato.pontuacao_social_minima']);
					unset($condicao['Candidato.pontuacao_social_maxima']);
					unset($condicao['Candidato.pontuacao_economica_minima']);
					unset($condicao['Candidato.pontuacao_economica_maxima']);
					if ($this->data['Candidato']['nota_prova_minima'] != '')
						$condicao['Candidato.nota_prova >='] = $this->data['Candidato']['nota_prova_minima'];
					if ($this->data['Candidato']['nota_prova_maxima'] != '')
						$condicao['Candidato.nota_prova <='] = $this->data['Candidato']['nota_prova_maxima'];
					// Retirar a definição de alguns campos do array $condicao que não serão utilizados
					unset($condicao['Candidato.nota_prova_minima']);
					unset($condicao['Candidato.nota_prova_maxima']);


                    //verificar se preencheu questionário ou prova
                    if ($this->data['Candidato']['questionario_vazio_flag'] != '')
                        $condicao['Candidato.questionario_vazio'] = $this->data['Candidato']['questionario_vazio_flag'];

                    if($this->data['Candidato']['fez_nota_prova'] == 1){
                        $condicao['Candidato.nota_prova > '] = '0';
                    }elseif($this->data['Candidato']['fez_nota_prova'] === '0'){
                        $condicao['OR'] = array(array('Candidato.nota_prova ' => 0), array('Candidato.nota_prova ' => null));
                    }
                    unset($condicao['Candidato.fez_nota_prova']);
                    unset($condicao['Candidato.questionario_vazio_flag']);


					// Guarda na sessão a variável Candidatos.filtro com valores dos campos do formulário
					$this->Session->write('Candidatos.filtro', $condicao);
					// Redireciona para a função que cuida de exibir os resultados
					$this->redirect('/candidatos/listar_filtro');
				}
				break;
			default :
				// Define o título do conteúdo da página (da barra laranja)
				$this->set('content_title', 'Filtrar');
				// Apaga da sessão a variável Candidatos.filtro, limpando os valores dos campos
				//    do formulário utilizados no filtro anterior
				$this->Session->delete('Candidatos.filtro');
				foreach ($grupoQuestaoQuestionario as $grupo => $questoes)
				{
					foreach ($questoes as $questao_id)
					{
						$questaoQuestionario[$grupo][$questao_id]['Questao'] = $this->QuestaoQuestionario->obterQuestao($questao_id);
					}
				}
				$this->set('grupoQuestoes', $questaoQuestionario);
				$estados = $this->Candidato->Cidade->Estado->find('list', array('recursive' => '0'));
				$this->set('estados', $estados);
				$this->set('estado_selecionado', 'SP');
				$cidades = $this->Candidato->Cidade->find('list', array('conditions' => array('Cidade.estado_id' => 'SP'),
																  'fields' => array('nome')));
				$cidade_selecionada = '';
                                // Unidades
                                $array_unidade = $this->Candidato->Unidade->getSelectForm();
                                $this->set('unidades', $array_unidade);
				$this->set('cidades', $cidades);
				$this->set('cidade_selecionada', $cidade_selecionada);
				break;
		}
	}
	function exportar_lista()
	{
	}
	function preencher_questionario()
	{
		// Define o título do conteúdo da página (da barra laranja)
		$this->set('content_title', 'Preencher questionário: informe o candidato');
		$this->Candidato->recursive = '0';
		if (!empty($this->data))
		{
			$condicao = array('numero_inscricao' => $this->data['Candidato']['numero_inscricao'],
							  'ano' => $this->data['Candidato']['ano']);
			if ($this->Candidato->find('first', array('conditions' => $condicao)))
			{
				$this->redirect('/questao_questionarios/preencher/' . $this->data['Candidato']['numero_inscricao']
				. '/' . $this->data['Candidato']['ano'] . '/1');
			}
			else
			{
				$this->Session->setFlash('Nenhum candidato com o número de inscrição e ano informado foi encontrado');
			}
		}
	}
	function preencher_prova($numero_inscricao, $ano)
	{
		if ($this->Candidato->existe($numero_inscricao, $ano))
		{
			$this->redirect('/provas/preencher_direto/'.$numero_inscricao.'/'.$ano);
		}
		else
		{
			$this->Session->setFlash('Candidato não encontrado!');
			$this->redirect('/candidatos/index/');
		}	
	}
   function inserir_nota_prova_especial($numero_inscricao, $ano) 
   { 
     $candidato_id  = $this->Candidato->obterId($numero_inscricao, $ano); 
     if(!empty($this->data)) 
     { 
       if($this->data['Candidato']['nota_prova'] == null or $this->data['Candidato']['nota_prova'] == '') 
       { 
         //deve inserir um nota válida 
         $this->set('numero_inscricao', $numero_inscricao); 
         $this->set('ano', $ano); 
       } 
       else 
       { 
         $this->data['Candidato']['prova_especial'] = 1; 
         $this->data['Candidato']['candidato_id'] = $candidato_id; 
         if($this->Candidato->save($this->data)) 
         { 
           $this->Session->setFlash('Nota inserida com sucesso.'); 
           $this->redirect('/candidatos/index/'); 
         } 
         else 
         { 
           $this->Session->setFlash('Nota não cadastrada, favor chamar um técnico!'); 
           $this->redirect('/candidatos/index/'); 
         } 
       } 
     } 
     else 
     { 
       //exibir formulário 
       // Define o título do conteúdo da página (da barra laranja)
       $this->set('content_title', 'Cadastrar nota de Prova Especial'); 
       //verifica se o candidato ja possui a nota da prova 
       if($this->Candidato->fezProvaEspecial($candidato_id)) 
       { 
         $this->redirect('/candidatos/alterar_nota_prova_especial/'.$numero_inscricao.'/'.$ano); 
       } 
       $this->set('numero_inscricao', $numero_inscricao); 
       $this->set('ano', $ano); 
     } 
   } 
   function alterar_nota_prova_especial($numero_inscricao, $ano) 
   { 
     $candidato_id  = $this->Candidato->obterId($numero_inscricao, $ano); 
     if(!empty($this->data)) 
     { 
       if($this->data['Candidato']['nota_prova'] == null or $this->data['Candidato']['nota_prova'] == '') 
       { 
         //deve inserir um nota válida 
         $this->set('numero_inscricao', $numero_inscricao); 
         $this->set('ano', $ano); 
       } 
       else 
       { 
         $this->data['Candidato']['prova_especial'] = 1; 
         $this->data['Candidato']['candidato_id'] = $candidato_id; 
         if($this->Candidato->save($this->data)) 
         { 
           $this->Session->setFlash('Nota alterada com sucesso.'); 
           $this->redirect('/candidatos/index/'); 
         } 
         else 
         { 
           $this->Session->setFlash('Houve um problema no cadastro da nota, favor chamar um técnico!'); 
           $this->redirect('/candidatos/index/'); 
         } 
       } 
     } 
     else 
     { 
       //exibir formulário 
		// Define o título do conteúdo da página (da barra laranja)
       $this->set('content_title', 'Alterar nota de Prova Especial'); 
       //pega a nota de prova do candidato 
       $dados = $this->Candidato->getCandidato($numero_inscricao, $ano); 
       $this->data['Candidato']['nota_prova'] = $dados['Candidato']['nota_prova'];  
       $this->set('numero_inscricao', $numero_inscricao); 
       $this->set('ano', $ano); 
       //$this->set('nota_prova', $this->data['Candidato']['nota_prova']); 
     } 
  } 
	function remover_candidatos_aprovados_fase_classificatoria($ano_fase)
	{
		$candidatos_aprovados = $this->Candidato->getCandidatosAprovados($ano_fase);
		foreach($candidatos_aprovados as $candidato)
		{
			$this->Candidato->setCandidatoClassificatoria($candidato['Candidato']['numero_inscricao'], $candidato['Candidato']['ano'], 0);
		}	
	}
	function remover_candidatos_aprovados_fase_eliminatoria($ano_fase)
	{
		$candidatos_aprovados = $this->Candidato->getCandidatosAprovados($ano_fase);
		foreach($candidatos_aprovados as $candidato)
		{
			$this->Candidato->setCandidatoEliminatoria($candidato['Candidato']['numero_inscricao'], $candidato['Candidato']['ano'], 0);
		}	
	}
	function visualizar_grafico_socio_economico()
	{
		$this->set('content_title', 'Gráfico de pontuação sócio-econômica');
		if (!empty($this->data))
		{
			$condicao = array(
				'Candidato.pontuacao_social >' => '0',
				'Candidato.pontuacao_economica >' => '0',
				'Candidato.ano' => $this->data['Candidato']['ano'],
				'Candidato.turma' => $this->data['Candidato']['turma']);
			$this->Excel->iniciando('pontuacao_socio_economica.xls');
			$header = array('Numero de inscricao', 'Ano', 'Pontuacao Social', 'Pontuacao Economica');
			$this->Excel->writeLine($header);
			$candidatos = $this->Candidato->find('all', array('conditions' => $condicao, 'recursive' => '0'));
			foreach ($candidatos as $candidato)
			{
				$pontuacao_social = str_replace('.', ',', $candidato['Candidato']['pontuacao_social']);
				$pontuacao_economica = str_replace('.', ',', $candidato['Candidato']['pontuacao_economica']);
				$valores = array(
					$candidato['Candidato']['numero_inscricao'],
					$candidato['Candidato']['ano'],
					$pontuacao_social,
					$pontuacao_economica);
				$this->Excel->writeLine($valores);
			}
			$this->Excel->fechando();
		}
	}
	function exportar_pontuacao_socio_economica()
	{		
		$this->render('visualizar_grafico_socio_economico');
	}
	function montar_relatorio($acao = null)
	{		
		$this->set('content_title', 'Montagem de Relatório');
		if ($this->Session->check('Candidatos.relatorio.condicao'))
		{
			if ($this->Session->check('Candidatos.relatorio.nomeArquivo'))
				$this->Excel->iniciando($this->Session->read('Candidatos.relatorio.nomeArquivo'));
			else
				$this->Excel->iniciando('relatorio.xls');
			$colunas = array('nome_campo' => array(), 'valor_campo' => array());
			$linha = array();
			if ($acao == 'montar')
			{
				foreach ($this->data as $model)
				{
					foreach ($model as $campo => $valor)
					{
						if ($valor != '0')
						{
							array_push($colunas['nome_campo'], $campo);
							array_push($colunas['valor_campo'], $valor);
							// Adiciona as colunas Numero e Complemento logo após a coluna Endereco
							if ($valor == 'Endereco')
							{
								array_push($colunas['nome_campo'], 'numero');
								array_push($colunas['valor_campo'], 'Numero');
								array_push($colunas['nome_campo'], 'complemento');
								array_push($colunas['valor_campo'], 'Complemento');
							}
						}
					}
				}
				$this->Excel->writeLine($colunas['valor_campo']);
				$candidatos = $this->Candidato->find('all', array('conditions' => $this->Session->read('Candidatos.relatorio.condicao'),
																  'recursive' => '0'));
				foreach ($candidatos as $candidato)
				{
					//DOUGLAS SANTANA - procurar uma solução melhor?
					//$candidato['Candidato']['cidade'] = $candidato['Cidade']['nome'];
					foreach ($colunas['nome_campo'] as $coluna)
					{
						switch ($coluna)
						{
							case 'idade' :
								if ($candidato['Candidato']['questionario_vazio'] == '0') 
									array_push($linha, $this->Candidato->getIdade($candidato['Candidato']['numero_inscricao'],
										$candidato['Candidato']['ano']));
								else
									array_push($linha, '');
								break;
							case 'trabalho' :
								if ($candidato['Candidato']['questionario_vazio'] == '0')
									array_push($linha, $this->Candidato->getTrabalho($candidato['Candidato']['numero_inscricao'],
										$candidato['Candidato']['ano']));
								else
									array_push($linha, '');
								break;
							case 'estado_civil' :
								if ($candidato['Candidato']['questionario_vazio'] == '0')
									array_push($linha, $this->Candidato->getEstadoCivil($candidato['Candidato']['numero_inscricao'],
										$candidato['Candidato']['ano']));
								else
									array_push($linha, '');
								break;
							case 'filhos' :
								if ($candidato['Candidato']['questionario_vazio'] == '0')
									array_push($linha, $this->Candidato->getNumeroFilhos($candidato['Candidato']['numero_inscricao'],
										$candidato['Candidato']['ano']));
								else
									array_push($linha, '');
								break;
							// Obtem a cor diretamente do questionário ao invés da tabela candidato (DEIXAR COMENTADO POR ENQUANTO)
							case 'cor' :
								if ($candidato['Candidato']['questionario_vazio'] == '0')
									array_push($linha, $this->Candidato->getCor($candidato['Candidato']['numero_inscricao'],
										$candidato['Candidato']['ano']));
								break;
                            case 'conclusao_medio' :
                                if ($candidato['Candidato']['questionario_vazio'] == '0')
                                    array_push($linha, $this->Candidato->getAnoConclusaoEM($candidato['Candidato']['numero_inscricao'],
                                        $candidato['Candidato']['ano']));
                                break;
                            case 'escolaridade_pai' :
                                if ($candidato['Candidato']['questionario_vazio'] == '0')
                                    array_push($linha, $this->Candidato->getEscolaridadePai($candidato['Candidato']['numero_inscricao'],
                                        $candidato['Candidato']['ano']));
                                break;
                            case 'escolaridade_mae' :
                                if ($candidato['Candidato']['questionario_vazio'] == '0')
                                    array_push($linha, $this->Candidato->getEscolaridadeMae($candidato['Candidato']['numero_inscricao'],
                                        $candidato['Candidato']['ano']));
                                break;
                            case 'tipo_moradia' :
                                if ($candidato['Candidato']['questionario_vazio'] == '0')
                                    array_push($linha, $this->Candidato->getTipoMoradia($candidato['Candidato']['numero_inscricao'],
                                        $candidato['Candidato']['ano']));
                                break;
                            case 'num_comodos' :
                                if ($candidato['Candidato']['questionario_vazio'] == '0')
                                    array_push($linha, $this->Candidato->getNumComodos($candidato['Candidato']['numero_inscricao'],
                                        $candidato['Candidato']['ano']));
                                break;
                            case 'num_banheiros' :
                                if ($candidato['Candidato']['questionario_vazio'] == '0')
                                    array_push($linha, $this->Candidato->getNumBanheiros($candidato['Candidato']['numero_inscricao'],
                                        $candidato['Candidato']['ano']));
                                break;
                            case 'tem_internet' :
                                if ($candidato['Candidato']['questionario_vazio'] == '0')
                                    array_push($linha, $this->Candidato->getInternet($candidato['Candidato']['numero_inscricao'],
                                        $candidato['Candidato']['ano']));
                                break;
                            case 'tem_tv_cabo' :
                                if ($candidato['Candidato']['questionario_vazio'] == '0')
                                    array_push($linha, $this->Candidato->getTvCabo($candidato['Candidato']['numero_inscricao'],
                                        $candidato['Candidato']['ano']));
                                break;
                            case 'tem_telefone' :
                                if ($candidato['Candidato']['questionario_vazio'] == '0')
                                    array_push($linha, $this->Candidato->getTelefone($candidato['Candidato']['numero_inscricao'],
                                        $candidato['Candidato']['ano']));
                                break;
                            case 'conheceu_cursinho' :
                                if ($candidato['Candidato']['questionario_vazio'] == '0')
                                    array_push($linha, $this->Candidato->getConheceuCursinho($candidato['Candidato']['numero_inscricao'],
                                        $candidato['Candidato']['ano']));
                                break;

                            case 'portador_necessidade' :
                                if ($candidato['Candidato']['questionario_vazio'] == '0')
                                    array_push($linha, $this->Candidato->getPortadorNecessidade($candidato['Candidato']['numero_inscricao'],
                                        $candidato['Candidato']['ano']));
                                break;
                            case 'orientacao_sexual' :
                                if ($candidato['Candidato']['questionario_vazio'] == '0')
                                    array_push($linha, $this->Candidato->getOrientacaoSexual($candidato['Candidato']['numero_inscricao'],
                                        $candidato['Candidato']['ano']));
                                break;

							// Obtem o sexo diretamente do questionário ao invés da tabela candidato (DEIXAR COMENTADO POR ENQUANTO)
							case 'sexo' :
								if ($candidato['Candidato']['questionario_vazio'] == '0')
									array_push($linha, $this->Candidato->getSexo($candidato['Candidato']['numero_inscricao'],
										$candidato['Candidato']['ano']));
								break;
							// Obtem a etnia diretamente do questionário ao invés da tabela candidato (DEIXAR COMENTADO POR ENQUANTO)
							case 'etnia' :
								if ($candidato['Candidato']['questionario_vazio'] == '0')
									array_push($linha, $this->Candidato->getEtnia($candidato['Candidato']['numero_inscricao'],
										$candidato['Candidato']['ano']));
								break;
//							case 'etnia' :
//								if ($candidato['Candidato']['questionario_vazio'] == '0')
//								{
//									$etnia = $candidato['Candidato']['etnia1'];
//									
//									if ($candidato['Candidato']['etnia2'] != '' && $candidato['Candidato']['etnia2'] != $etnia)
//										$etnia .= ' e ' . $candidato['Candidato']['etnia2'];
//									
//									array_push($linha, $etnia);
//								}
//								else
//									array_push($linha, '');
//								
//								break;
							case 'renda_bruta' :
								if ($candidato['Candidato']['questionario_vazio'] == '0')
									array_push($linha, $this->Candidato->getRendaBruta($candidato['Candidato']['numero_inscricao'],
										$candidato['Candidato']['ano']));
								else
									array_push($linha, '');
								break;
							case 'pontuacao_social' :
								$candidato['Candidato']['pontuacao_social'] = str_replace('.', ',', $candidato['Candidato']['pontuacao_social']);
								array_push($linha, $candidato['Candidato']['pontuacao_social']);
								break;
							case 'pontuacao_economica' :
								$candidato['Candidato']['pontuacao_economica'] = str_replace('.', ',', $candidato['Candidato']['pontuacao_economica']);
								array_push($linha, $candidato['Candidato']['pontuacao_economica']);
								break;
							case 'cidade' :
								array_push($linha, $candidato['Cidade']['nome']);
								break;
							case 'estado' :
								array_push($linha, $candidato['Cidade']['estado_id']);
								break;
							case 'numero' :
								array_push($linha, $candidato['Candidato']['numero']);
								break;
							case 'complemento' :
								array_push($linha, $candidato['Candidato']['complemento']);
								break;
                            case 'unidade_id' :
								array_push($linha, $candidato['Unidade']['nome']);
								break;
                            case 'turma' :
								array_push($linha, $candidato['Candidato']['turma']);
								break;
                            case 'nota_prova' :
								array_push($linha, number_format($candidato['Candidato']['nota_prova'], 2, ',', ''));
								break;
							default : array_push($linha, $candidato['Candidato'][$coluna]);
								break; 
						}
					}
					$this->Excel->writeLine($linha);
					$linha = array();
				}
				$this->Excel->fechando();
			}
		}
	}
	function matricular_selecionar_ano()
	{
		$this->set('content_title', 'Matricular candidatos aprovados');
		if (!empty($this->data) && $this->data['Candidato']['ano'] != '')
		{
			$this->redirect('/candidatos/matricular/' . $this->data['Candidato']['ano']);
		}
	}
	function matricular($ano)
	{



		$this->set('content_title', 'Matricular candidatos aprovados');
		$this->set('ano', $ano);
		$condicao = array('Candidato.fase_classificatoria_status' => '1',
						  'Candidato.ano' => $ano);
		if ($this->Candidato->find('count', array('conditions' => $condicao, 'recursive' => '0')) > 0)
		{
			$this->paginate = array('order' => array('Candidato.ano' => 'desc',
									  				 'Candidato.numero_inscricao' => 'asc'),
									'recursive' => '0',
									'fields' => array('numero_inscricao', 'ano', 'nome', 'matriculado'),
									'limit' => 1500);
			$candidatos = $this->paginate('Candidato', $condicao);
			$this->set('candidatos', $candidatos);
		}
		else
		{
			$this->Session->setFlash('Nenhum candidato ainda foi aprovado no processo seletivo ' . $ano);
			$this->redirect('/candidatos/matricular_selecionar_ano');
		}
		if (!empty($this->data))
		{			
			$cont = 1;
			foreach ($candidatos as $candidato)
			{				
				$candidatos[$cont-1]['Candidato']['matriculado'] = $this->data['Candidato']['matriculado'.$cont];			
				$this->Candidato->save($candidatos[$cont-1]);
				if ($candidatos[$cont-1]['Candidato']['matriculado'] == 1)
				{
                    CakeLog::write('debug', 'Estudante candidato_id:'.$candidatos[$cont-1]['Candidato']['candidato_id']);
					if ($this->Candidato->Estudante->find('count', array('conditions' => array('Estudante.candidato_id' => $candidatos[$cont-1]['Candidato']['candidato_id']),
														  'recursive' => '0')) == 0)
					{
						$data['Estudante']['candidato_id'] = $candidatos[$cont-1]['Candidato']['candidato_id'];
                        $data['Estudante']['ano_letivo'] = $ano;
                        CakeLog::write('debug', 'Estudante $data save: '.json_encode($data));
						$this->Candidato->Estudante = new $this->Candidato->Estudante();
						$this->Candidato->Estudante->save($data);
					}
				}else{
                    //remove tupla de estudante caso exista
                    $this->Candidato->Estudante->removeEstudante($candidatos[$cont-1]['Candidato']['candidato_id']);
                }
				$cont++;
			}
                        $this->Session->setFlash('Candidatos do ano ' . $ano.' matrículados');
                        $this->redirect('/candidatos/matricular/'.$ano);
			$this->set('candidatos', $candidatos);
		}
	}
	function beforeFilter() {
		parent::beforeFilter(); 
        $this->Auth->allow('manutencao', 'inscricao', 'inscricao_boleto', 'branco', 'atualizar_email', 'esqueceu_senha', 'edita_inscricao', 'mensalidades', 'gerar_faturas', 'inscricao_confirmacao_questionario');
		$this->set('moduloAtual', 'candidatos');

        $this->Candidato->query('update candidato set questionario_vazio = 0 where candidato_id in (select DISTINCT candidato_id from resposta_questao_questionario)');

	}
        function manutencao(){
                $candidatos = $this->Candidato->find('all', array('conditions' => array('Candidato.ano' => '2013'), 'recursive' => -1));
                $this->loadModel('RespostaQuestaoQuestionario');
                echo 'Iniciar verificação<br/>';
                $resposta = array();
                $count = 0;
                foreach($candidatos as $candidato){
                    if($count < 500){
                        $candidato_id = $candidato['Candidato']['candidato_id'];
                        $resposta = $this->RespostaQuestaoQuestionario->obterResposta(67, $candidato_id);
                        $xml = simplexml_load_string($resposta);
                        $valor_1 = 0; $valor_2 = 0;
                        if ($xml->campos)
                        {
                            foreach ($xml->campos->campo as $campo){
                                if($campo->nome == 'qid67_txt_4_8'){
                                    $valor_1 = $campo->valor;
                                }
                                if($campo->nome == 'qid67_txt_4_9'){
                                    $valor_2 = $campo->valor;
                                }
                            }
                        }
                        if( intval($valor_1) != intval($valor_2) ){
                            echo 'Candidato: '. $candidato['Candidato']['numero_inscricao'].'<br/>';
                            echo 'Bruto: '.$valor_1.' Líquido: '.$valor_2;
                            echo '<br/><br/>';
                        }
                    }
                    $count++;
                }
                $this->render('index');
        }
    function definir_turmas_inicio(){
        $this->set('content_title', 'Fase Eliminatória');
        if (empty($this->data))
        {
            // Mostra o formulário para informar o ano do processo seletivo
        }
        else
        {
            //App::import('Model', 'ProcessoSeletivo');
            $this->loadModel('ProcessoSeletivo');
            $ano = $this->data['Candidato']['ano_fase'];
            if ($this->ProcessoSeletivo->existe($ano))
            {
                $this->redirect('/candidatos/definir_turmas/' . $ano);
            }
            else
            {
                $this->Session->setFlash('Não há um processo seletivo para o ano informado.');
            }
        }
    }
    function definir_turmas($ano)
    {
        $this->set('content_title', 'Turma dos candidatos');
        $condicao = array('Candidato.ano' => $ano);
        $this->paginate = array('order' => array('Candidato.ano' => 'desc',
            'Candidato.numero_inscricao' => 'asc'),
            'recursive' => '0',
            'fields' => array('numero_inscricao', 'ano', 'nome',
                'turma'),
            'limit' => '50');
        $candidatos = $this->paginate('Candidato', $condicao);
        $this->set('candidatos', $candidatos);
        $this->set('ano', $ano);
    }
    function definir_turmas_action()
    {
        if (!empty($this->data))
        {
            foreach ($this->data['Candidato'] as $id => $valor)
            {
                $mudou = false;
                if ($id != 'url') {
                    $candidato = $this->Candidato->find('first', array('conditions' => array('Candidato.candidato_id' => $id),
                        'recursive' => '0',
                        'fields' => array('candidato_id', 'turma', 'questionario_vazio')));
                    if ($candidato['Candidato']['turma'] != $valor['turma']){
                        $mudou = true;
                        $candidato['Candidato']['turma'] = $valor['turma'];
                    }
                    if ($mudou){
                        $this->Candidato->create();
                        $this->Candidato->save($candidato);
                    }
                }
            }
            $this->Session->setFlash('Candidatos atualizados');
            //$this->redirect('/candidatos/listar_elim_class/'.$ano);
            $this->redirect('/' . $this->data['Candidato']['url']);
        }
    }

    function inscricao()
    {
        //buscar processo seletivo aberto

        App::import('Model', 'ProcessoSeletivo');
        $this->ProcessoSeletivo = new ProcessoSeletivo();
        $processo_seletivo = $this->ProcessoSeletivo->getProcessoSeletivoAberto();


        if(empty($processo_seletivo)){

            //inscrições encerradas
            $this->set('content_title', 'Inscrições encerradas');
            $this->render('inscricao_fechada');

        }else{

            $this->set('content_title', 'Fazer inscrição');


            $this->set('processo_seletivo', $processo_seletivo);

            // Verifica se algum dado foi enviado do formulário
            if (!empty($this->data))
            {
				$forma_pagamento = json_decode($processo_seletivo['ConfiguracaoProcessoSeletivo']['forma_pagamento']);
				$forma_pagamento = $forma_pagamento[$this->data['Candidato']['forma_pagamento_index']];
								
                $this->data['Candidato']['ano'] = $processo_seletivo['ProcessoSeletivo']['ano'];
                $this->data['Candidato']['numero_inscricao'] = $this->Candidato->geraNumeroInscricao($this->data['Candidato']['ano']);
                $this->data['Candidato']['taxa_inscricao'] = 0;
                $this->data['Candidato']['cpf'] = str_replace('.', '', $this->data['Candidato']['cpf']);
                $this->data['Candidato']['cpf'] = str_replace('-', '', $this->data['Candidato']['cpf']);
				
				if(empty($forma_pagamento->num_parcelas) ){
					$this->data['Candidato']['taxa_inscricao'] = 1;
				}

                $this->Candidato->create();
                // Verifica se não existe um candidato com o número de inscrição e ano informados
                if ($this->Candidato->naoExiste($this->data['Candidato']['numero_inscricao'], $this->data['Candidato']['ano']))
                {
                    //verifica se já não foi feita uma inscrição com o CPF informado
                    if ($this->Candidato->cpfExiste($this->data['Candidato']['cpf'], $this->data['Candidato']['ano'])){
                        //verifica se ja existe o processo seletivo para este candidato
                        if($this->data['Candidato']['ano'] == '' || $this->ProcessoSeletivo->existe($this->data['Candidato']['ano']))
                        {
                            $this->data['Candidato']['processo_seletivo_id'] = $processo_seletivo['ProcessoSeletivo']['processo_seletivo_id'];
                            //setar para maiuscula os textos do formulário
                            $this->data['Candidato']['nome'] = strtoupper($this->data['Candidato']['nome']);
                            $this->data['Candidato']['nome_mae'] = strtoupper($this->data['Candidato']['nome_mae']);
                            $this->data['Candidato']['nome_pai'] = strtoupper($this->data['Candidato']['nome_pai']);
                            $this->data['Candidato']['orgao_emissor_rg'] = strtoupper($this->data['Candidato']['orgao_emissor_rg']);
                            $this->data['Candidato']['endereco'] = strtoupper($this->data['Candidato']['endereco']);
                            $this->data['Candidato']['numero'] = strtoupper($this->data['Candidato']['numero']);
                            $this->data['Candidato']['complemento'] = strtoupper($this->data['Candidato']['complemento']);
                            $this->data['Candidato']['bairro'] = strtoupper($this->data['Candidato']['bairro']);
                            $this->Candidato->set($this->data);

                            //salvando o candidato
                            if ($this->Candidato->save())
                            {
                                $candidato_id = $this->Candidato->getInsertId();

                                //criar um usuário no sistema para o candidato poder acessar posteriormente
                                $this->loadModel('User');
                                $this->data['Candidato']['senha'] = $this->Auth->password($this->data['Candidato']['senha']);
                                $this->data['Candidato']['senha_2'] = $this->Auth->password($this->data['Candidato']['senha_2']);
                                if(!$this->User->possuiUsername($this->data['Candidato']['cpf'])){
                                    $this->User->create();

                                    $user = array();
                                    $user['User']['nome'] = $this->data['Candidato']['nome'];
                                    $user['User']['username'] = $this->data['Candidato']['cpf'];
                                    $user['User']['password'] = $this->data['Candidato']['senha'];
                                    $user['User']['group_id'] = 3;

                                    if(!$this->User->save($user)){
                                        $this->Session->setFlash('USUARIO - Erro ao salvar a inscrição. For favor verifique seus dados e tente novamente.');
                                        //apagar inscricao
                                        $this->Candidato->create();
                                        $this->Candidato->delete($candidato_id);
                                        $this->redirect('/candidatos/inscricao/');
                                    }
                                }else{
                                    $user_access = $this->User->find('first',array('conditions' => array('User.username' => $this->data['Candidato']['cpf'])));
                                    if($user_access){
                                        $user_access['User']['password'] = $this->data['Candidato']['senha'];
                                        $this->User->save($user_access);
                                    }
                                }

                                //gerar o boleto da fatura, tendo o valor padrão do
                                $_SESSION['Inscricao']['candidato_id'] = $candidato_id;
                                $_SESSION['Inscricao']['faturas'] = array();

                                $this->loadModel('Fatura');
                                $fatura = array();
                                $forma_pagamento = json_decode($processo_seletivo['ConfiguracaoProcessoSeletivo']['forma_pagamento']);
                                $forma_pagamento = $forma_pagamento[$this->data['Candidato']['forma_pagamento_index']];



                                if(strlen($forma_pagamento->vencimento) < 2){
                                    $vencimento_fatura = date('Y-m-d');
                                    $time = strtotime($vencimento_fatura);
                                    $vencimento_fatura = date("Y-m-d", strtotime("+$forma_pagamento->vencimento day", $time));
									if(strtotime($processo_seletivo['ConfiguracaoProcessoSeletivo']['data_limite_pagamento']) < strtotime($vencimento_fatura) ){
										$vencimento_fatura = date("Y-m-d", strtotime($processo_seletivo['ConfiguracaoProcessoSeletivo']['data_limite_pagamento']) );
									}
                                }else{
                                    $vencimento_fatura = $this->dateFormatBeforeSave($forma_pagamento->vencimento);
                                }

                                for($count = 0; $count < $forma_pagamento->num_parcelas; $count++){
                                    $fatura['Fatura']['candidato_id'] = $candidato_id;
                                    $fatura['Fatura']['valor'] = $forma_pagamento->valor;
                                    $fatura['Fatura']['data_vencimento'] = date('Y-m-d', strtotime($vencimento_fatura));

                                    if($count == 0)
                                        $fatura['Fatura']['mensagem'] = 'NÃO RECEBER APÓS O VENCIMENTO.';
                                    else
                                        $fatura['Fatura']['mensagem'] = 'Cobrar multa de R$ 7,50 mais 2% por mês de atraso';

                                    $this->Fatura->create();
                                    if(!$this->Fatura->save($fatura)){
                                        $this->Session->setFlash(' FATURA - Erro ao salvar a inscrição. For favor verifique seus dados e tente novamente.');
                                        //apagar inscricao
                                        $this->Candidato->create();
                                        $this->Candidato->delete($candidato_id);
                                        $this->redirect('/candidatos/inscricao/');
                                    }else{
                                        $fatura_id = $this->Fatura->getInsertId();
                                        $_SESSION['Inscricao']['faturas'][] = $fatura_id;

                                        //incrementa mais um mês para a próxima fatura
                                        $time = strtotime($vencimento_fatura);
                                        $vencimento_fatura = date("Y-m-d", strtotime("+1 month", $time));
                                    }

                                }
								
								
								
                                //$this->Session->setFlash('Candidato cadastrado com sucesso. Preencha o questionário socioecônomico para finalizar a inscrição e fazer o pagamento');
                                $this->Session->setFlash('Candidato cadastrado com sucesso.');
                                $cand = $this->Candidato->getCandidatoById($candidato_id);


                                //redirecionar para a confirmação de questionário
                                $this->redirect('/candidatos/inscricao_confirmacao_questionario/'.$candidato_id);

                                //redirecionar para o questionário
                                //$this->redirect('/questao_questionarios/preencher_candidato/'.$candidato_id.'/1');

                                //OLD
                                //redirecionar para visulizar os boletos (sem preencher questionário)
                                //$this->redirect('/candidatos/inscricao_boleto/');
                            }
                            else
                            {
                                $this->Session->setFlash('Candidato não pode ser inserido. Tente novamente ou entre em contato com o cursinho');
                            }
                        }
                        else
                        {
                            //caso não tenha o processo seletivo, deve informar ao usuário para iniciar o processo.
                            $this->Session->setFlash('Candidato não pode ser inserido. Favor iniciar o Processo Seletivo pela coordenação!');
                            $this->redirect('/candidatos/index');
                        }
                    }
                    else
                    {
                        $this->Session->setFlash('ATENÇÃO! Já existe uma inscrição com o seu CPF, por favor tente fazer um login no sistema ');
                    }

                }
                else
                {
                    $this->Session->setFlash('GERAL - Erro ao salvar a inscrição. For favor verifique seus dados e tente novamente.');
                }
            }

            //verificar quantidade de inscritos
            $num_candidatos = $this->Candidato->find('count', array('conditions' => array('Candidato.processo_seletivo_id' => $processo_seletivo['ProcessoSeletivo']['processo_seletivo_id'])));

            //Configuração do menu lateral
            $this->set('moduloAtual', 'inscricao');
            $this->set('title_for_layout', 'inscricao');
            $this->set('num_candidatos', $num_candidatos);

            //Configurações do formulário de inscrição

            // Pega a lista de estados do banco de dados para colocar um combobox no formulário
            $estados = $this->Candidato->Cidade->Estado->find('list', array('recursive' => '0'));
            $this->set('estados', $estados);
            $this->set('estado_selecionado', 'SP');

            // Pega a lista de todas as cidades do estado SP para colocar um combobox no formulário
            $cidades = $this->Candidato->Cidade->find('list', array('conditions' => array('Cidade.estado_id' => 'SP'),
                'fields' => array('nome')));
            // Unidades
            $array_unidade = $this->Candidato->Unidade->getSelectForm();
            // Seleciona a cidade de São Carlos como padrão
            $cidade_selecionada = $this->Candidato->Cidade->obtemId('SAO CARLOS', 'SP');
            $this->set('cidades', $cidades);
            $this->set('unidades', $array_unidade);
            $this->set('cidade_selecionada', $cidade_selecionada);

        //inscrições encerradas
        }
    }

    function edita_inscricao($candidato_id){
        $candidato = $this->Candidato->read(null, $candidato_id);

        if($candidato['Candidato']['cpf'] == $_SESSION['Auth']['User']['username']){



            if(!empty($this->data)){

                $this->data['Candidato']['nome'] = strtoupper($this->data['Candidato']['nome']);
                $this->data['Candidato']['nome_mae'] = strtoupper($this->data['Candidato']['nome_mae']);
                $this->data['Candidato']['nome_pai'] = strtoupper($this->data['Candidato']['nome_pai']);
                $this->data['Candidato']['orgao_emissor_rg'] = strtoupper($this->data['Candidato']['orgao_emissor_rg']);
                $this->data['Candidato']['endereco'] = strtoupper($this->data['Candidato']['endereco']);
                $this->data['Candidato']['numero'] = strtoupper($this->data['Candidato']['numero']);
                $this->data['Candidato']['complemento'] = strtoupper($this->data['Candidato']['complemento']);
                $this->data['Candidato']['bairro'] = strtoupper($this->data['Candidato']['bairro']);

                if ($this->Candidato->save($this->data)){
                    $this->Session->setFlash('Dados salvos');
                }else{
                    $this->Session->setFlash('Erro ao salvar os dados. Tente novamente');
                }
                $this->redirect('/candidatos/index');
            }

        }else{
            $this->redirect('/candidatos/index');
        }

        $this->data = $candidato;

        $estados = $this->Candidato->Cidade->Estado->find('list', array('recursive' => '0'));
        $this->set('estados', $estados);
        $this->set('estado_selecionado', $this->data['Cidade']['estado_id']);
        // Pega a lista de cidades para colocar em um combobox no formulário
        $cidades = $this->Candidato->Cidade->find('list', array('conditions' => array('Cidade.estado_id' => $this->data['Cidade']['estado_id']),
            'fields' => array('nome')));
        $cidade_selecionada = $this->data['Cidade']['cidade_id'];
        $unidade_selecionada = $this->data['Candidato']['unidade_id'];
        // Unidades
        $array_unidade = $this->Candidato->Unidade->getSelectForm();
        $this->set('cidades', $cidades);
        $this->set('unidades', $array_unidade);
        $this->set('cidade_selecionada', $cidade_selecionada);
        $this->set('unidade_selecionada', $unidade_selecionada);
        $this->set('turma', $this->data['Candidato']['turma']);

        $this->set('candidato', $candidato);
    }

    function inscricao_boleto(){
        $this->set('content_title', 'Inscrição');
        if(empty($_SESSION['Inscricao']['faturas'])){
            //ocorreu um ero na geração da fatura. verificar novamente.
            //$this->Session->setFlash('Ocorreu um erro durante a inscrição, tente novamente.');
            //$this->redirect('/candidatos/inscricao/');
        }

        $this->loadModel('Fatura');
        if(!$this->Fatura->existeFaturas($_SESSION['Inscricao']['faturas'])){
            //$this->Session->setFlash('Ocorreu um erro para visualizar a fatura da inscrição. Faça o login com seu CPF e senha para visualizar a inscrição');
            //$this->redirect('/candidatos/branco/');
        }

        $this->loadModel('ProcessoSeletivo');
        $processo_seletivo = $this->ProcessoSeletivo->getProcessoSeletivoAberto();
        //verificar quantidade de inscritos
        $num_candidatos = $this->Candidato->find('count', array('conditions' => array('Candidato.processo_seletivo_id' => $processo_seletivo['ProcessoSeletivo']['processo_seletivo_id'])));
        $this->set('num_candidatos', $num_candidatos);
        $this->set('processo_seletivo', $processo_seletivo);

        $candidato = $this->Candidato->read(null, $_SESSION['Inscricao']['candidato_id']);
        $faturas = $this->Fatura->find('all', array('conditions' => array('Fatura.id' => $_SESSION['Inscricao']['faturas'] ) ) );
        //gerar código para visualizar o boleto da inscrição
        $codigo_faturas = array();
        foreach($faturas as $fatura){
            $codigo_faturas[$fatura['Fatura']['id']] = $this->codificar($fatura['Fatura']['id']);
        }
        $this->set('candidato', $candidato);
        $this->set('faturas', $faturas);
        $this->set('codigo_faturas', $codigo_faturas);

    }

    function branco(){

    }

    function atualizar_email(){
        // Define o título do conteúdo da página (da barra laranja)
        $this->set('content_title', 'Editar a ficha de inscrição de um candidato');
        $this->Candidato->recursive = '0';
        // Verifica se algum dado foi enviado do formulário
        if (!empty($this->data))
        {

            if ($this->Candidato->salvar_email($_SESSION['Auth']['User']['username'], $this->data['Candidato']['email']))
            {
                $this->Session->setFlash('E-mail atualizado com sucesso');
                $this->redirect('/candidatos/index/');
            }else{
                $this->Session->setFlash('Ocorreu um erro para salvar o e-mail, tente novamente.');
                $this->redirect('/candidatos/index/');
            }
        }
    }

    function mensalidades($processo_id, $candidato_id){
        if(empty($_SESSION['Auth']['User']))
            $this->redirect('/candidatos/index');

        //obter dados do processo seletivo
        $this->loadModel('ProcessoSeletivo');
        $processo = $this->ProcessoSeletivo->find('first', array('conditions' => array('ProcessoSeletivo.processo_seletivo_id' => $processo_id) ));

        //busca faturas do candidato
        $faturas = $this->Candidato->Fatura->getFaturasProcesso($candidato_id, $processo_id);

        $this->set(compact('processo', 'faturas'));
        $this->set('candidato_id', $candidato_id);
    }

    function gerar_faturas( $candidato_id, $processo_id, $tipo){
        $this->loadModel('Fatura');
        $this->Fatura->begin();
        $erro = false;

        //insersão manual
        //$processo_id = 10;

        if($tipo == 1){
            //gerar 1 fatura


            $fatura = array();
            $fatura['Fatura']['candidato_id'] = $candidato_id;
            $fatura['Fatura']['processo_seletivo_id'] = $processo_id;
            $fatura['Fatura']['valor'] = 190;
            $fatura['Fatura']['data_vencimento'] = '2013-09-10';
            if(!$this->Fatura->save($fatura)){
                $erro = true;
            }

        }else{
            //gerar 3 faturas

            $this->loadModel('Fatura');
            $fatura = array();
            $fatura['Fatura']['candidato_id'] = $candidato_id;
            $fatura['Fatura']['processo_seletivo_id'] = $processo_id;
            $fatura['Fatura']['valor'] = 70;
            $fatura['Fatura']['data_vencimento'] = '2013-08-20';
            if(!$this->Fatura->save($fatura)){
                $erro = true;
            }

            $fatura['Fatura']['data_vencimento'] = '2013-09-10';
            $this->Fatura->create();
            if(!$this->Fatura->save($fatura)){
                $erro = true;
            }

            $fatura['Fatura']['data_vencimento'] = '2013-10-10';
            $this->Fatura->create();
            if(!$this->Fatura->save($fatura)){
                $erro = true;
            }
        }


        if($erro){
            $this->Fatura->rollback();
            $this->Session->setFlash('Erro ao gerar as faturas. Tente novamente');
        }else{
            $this->Fatura->commit();
            $this->Session->setFlash('Faturas criadas com sucesso');
        }

        $this->redirect('/candidatos/mensalidades/'.$processo_id.'/'.$candidato_id);


    }

    function liberar_candidato($candidato_id){
        if ($this->Candidato->liberar_candidato($candidato_id))
        {
			if($candidato_id > 3750 ){
		
				//verificar se há fatura vencida para atualizar a data de vencimento.
				$this->loadModel('Fatura');
				$fatura = $this->Fatura->find('first', array('conditions' => array('Fatura.candidato_id' => $candidato_id), 'order' => array('Fatura.data_vencimento ASC')));
				if($fatura){
					//$fatura['Fatura']['data_vencimento'] = '2014-07-28';
					//$this->Fatura->create();
					//$this->Fatura->save($fatura);
				}
			}
			
		
            $this->Session->setFlash('Candidato liberado');
            $cand = $this->Candidato->getCandidatoById($candidato_id);
            $this->redirect('/candidatos/visualizar/'.$cand['Candidato']['numero_inscricao'].'/'.$cand['Candidato']['ano']);
        }else{
            $this->Session->setFlash('Ocorreu um erro para liberar o candidato, tente novamente.');
            $cand = $this->Candidato->getCandidatoById($candidato_id);
            $this->redirect('/candidatos/visualizar/'.$cand['Candidato']['numero_inscricao'].'/'.$cand['Candidato']['ano']);
        }
    }

    function ativar_inscricao($candidato_id){
        if ($this->Candidato->ativar_inscricao($candidato_id))
        {
            $this->Session->setFlash('Inscriçao ativada');
            $cand = $this->Candidato->getCandidatoById($candidato_id);
            $this->redirect('/candidatos/index/');
        }else{
            $this->Session->setFlash('Erro ao ativar a inscrição. Tente novamente ou entre em contato com o cursinho.');
            $cand = $this->Candidato->getCandidatoById($candidato_id);
            $this->redirect('/candidatos/index/');
        }
    }

    function cancelar_inscricao($candidato_id){
        if ($this->Candidato->cancelar_inscricao($candidato_id))
        {
            $this->Session->setFlash('Candidato atualizado');
            $cand = $this->Candidato->getCandidatoById($candidato_id);
            $this->redirect('/candidatos/visualizar/'.$cand['Candidato']['numero_inscricao'].'/'.$cand['Candidato']['ano']);
        }else{
            $this->Session->setFlash('Ocorreu um erro para salvar os dados do candidato, tente novamente.');
            $cand = $this->Candidato->getCandidatoById($candidato_id);
            $this->redirect('/candidatos/visualizar/'.$cand['Candidato']['numero_inscricao'].'/'.$cand['Candidato']['ano']);
        }
    }

    function inscricao_confirmacao_questionario($candidato_id){


        $this->set('candidato_id', $candidato_id);
    }

}
?>
