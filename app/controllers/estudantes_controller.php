<?php 
/**
 * Classe correspondente ao Módulo Estudante
 */
class EstudantesController extends AppController {

	//var $scaffold;

	var $layout = 'tricolor_layout';
	var $pageTitle = 'estudantes';
	var $paginate = array('joins' => 'Advertencia');

	var $helpers = array('questionario');
	var $uses = array('Estudante', 'Candidato', 'QuestaoQuestionario', 'Frequencia');

	var $components = array('Excel', 'Fpdf');

	var $mesPorExtenso = array('1' => 'Janeiro',
                                   '2' => 'Fevereiro',
                                   '3' => 'Março',
                                   '4' => 'Abril',
                                   '5' => 'Maio',
                                   '6' => 'Junho',
                                   '7' => 'Julho',
                                   '8' => 'Agosto',
                                   '9' => 'Setembro',
                                   '10' => 'Outubro',
                                   '11' => 'Novembro',
                                   '12' => 'Dezembro');

	function index()
	{
		$this->set('content_title', 'Módulo Estudante');

        //manutenção de estudantes
        $this->Estudante->mantem_consistencia();

	}

	function visualizar_ficha($estudante_id)
	{
		$this->set('content_title', 'Ficha do estudante');

		$estudante = $this->Estudante->find('first', array('conditions' => array('estudante_id' => $estudante_id),
                                                                                      'recursive' => '1'));

		//buscando turma
		$codigo_turma = 'SEM TURMA';
		$turma = $this->Estudante->getTurma($estudante_id);

                if($turma)
                    $codigo_turma = $turma['nome'];

		//fazer calculo da frequência do estudante
		$freq = $this->Frequencia->obterFrequenciaEstudanteData($estudante_id, '01', '01', '31', '12', $estudante['Estudante']['ano_letivo']);

                $dias_totais = 0;
		$presencas = 0;
		if(count($freq) > 0)
		foreach ($freq as $frequencia){

			$dias_totais += 1;
			if($frequencia['frequencia']['presente'] == 'Sim'){
					$presencas += 1;        
			} elseif($frequencia['frequencia']['presente'] == 'Justificado'){
					$presencas += 1;
			}
		}

		if($dias_totais == 0){
			$frequencia_count = 'Nenhum';
                }else{
			$frequencia_count = floatval($presencas*100/$dias_totais);

		}


		//buscando sobre mensalidade
		//...
		//SANTANA

		$estudante_id = $estudante['Estudante']['estudante_id'];		
		//$pendentes = $this->Estudante->Mensalidade->num_pendentes('all', array('conditions' => array('Mensalidade.estudante_id' => $estudante_id)));
		$pendentes = $this->Estudante->Fatura->num_pendentes($estudante_id);

		//...
                $this->set('pendentes', $pendentes);
		$this->set('estudante', $estudante);
		$this->set('frequencia_count', $frequencia_count);
        $this->set('dias_totais', $dias_totais);
		$this->set('codigo_turma', $codigo_turma);
	}

	function listar_todos()
    {
		$this->set('content_title', 'Listagem de todos os estudantes');

		//$this->Estudante->find('count', array('recursive' => '0'))

        $this->paginate = array('limit' => 30, 'order' => array('Candidato.ano' => 'desc', 'Candidato.numero_inscricao' => 'asc'));
        // Salva em uma variável de sessão a condição utilizada para fazer a listagem e um nome de arquivo.
        // Eles serão utilizados para exportar os resultados da listagem
        $this->Session->write('Estudantes.relatorio.condicao', array('Estudante.evasao' => 0));
        $this->Session->write('Estudantes.relatorio.nomeArquivo', 'listagem_estudantes.xls');
        $estudantes = $this->paginate('Estudante', array('Estudante.evasao' => 0));
        $this->set('estudantes', $estudantes);

    }

	function gerar_mensalidades($estudante_id)
	{
		$this->set('content_title', 'Gerar Mensalidades');
		$this->set('estudante_id', $estudante_id);
		$estudante = $this->Estudante->read(null, $estudante_id);
		$this->set('estudante', $estudante );

		for ($i = 1; $i <= 28; $i++)
			$dias[$i] = $i;

		$this->set('dias', $dias);

            if (!empty($this->data)) {
                if($this->Estudante->Fatura->criar_mensalidades($estudante_id, $this->data['Estudante'])){
				
                    $this->Estudante->salvar_valor_mensalidade($estudante_id, $this->data['Estudante']['valor']);
                    $this->Session->setFlash('Mensalidades geradas');
					
                }else{
                    $this->Session->setFlash('Falha ao gerar as mensalidades. Entre em contato com o suporte');
                }

                $this->redirect('/estudantes/visualizar_ficha/'.$estudante_id);
            }
	}

	function visualizar_mensalidades($estudante_id)
	{
		$this->set('content_title', 'Mensalidades do Estudante');

		$numero_inscricao = $this->Estudante->obterNumeroInscricao($estudante_id);
		$ano = $this->Estudante->obterAno($estudante_id);

		$estudante = $this->Estudante->read(null, $estudante_id);

		$mensalidades = $this->Estudante->Fatura->find('all', array('conditions' => array('Fatura.estudante_id' => $estudante_id), 'order' => array('Fatura.ano_ref ASC', 'Fatura.mes_ref ASC')));

		$this->set('estudante', $estudante);
		$this->set('mensalidades', $mensalidades);
        $this->set('meses', $this->Estudante->Fatura->getMeses());
	}

	function registrar_pagamento_mensalidade($fatura_id)
	{
		$this->set('content_title', 'Registrar pagamento de mensalidade');
		
		$fatura = $this->Estudante->Fatura->read(null, $fatura_id);
		$estudante = $this->Estudante->read(null, $fatura['Fatura']['estudante_id']);
		
		$this->set('estudante', $estudante);
		$this->set('fatura', $fatura);
		

		if (!empty($this->data)) {
			if($this->data['Fatura']['isento']){
				$status = $this->Estudante->Fatura->isentar($fatura_id);
			}else{
				$status = $this->Estudante->Fatura->baixa_manual($fatura_id, $this->data['Fatura']['data_pagamento']);
			}
			
			if($status){
				$this->Session->setFlash('Baixa registrada');
			}else{
				$this->Session->setFlash('Erro ao registrar a baixa.Tente novamente');
			}
			$this->redirect('/estudantes/visualizar_mensalidades/'.$fatura['Fatura']['estudante_id']);
			
		}
	}

        function gerar_recibo_mensalidade($id){ //recibo_mensalidade_id
            $mensalidade = $this->Estudante->Mensalidade->find('first', array('conditions' => array( 'Mensalidade.recibo_mensalidade_id' => $id)));
            $candidato = $this->Estudante->Candidato->find('first', array('conditions' => array( 'Candidato.candidato_id' => $mensalidade['Estudante']['candidato_id'])));

//            $this->debugVar($mensalidade);
//            die();

            // variaveis
            $font = 'arial';
            $startX = 10;
            $startY = 10;
            $width = 210;
            $height = 297;
            $margin = 20;     // margem contando ambos os lados
            $main_width = 220;
            $main_height = 150;
            $retorno_width = 57;
            $retorno_height = 190;
            $comprovante_width = 150;
            $comprovante_height = 40;
            $comprovanteobs_width = 70;
            $this->Fpdf->FpdfComponent("P", "mm", "A4");
                    $pdf = $this->Fpdf; 
                    //cabeçalho do arquivo
                    $pdf->header_tipo('ficha_inscricao');
            $pdf->SetAutoPageBreak(false);
            $pdf->AddPage();
            $pdf->SetFont($font, '', 10);
            $pdf->SetTitle("Recido de pagamento de mensalidade");
            $pdf->SetSubject("Recido de pagamento de mensalidade");
            //cabeçalho do documento
            //imagem do cabeçalho
            $pdf->SetXY($startX + 1, $startY + 1);
            $pdf->Image($this->url_logo_ufscar, 15, 22, 45, 36);

            $pdf->SetXY($startX + 85, $startY + 1);
            $pdf->SetFont($font, 'B', 12);
            $pdf->Text($startX + 85, $startY + 5, 'UNIVERSIDADE FEDERAL DE SÃO CARLOS');
            $pdf->Text($startX + 85, $startY + 10, 'PRÓ REITORIA DE GRADUAÇÃO');
            $pdf->Text($startX + 85, $startY + 15, 'PRÓ REITORIA DE EXTENSÃO');
            $pdf->SetFont($font, 'B', 10);
            $pdf->Text($startX + 85, $startY + 20, 'NÚCLEO DE EXTENSÃO UFSCAR-ESCOLA');
            $pdf->SetFont($font, '', 9);
            $pdf->Text($startX + 85, $startY + 25, 'PROJETO DE EXTENSÃO CURSO PRÉ-VESTIBULAR DA UFSCAR');
            $pdf->SetFont($font, '', 10);
            $pdf->Text($startX + 85, $startY + 30, 'Via Washington Luiz, Km. 235 - Caixa Postal 676');
            $pdf->Text($startX + 85, $startY + 35, 'Fones: (016) 3351-8433 Telefax: (016) 3351.2081 ');
            $pdf->Text($startX + 85, $startY + 40, 'CEP 13565-905 - São Carlos - SP - Brasil');
            $pdf->Text($startX + 85, $startY + 45, 'e.mail: cursinho.ufscar@gmail.com');

            //texto da folha
            //titulo
            $pdf->SetXY($startX + 1, $startY + 66);
            $pdf->SetFont($font, 'B', 14);
            $pdf->Text($startX + 50, $startY + 85, 'Recibo de pagamento de mensalidade');
            //corpo da mensagem
            $pdf->SetFont($font, '', 12);
            $pdf->SetXY($startX + 1, $startY + 100);
                    $texto = '     Declaramos que o estudante '.$candidato['Candidato']['nome'].', portador do RG nº. '.$candidato['Candidato']['rg'].', pagou a mensalidade do mês de '.$mensalidade['Mensalidade']['mes'].', com vencimento no dia '.$mensalidade['Mensalidade']['data_pagamento_form'];
            $pdf->MultiCell(180, 8, $texto, 0, 'J', 0);

            $pdf->Text($startX + 60, $startY + 170, 'São Carlos, _______ de _______________________de _______.');
            $pdf->Text($startX + 90, $startY + 230, '______________________________________');
            $pdf->Text($startX + 120, $startY + 240, 'Coordenação');

            $pdf->Output('comprovante_pagamento_mensalidade_'.$candidato['Candidato']['candidato_id'].'_'.$mensalidade['Mensalidade']['mes'].'.pdf','D');

        }

        function rematricular(){
            $this->set('content_title', 'Rematricular estudante');
            //verificar se o candidato já possui duas matrículas
            $estudantes = $this->Estudante->find('all', array('conditions' => array('Estudante.candidato_id' => $this->data['Estudante']['candidato_id'], 'Estudante.evasao' => '0')));

            if(empty($estudantes)){
                $this->Session->setFlash('Não há estudante');
                $this->redirect('/estudantes');
            }
            if(count($estudantes) > 1){
                $this->Session->setFlash('Não é possível rematrícular pois já possui duas matrículas. Veja a segunda matrícula abaixo');
                $this->redirect('/estudantes/visualizar_ficha/'.$estudantes[1]['Estudante']['estudante_id']);
            }
            if(!empty($this->data)){

                $candidato = $this->Estudante->Candidato->find('first', array('conditions' => array('Candidato.candidato_id' => $this->data['Estudante']['candidato_id']), 'fields' => array('Candidato.candidato_id', 'Candidato.rematriculado' )));
                $candidato['Candidato']['rematriculado'] = 1;
                $candidato['Candidato']['prova_nota_rematriculado'] = $this->data['Estudante']['prova_nota_rematriculado'];

                if($this->Estudante->Candidato->save($candidato)){
                    $this->Session->setFlash('Remetrícula feita com sucesso.');
                }else{
                    $this->Session->setFlash('Não foi possível fazer a rematrícula. Tente novamente');
                }

                $this->redirect('/estudantes/visualizar_ficha/'.$this->data['Estudante']['estudante_id']);



                //Não usado
                if(false){
                    //criar a tupla em estudante
                    $data = array();
                    $data['Estudante']['candidato_id'] = $this->data['Estudante']['candidato_id'];
                    $data['Estudante']['ano_letivo'] = $this->data['Estudante']['ano_letivo'];
                    $this->Candidato->Estudante = new $this->Candidato->Estudante();
                    if($this->Candidato->Estudante->save($data)){
                        $estudante_id = $this->Candidato->Estudante->getInsertId();

                        //atualizar o status da rematrícula do candidato.
                        $candidato = $this->Estudante->Candidato->find('first', array('conditions' => array('Candidato.candidato_id' => $this->data['Estudante']['candidato_id']), 'fields' => array('Candidato.candidato_id', 'Candidato.rematriculado' )));
                        $candidato['Candidato']['rematriculado'] = 1;
                        $candidato['Candidato']['prova_nota_rematriculado'] = $this->data['Estudante']['prova_nota_rematriculado'];
                        $this->Estudante->Candidato->save($candidato);

                        $this->Session->setFlash('Remetrícula feita com sucesso. Veja a ficha do novo estudante');
                        $this->redirect('/estudantes/visualizar_ficha/'.$estudante_id);
                    }else{
                        $this->Session->setFlash('Não foi possível fazer a rematrícula. Tente novamente');
                        $this->redirect('/estudantes/visualizar_ficha/'.$estudantes[0]['Estudante']['estudante_id']);
                    }
                }

            }

        }

    /*
     * Listas estudantes evadidos
     */
    function listar_evasao()
    {

        $this->set('content_title', 'Estudantes com evasão');
        //$this->Estudante->find('count', array('recursive' => '0'))
//        $this->Estudante->bindModel(array('hasMany' => array('Advertencia')));
//        $this->paginate['Estudante']['contain'][] = 'Advertencia';
//        $this->paginate = array('limit' => 30, 'order' => array(), 'recursive'=>2);
        $this->paginate = array(
            'joins' => array(array('table'=> 'evasao',
                'type' => 'INNER',
                'alias' => 'Evasao',
                'conditions' => array('Evasao.estudante_id = Estudante.estudante_id'))),
            'limit' => 30,
            'order' => 'Evasao.data DESC',
            'group' => array(
                'Estudante.estudante_id'
            ));
        $condicao = array();

        // Salva em uma variável de sessão a condição utilizada para fazer a listagem e um nome de arquivo.
        // Eles serão utilizados para exportar os resultados da listagem
        $this->Session->write('Estudantes.relatorio.condicao', array('1' => '1'));
        $this->Session->write('Estudantes.relatorio.nomeArquivo', 'listagem_evasaos.xls');			
        $estudantes = $this->paginate('Estudante', $condicao );
        $this->set('estudantes', $estudantes);
    }

    function listar_advertencias()
    {
        $this->set('content_title', 'Estudantes com advertências');
        //$this->Estudante->find('count', array('recursive' => '0'))
//        $this->Estudante->bindModel(array('hasMany' => array('Advertencia')));
//        $this->paginate['Estudante']['contain'][] = 'Advertencia';
//        $this->paginate = array('limit' => 30, 'order' => array(), 'recursive'=>2);
        $this->paginate = array(
            'joins' => array(array('table'=> 'advertencia',
                'type' => 'INNER',
                'alias' => 'Advertencia',
                'conditions' => array('Advertencia.estudante_id = Estudante.estudante_id'))),
            'limit' => 30,
            'order' => 'Advertencia.data_advertencia DESC',
            'group' => array(
                'Estudante.estudante_id'
            ));
        $condicao = array();

        // Salva em uma variável de sessão a condição utilizada para fazer a listagem e um nome de arquivo.
        // Eles serão utilizados para exportar os resultados da listagem
        $this->Session->write('Estudantes.relatorio.condicao', array('1' => '1'));
        $this->Session->write('Estudantes.relatorio.nomeArquivo', 'listagem_advertencias.xls');			
        $estudantes = $this->paginate('Estudante', $condicao );
        $this->set('estudantes', $estudantes);
    }

	function listar_filtro()
	{
		// Define o título do conteúdo da página (da barra laranja)
		$this->set('content_title', 'Estudantes encontrados pelo filtro');
		$this->Estudante->Candidato->recursive = '0';
		// Verifica se está salvo em uma variável de sessão um array que contém os campos que serão utilizados
		//   na condição de busca
		if ($this->Session->check('Estudantes.filtro'))
		{
			$filtro = $this->Session->read('Estudantes.filtro');

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
			$this->Session->write('Estudantes.relatorio.condicao', $condicao);
			$this->Session->write('Estudantes.relatorio.nomeArquivo', 'resultado_filtro.xls');

			// Verifica se o array $condicao não está vazio
			if (!empty($condicao))
			{
				//print_r($condicao);
				// Se o número de candidatos encontrados for maior que zero, verifica quais deles são
				//   estudantes e exibe os resultados
				if ($this->Estudante->Candidato->find('count', array('conditions' => $condicao)) > 0)
				{					
					$candidatos = $this->Estudante->Candidato->find('all', array('conditions' => $condicao,
																				 'fields' => array('candidato_id')));

					$candidatos_ids = array();

					foreach ($candidatos as $candidato)
					{
						array_push($candidatos_ids, $candidato['Candidato']['candidato_id']);
					}

					$condicao = array('Estudante.candidato_id' => $candidatos_ids, 'Estudante.evasao' => 0);

					if ($this->Estudante->find('count', array('conditions' => $condicao)) > 0)
					{
						$this->paginate = array('limit' => 30, 'order' => array('Candidato.ano' => 'desc',
																			    'Candidato.numero_inscricao' => 'asc'));
						$candidatos = $this->paginate('Estudante', $condicao);
						$this->set('estudantes', $candidatos);
					}
					else
					{
						$this->Session->setFlash('Nenhum estudante foi encontrado');
						$this->redirect('/estudantes/filtrar/formulario');
					}
				}
				// Senão, mostra uma mensagem e exibe novamente o formulário de filtro
				else
				{
					$this->Session->setFlash('Nenhum estudante foi encontrado');
					$this->redirect('/estudantes/filtrar/formulario');
				}
			}
			// Se todos os campos do formulário de filtro estão vazios, então redireciona para
			//   a listagem de todos os candidatos
			else
			{
				$this->redirect('/estudantes/listar_todos');
			}
		}
		else
		{
			$this->redirect('/estudantes/listar_todos');
		}
	}

    function filtrar($acao)
    {
		// Aqui é definido 13 grupos de questões, onde cada grupo possui algumas determinadas questões
		// Esses grupos são os que aparecem dentro da opção 'Campos do questionário' na página de filtro
		$grupoQuestaoQuestionario = array('0' => array('1', '2', '3'),
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

    	switch ($acao)
    	{
			case 'resultados' :
				// Verifica se algum dado foi enviado do formulário
				if (!empty($this->data))
				{					
					$contador = 1;
					$conjuntoArray = '';
					$conjunto = '';
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

					$this->data['Candidato'] = $this->data['Estudante'];
					unset($this->data['Estudante']);

					// Guarda o conjunto de ids dos candidatos na variável data
					$this->data['Candidato']['candidato_id'] = $candidatos;
                    if($this->data['Candidato']['rematriculado'] == 0){
                        unset($this->data['Candidato']['rematriculado']);
                    }

					// Retira a definição do campo estado do array data, pois ele não será utilizado
					unset($this->data['Candidato']['estado']);


					$postConditions = array('nome' => 'LIKE',
								  			'endereco' => 'LIKE',
								  			'bairro' => 'LIKE');

					$condicao = $this->postConditions($this->data, $postConditions);

					// Guarda na sessão a variável Estudantes.filtro com valores dos campos do formulário
					$this->Session->write('Estudantes.filtro', $condicao);

					//print_r($condicao);

					// Redireciona para a função que cuida de exibir os resultados
					$this->redirect('/estudantes/listar_filtro');
				}

				break;

			default :
				// Define o título do conteúdo da página (da barra laranja)
				$this->set('content_title', 'Filtrar');
				// Apaga da sessão a variável Candidatos.filtro, limpando os valores dos campos
				//    do formulário utilizados no filtro anterior
				$this->Session->delete('Estudantes.filtro');
				foreach ($grupoQuestaoQuestionario as $grupo => $questoes)
				{
					foreach ($questoes as $questao_id)
					{
						$questaoQuestionario[$grupo][$questao_id]['Questao'] = $this->QuestaoQuestionario->obterQuestao($questao_id);
					}
				}
				$this->set('grupoQuestoes', $questaoQuestionario);

				$estados = $this->Estudante->Candidato->Cidade->Estado->find('list', array('recursive' => '0'));
				$this->set('estados', $estados);
				$this->set('estado_selecionado', 'SP');

				$cidades = $this->Estudante->Candidato->Cidade->find('list', array('conditions' => array('Cidade.estado_id' => 'SP'),
																     'fields' => array('nome')));

				$cidade_selecionada = '';

				$this->set('cidades', $cidades);
				$this->set('cidade_selecionada', $cidade_selecionada);
				break;
    	}
    }

    /*
     * Alterar turma
     */
    function alterar_turma($estudante_id, $ano_letivo )
    {
    	$this->set('content_title', 'Alterar turma');

    	$turmas_disponiveis = array();

    	//Obter dados do estudante
    	$estudante = $this->Estudante->getEstudante($estudante_id);

    	//verificar salas que ainda tenha vaga para o estudante
    	$turmas = $this->Estudante->Turma->getAllTurmasPorAnoLetivo($ano_letivo);

    	//verificar qual sala tem vaga disponível.
    	foreach($turmas as $turma)
    	{
    		$num_estudantes = count($this->Estudante->getAllEstudantesPorTurma($turma['Turma']['id']));
    		if ($num_estudantes < $turma['Turma']['vagas'])
    			$turmas_disponiveis[] = $turma;
    	}

    	$this->set('estudante', $estudante);
        $this->set('ano', $ano_letivo);
    	$this->set('turmas_disponiveis', $turmas_disponiveis);
    }

    function alterar_turma_direto($estudante_id, $turma_id)
    {
        $turma = $this->Estudante->Turma->read(null, $turma_id);

    	$estudante = $this->Estudante->getEstudante($estudante_id);
    	$estudante['Estudante']['turma_id'] = $turma_id;
    	if($this->Estudante->save($estudante)){
                //gravar histórico de turma, gravando no histórico o registro do ultima turma do ano
                $this->Estudante->grava_turma($estudante_id, $turma_id, $turma['Turma']['ano_letivo']);
    		$this->Session->setFlash('Turma alterada com sucesso!');
        }else
    		$this->Session->setFlash('Houve um erro ao trocar de turma. Avise um técnico');
    	$this->redirect('/estudantes/visualizar_ficha/'.$estudante['Estudante']['estudante_id']);
    }

    function remover_turma_direto($estudante_id)
    {
    	$estudante = $this->Estudante->getEstudante($estudante_id);
    	$estudante['Estudante']['turma_id'] = NULL;
    	if($this->Estudante->save($estudante))
    		$this->Session->setFlash('Estudante removido da turma com sucesso!');
    	else
    		$this->Session->setFlash('Houve um erro ao remover o estudante da turma. Avise um técnico');
    	$this->redirect('/estudantes/visualizar_ficha/'.$estudante['Estudante']['estudante_id']);
    }

    function declaracao_frequencia(){

    }

    function relatorio_atestado_matricula($estudante_id)
    {
    	$possui_presenca = 1;
    	// Verificar se pode ter o atestado
    	// 70% de freguencia nas aulas

    	if($possui_presenca == 1)
    	{
    		$estudante = $this->Estudante->getEstudante($estudante_id);

	    	// variaveis
	        $font = 'arial';
	        $startX = 10;
	        $startY = 10;
	        $width = 210;
	        $height = 297;
	        $margin = 20;     // margem contando ambos os lados

	        $main_width = 220;
	        $main_height = 150;
	        $retorno_width = 57;
	        $retorno_height = 190;
	        $comprovante_width = 150;
	        $comprovante_height = 40;
	        $comprovanteobs_width = 70;

	        $this->Fpdf->FpdfComponent("P", "mm", "A4");
			$pdf = $this->Fpdf; 

			//cabeçalho do arquivo
			$pdf->header_tipo('ficha_inscricao');

	        $pdf->SetAutoPageBreak(false);
	        $pdf->AddPage();
	        $pdf->SetFont($font, '', 10);
	        $pdf->SetTitle("Atestado Matrícula");
	        $pdf->SetSubject("Atestado de matrícula");

	        //cabeçalho do documento

	        //imagem do cabeçalho
	        $pdf->SetXY($startX + 1, $startY + 1);
	        $pdf->Image($this->url_logo_ufscar, 15, 22, 45, 36);

	        $pdf->SetXY($startX + 85, $startY + 1);
	        $pdf->SetFont($font, 'B', 12);
	        $pdf->Text($startX + 85, $startY + 5, 'UNIVERSIDADE FEDERAL DE SÃO CARLOS');
	        $pdf->Text($startX + 85, $startY + 10, 'PRÓ REITORIA DE GRADUAÇÃO');
	        $pdf->Text($startX + 85, $startY + 15, 'PRÓ REITORIA DE EXTENSÃO');
	        $pdf->SetFont($font, 'B', 10);
	        $pdf->Text($startX + 85, $startY + 20, 'NÚCLEO DE EXTENSÃO UFSCAR-ESCOLA');
	        $pdf->SetFont($font, '', 9);
	        $pdf->Text($startX + 85, $startY + 25, 'PROJETO DE EXTENSÃO CURSO PRÉ-VESTIBULAR DA UFSCAR');
	        $pdf->SetFont($font, '', 10);
	        $pdf->Text($startX + 85, $startY + 30, 'Via Washington Luiz, Km. 235 - Caixa Postal 676');
			$pdf->Text($startX + 85, $startY + 35, 'Fones: (016) 3351-8433 Telefax: (016) 3351.2081 ');
			$pdf->Text($startX + 85, $startY + 40, 'CEP 13565-905 - São Carlos - SP - Brasil');
			$pdf->Text($startX + 85, $startY + 45, 'e.mail: cursinho.ufscar@gmail.com');

			//texto da folha

			//titulo
			$pdf->SetXY($startX + 1, $startY + 66);
	        $pdf->SetFont($font, 'B', 14);
	        $pdf->Text($startX + 70, $startY + 85, 'Atestado de Matrícula');

	        //corpo da mensagem
	        $pdf->SetFont($font, '', 12);
	        $pdf->SetXY($startX + 1, $startY + 100);
			$texto = '     Declaramos para os devidos fins que '.$estudante['Candidato']['nome'].', portador do RG nº. '.$estudante['Candidato']['rg'].', é aluno regular do cursinho Pré-Vestibular da UFSCar.';

	        $pdf->MultiCell(180, 8, $texto, 0, 'J', 0);

			$pdf->Text($startX + 60, $startY + 170, 'São Carlos, _______ de _______________________de _______.');
			$pdf->Text($startX + 90, $startY + 230, '______________________________________');
			$pdf->Text($startX + 120, $startY + 240, 'Coordenação');

			$pdf->Output('atestado_matric_'.$estudante_id.'.pdf','D');
    	}
    	else
    	{
    		$this->Session->setFlash('Estudante possui menos de 70% de frequência. Não pode retirar atestado.'); 
        	$this->redirect('/estudantes/index'); 
    	} 
    }

    function carta_de_mensalidade($estudante_id)
    {
    	if(!isset($this->data['CartaMensalidade']))
    	{

    		//confirmar com o usuário os valores que serão inseridos.
    		$this->set('content_title', 'Carta de Mensalidade');

    		$estudante = $this->Estudante->read(null, $estudante_id);
	    	$nome = $estudante['Candidato']['nome'];
	    	$ano = $estudante['Candidato']['ano'];

	    	//obter numero de mensalidades, contando elas do banco
	    	//...
	    	//falta fazer
	    	//...
	    	$numero_mensalidades = 0;
	    	$valor_mensalidade = 0;

    		if ($valor_mensalidade == -1)
	    	{
	    		$this->Session->setFlash('Estudante não possui mensalidades cadastradas. Peça a coordenação que realize esta operação.'); 
	        	$this->redirect('/estudantes/index');
	    	}
	    	$intervalo = $this->Estudante->Mensalidade->obterIntervaloMeses();

                //buscando turma
		$codigo_turma = 'SEM TURMA';
		$turma = $this->Estudante->getTurma($estudante_id);

                if($turma)
                    $codigo_turma = $turma['nome'];

	    	//colocando na view
	    	$this->set('estudante_id', $estudante['Estudante']['estudante_id']);
                $this->set('estudante', $estudante);
	    	$this->set('nome', $nome);
	    	$this->set('ano', $ano);
	    	$this->set('codigo_turma', $codigo_turma);
	    	$this->set('numero_mensalidades', $numero_mensalidades);
	    	$this->set('valor_mensalidade', $valor_mensalidade);
	    	$this->set('intervalo', $intervalo);
    	}
    	else 
    	{
    		//gerar a carta.

    		$nome = $this->data['CartaMensalidade']['nome'];
    		$ano = $this->data['CartaMensalidade']['ano'];
    		$turma = $this->data['CartaMensalidade']['turma'];
    		$numero_mensalidades = $this->data['CartaMensalidade']['numero_mensalidades'];
    		$valor_mensalidade = $this->data['CartaMensalidade']['valor_mensalidade'];
    		$intervalo = $this->data['CartaMensalidade']['intervalo'];

	    	// variaveis
	        $font = 'arial';
	        $startX = 10;
	        $startY = 10;
	        $width = 210;
	        $height = 297;
	        $margin = 20;     // margem contando ambos os lados

	        $main_width = 220;
	        $main_height = 150;
	        $retorno_width = 57;
	        $retorno_height = 190;
	        $comprovante_width = 150;
	        $comprovante_height = 40;
	        $comprovanteobs_width = 70;

	        $this->Fpdf->FpdfComponent("P", "mm", "A4");
			$pdf = $this->Fpdf; 

			//cabeçalho do arquivo
			$pdf->header_tipo('');

	        $pdf->SetAutoPageBreak(false);
	        $pdf->AddPage();
	        $pdf->SetFont($font, '', 10);
	        $pdf->SetTitle("Carta de Mensalidade");
	        $pdf->SetSubject("Carta de mensalidade");

	        //titulo
	        $pdf->SetFont($font, 'B', 12);
	        $pdf->Text($startX + 45, $startY + 5, 'UNIVERSIDADE FEDERAL DE SÃO CARLOS');
	        $pdf->Text($startX + 50, $startY + 10, 'CURSO PRÉ-VESTIBULAR DA UFSCar');

	        //corpo da mensagem

	        $pdf->SetFont($font, '', 12);
	        $pdf->SetXY($startX + 1, $startY + 20);
			$texto = 'O Curso Pré-vestibular da UFSCar vem por meio deste comunicado informar'.
				' o(a) estudante '.$nome.' - Turma '.$turma.', que o'.
				' valor da sua mensalidade é R$ '.$valor_mensalidade.'.';
	        $pdf->MultiCell(180, 5, $texto, 0, 'J', 0);

	        $pdf->SetXY($startX + 1, $startY + 35);
			$texto = 'As mensalidades devem ser pagas nos dias estipulados para pagamento, em geral, até o dia 16 de cada mês. O estudante deverá efetuar depósito bancário na conta FAI-UFSCar - Banco do Brasil, agência 1888-0, conta corrente 7215-X, no valor de sua mensalidade.';
	        $pdf->MultiCell(180, 5, $texto, 0, 'J', 0);

	        $pdf->SetXY($startX + 1, $startY + 55);
			$texto = 'Para o ano de '.$ano.', serão consideradas '.$numero_mensalidades.' mensalidades ('.$intervalo.'). ';
	        $pdf->MultiCell(180, 5, $texto, 0, 'J', 0);

	        $pdf->SetXY($startX + 1, $startY + 65);
			$texto = 'Na impossibilidade de pagamento da mensalidade ou em caso de desligamento voluntário, o estudante deverá avisar a Coordenação.';
	        $pdf->MultiCell(180, 5, $texto, 0, 'J', 0);

	        $pdf->SetXY($startX + 1, $startY + 75);
			$texto = 'Aceito os termos desse comunicado e assino abaixo.';
	        $pdf->MultiCell(180, 8, $texto, 0, 'J', 0);

	        $pdf->SetXY($startX + 1, $startY + 87);
			$texto = '____________________________________________';
	        $pdf->MultiCell(180, 8, $texto, 0, 'J', 0);

	        $pdf->SetXY($startX + 1, $startY + 97);
			$texto = 'São Carlos, ____ de _____ de ______. ';
	        $pdf->MultiCell(180, 8, $texto, 0, 'J', 0);

	        $pdf->SetXY($startX + 1, $startY + 115);
			$texto = '___________________________________________________________________________';
	        $pdf->MultiCell(180, 8, $texto, 0, 'J', 0);

	        //segunda parte da carta de mensalidade

	        //titulo
	        $pdf->SetFont($font, 'B', 12);
	        $pdf->Text($startX + 45, $startY + 139, 'UNIVERSIDADE FEDERAL DE SÃO CARLOS');
	        $pdf->Text($startX + 50, $startY + 144, 'CURSO PRÉ-VESTIBULAR DA UFSCar');

	        //corpo da mensagem

	        $pdf->SetFont($font, '', 12);
	        $pdf->SetXY($startX + 1, $startY + 155);
			$texto = 'O Curso Pré-vestibular da UFSCar vem por meio deste comunicado informar'.
				' o(a) estudante '.$nome.' - Turma '.$turma.', que o'.
				' valor da sua mensalidade é R$ '.$valor_mensalidade.'.';
	        $pdf->MultiCell(180, 5, $texto, 0, 'J', 0);

	        $pdf->SetXY($startX + 1, $startY + 170);
			$texto = 'As mensalidades devem ser pagas nos dias estipulados para pagamento, em geral, até o dia 16 de cada mês. O estudante deverá efetuar depósito bancário na conta FAI-UFSCar - Banco do Brasil, agência 1888-0, conta corrente 7215-X, no valor de sua mensalidade.';
	        $pdf->MultiCell(180, 5, $texto, 0, 'J', 0);

	        $pdf->SetXY($startX + 1, $startY + 190);
			$texto = 'Para o ano de '.$ano.', serão consideradas '.$numero_mensalidades.' mensalidades ('.$intervalo.'). ';
	        $pdf->MultiCell(180, 5, $texto, 0, 'J', 0);

	        $pdf->SetXY($startX + 1, $startY + 200);
			$texto = 'Na impossibilidade de pagamento da mensalidade ou em caso de desligamento voluntário, o estudante deverá avisar a Coordenação.';
	        $pdf->MultiCell(180, 5, $texto, 0, 'J', 0);

	        $pdf->SetXY($startX + 1, $startY + 210);
			$texto = 'Aceito os termos desse comunicado e assino abaixo.';
	        $pdf->MultiCell(180, 8, $texto, 0, 'J', 0);

	        $pdf->SetXY($startX + 1, $startY + 222);
			$texto = '____________________________________________';
	        $pdf->MultiCell(180, 8, $texto, 0, 'J', 0);

	        $pdf->SetXY($startX + 1, $startY + 232);
			$texto = 'São Carlos, ____ de _____ de ______. ';
	        $pdf->MultiCell(180, 8, $texto, 0, 'J', 0);

			$pdf->Output('atestado_matric_'.$estudante_id.'.pdf','D');
    	}

    }

    function gerar_lista_estudantes_excel($tipo, $ano)
    {
    	$estudantes = $this->Estudante->listar_todos_sem_evasao_por_ano($ano);

    	if($tipo == 'athenas')
    		$this->Excel->iniciando('relatorio_lista_estudantes_athenas.xls');
    	elseif ($tipo == 'ru')
    		$this->Excel->iniciando('relatorio_lista_estudantes_ru.xls');

   		$myArr=array('Processo Seletivo: ', $ano);
   		$this->Excel->writeLine($myArr);
   		$myArr=array(' ');
   		$this->Excel->writeLine($myArr);

		$myArr=array('Estudantes', 'RG');
   		$this->Excel->writeLine($myArr);
   		$myArr=array(' ');
   		$this->Excel->writeLine($myArr);

    	foreach ($estudantes as $estudante)
		{
			$myArr = array($estudante['Candidato']['nome'], $estudante['Candidato']['rg']);
   			$this->Excel->writeLine($myArr);
		}

		$this->Excel->fechando();
    }

	/*
     * Gerar lista de estudantes para o R.U. ou Athenas.
     */
    function gerar_lista_estudantes($tipo, $ano)
    {
    	//obtenção dos estudantes
    	$estudantes = $this->Estudante->listar_todos_sem_evasao_por_ano($ano);

    	//diferenciando a mensagem de descrição da lista.
    	if($tipo == 'athenas')
    		$texto = 'Lista de alunos do Curso Pré-Vestibular da UFSCar '.$ano.' que podem fazer carteirinha de passe escolar para transporte junto a Athenas Paulista, empresa de transporte coletivo de São Carlos. ';
    	elseif ($tipo == 'ru')
    		$texto = 'Lista de alunos matriculados do Curso Pré-Vestibular da UFSCar '.$ano.' que podem frequentar o RU da UFSCar.';

    	//iniciar criação do Pdf

    	// variaveis
        $font = 'arial';
        $startX = 10;
        $startY = 10;
        $width = 210;
        $height = 297;
        $margin = 20;     // margem contando ambos os lados
        $main_width = 220;
        $main_height = 150;
        $retorno_width = 57;
        $retorno_height = 190;
        $comprovante_width = 150;
        $comprovante_height = 40;
        $comprovanteobs_width = 70;
        $this->Fpdf->FpdfComponent("P", "mm", "A4");
		$pdf = $this->Fpdf; 
		//cabeçalho do arquivo
		$pdf->header_tipo('ficha_inscricao');
        $pdf->SetAutoPageBreak(false);
        $pdf->AddPage();
        $pdf->SetFont($font, '', 10);

        if($tipo == 'athenas'){
    		$pdf->SetTitle("Lista Estudantes Athenas");
        	$pdf->SetSubject("Lista Estudantes Athenas");
        }
    	elseif ($tipo == 'ru'){
    		$pdf->SetTitle("Lista Estudantes R.U.");
        	$pdf->SetSubject("Lista Estudantes R.U.");
    	}

        //cabeçalho do documento

        //imagem do cabeçalho
        $pdf->SetXY($startX + 1, $startY + 1);
        $pdf->Image($this->url_logo_ufscar, 15, 22, 45, 36);

        $pdf->SetXY($startX + 85, $startY + 1);
        $pdf->SetFont($font, 'B', 12);
        $pdf->Text($startX + 85, $startY + 5, 'UNIVERSIDADE FEDERAL DE SÃO CARLOS');
        $pdf->Text($startX + 85, $startY + 10, 'PRÓ REITORIA DE GRADUAÇÃO');
        $pdf->Text($startX + 85, $startY + 15, 'PRÓ REITORIA DE EXTENSÃO');
        $pdf->SetFont($font, 'B', 10);
        $pdf->Text($startX + 85, $startY + 20, 'NÚCLEO DE EXTENSÃO UFSCAR-ESCOLA');
        $pdf->SetFont($font, '', 9);
        $pdf->Text($startX + 85, $startY + 25, 'PROJETO DE EXTENSÃO CURSO PRÉ-VESTIBULAR DA UFSCAR');
        $pdf->SetFont($font, '', 10);
        $pdf->Text($startX + 85, $startY + 30, 'Via Washington Luiz, Km. 235 - Caixa Postal 676');
		$pdf->Text($startX + 85, $startY + 35, 'Fones: (016) 3351-8433 Telefax: (016) 3351.2081 ');
		$pdf->Text($startX + 85, $startY + 40, 'CEP 13565-905 - São Carlos - SP - Brasil');
		$pdf->Text($startX + 85, $startY + 45, 'e.mail: cursinho.ufscar@gmail.com');

		//corpo da mensagem

		$pdf->SetXY($startX + 1, $startY + 60);
		$pdf->SetFont($font, 'B', 14);
        $pdf->MultiCell(180, 8, $texto, 0, 'C', 0);
		$pdf->SetXY($startX + 1, $startY + 90);
        $pdf->MultiCell(180, 8, '', 0, 'C', 0);

        $pdf->SetFont($font, '', 10); 

        //inserir a tabela com os estudantes
        $header = array();
		$header[] = array('Nome alunos', 'RG');
		$width = array(140, 40);
		$data = array();

		foreach ($estudantes as $estudante)
		{
			$data[] = array($estudante['Candidato']['nome'], $estudante['Candidato']['rg']);
		}

		$pdf->AddTableCPV($header, $width, $data, 'L');

		$pdf->Output('lista_estudantes_'.$tipo.'_'.$ano.'.pdf','D');
    }

    function beforeFilter() {
            parent::beforeFilter(); 
            $this->set('moduloAtual', 'estudantes');

            // Unidades
            $array_unidade = $this->Candidato->Unidade->getSelectForm(true);
            $this->set('unidades', $array_unidade);
    }
	
	//gerar lista de opções 
	function gerar_mensalidade_grupo_index(){
		
		//obter os anos dos estudantes
		$anos_letivos = $this->Estudante->getAnosLetivos();
		
		$this->set('anos_letivos', $anos_letivos);
		
		if (!empty($this->data)){
			
			$this->redirect('/estudantes/gerar_mensalidade_grupo/'.$this->data['Estudante']['ano_letivo'].'/'.$this->data['Estudante']['mes'].'/'.$this->data['Estudante']['rematriculado']);
		}
		
	}
	
	function gerar_mensalidade_grupo($ano_letivo, $mes, $rematriculado){
		$this->set('content_title', 'Gerar mensalidades');
		$this->set('ano_letivo', $ano_letivo);
		$this->set('mes', $mes);
		
		$condicao = array('Estudante.ano_letivo' => $ano_letivo, 'Estudante.evasao' => 0);
        if($rematriculado == '1'){
            $condicao['Candidato.rematriculado'] = '1';
        }
		
		if ($this->Estudante->find('count', array('conditions' => $condicao, 'recursive' => '0')) > 0)
		{
			$this->paginate = array('order' => array('Candidato.ano' => 'desc',
									  				 'Candidato.numero_inscricao' => 'asc'),
									'recursive' => '0',
									'fields' => array('Candidato.numero_inscricao', 'Candidato.ano', 'Candidato.nome', 'Candidato.matriculado', 'Estudante.estudante_id'),
									'limit' => 1500);
			$estudantes = $this->paginate('Estudante', $condicao);
			
			//buscar estudantes com mensalidade
			$condicao_fat = array('Fatura.pago >= ' => 0, 'Fatura.data_vencimento >= ' => date('Y').'-'.$mes.'-'.'1',
					'Fatura.data_vencimento <= ' => date('Y').'-'.$mes.'-'.'28') ;
			$estudantes_fat = $this->Estudante->Fatura->find('all', array('conditions' => $condicao_fat, 'recursive' => '-1'));
			$estudantes_fatura = array();

			if($estudantes_fat)
				foreach($estudantes_fat as $est)
                    if(!empty($est['Fatura']['estudante_id']))
                        if( array_key_exists($est['Fatura']['estudante_id'], $estudantes_fatura)  )
                            $estudantes_fatura[$est['Fatura']['estudante_id']] ++;
                        else
                            $estudantes_fatura[$est['Fatura']['estudante_id']] = 1;


			$this->set('estudantes', $estudantes);
			$this->set('estudantes_fatura', $estudantes_fatura);
		} else {
			$this->Session->setFlash('Nenhum estudante encontrado. Tente novametne');
			$this->redirect('/estudantes/gerar_mensalidade_grupo_index');
		}
		
		if (!empty($this->data))
		{
			$erro = false;
			$this->data['Fatura']['mes'] = $mes;


			foreach ($this->data['mensalidade'] as $estudante_id => $value)
			{
                if($value == 1){
                    $this->Estudante->Fatura->create();
                    if(!$this->Estudante->Fatura->criar_mensalidade($estudante_id, $this->data['Fatura'] ) ){
                        $erro = true;
                    }
                }
			}

			if($erro)
				$this->Session->setFlash('Houve um erro na geração das mensalidades, veja na lista os estudantes que faltam gerar.');
			else
				$this->Session->setFlash('Mensalidades criadas');
			
			$this->redirect('/estudantes/gerar_mensalidade_grupo/'.$ano_letivo.'/'.$mes.'/'.$rematriculado);
		}
	}
	
	//pega a lista de estudantes atrasados com relação ao mês atual.
	function atrasados(){
		if (!empty($this->data))
		{
			//buscar por faturas não pagas
            $condicao = array('Fatura.pago' => 0, 'Fatura.ano_ref' => $this->data['Estudante']['ano'],
					'Fatura.mes_ref' => $this->data['Estudante']['mes'], 'Estudante.evasao' => 0) ;
			$estudantes = $this->Estudante->Fatura->find('all', array('conditions' => $condicao, 'recursive' => '0'));
			$estudantes_id = array();
			foreach($estudantes as $est){
				$estudantes_id[$est['Estudante']['estudante_id']] = $est['Estudante']['estudante_id'];
			}

            //buscar por faturas pagas, pois pode haver de ter estudantes com pelo menos uma fatura do mês solicitado, devido a duplicação de faturas
            $condicao_pagas = array('Fatura.pago >= ' => 1, 'Fatura.ano_ref' => $this->data['Estudante']['ano'],
                'Fatura.mes_ref' => $this->data['Estudante']['mes'], 'Estudante.evasao' => 0) ;
            $estudantes_pagos = $this->Estudante->Fatura->find('all', array('conditions' => $condicao_pagas, 'recursive' => '0'));
            foreach($estudantes_pagos as $est){
                unset($estudantes_id[$est['Estudante']['estudante_id']]);
            }

			$estudantes = $this->Estudante->find('all', array('conditions' => array('Estudante.estudante_id' => $estudantes_id), 'recursive' => '0'));
			
			$this->set('estudantes', $estudantes);
		}
	}
	
}
?>
