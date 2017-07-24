<?php 
/**
 * Classe correspondente ao Módulo Candidatos
 */
class QuestaoQuestionariosController extends AppController {
	//var $scaffold;
	var $layout = 'tricolor_layout';
	var $pageTitle = 'candidatos';
	var $helpers = array('questionario');
	var $uses = array('QuestaoQuestionario', 'Candidato', 'ProcessoSeletivo');
	var $paginasQuestionario = array('1' => array('1', '2', '4', '5', '6', '7', '8'),
            '2' => array('9', '10', '11', '12', '13', '14', '15', '16', '17', 
                                     '18', '79', '19', '20', '21', '22', '23', '24', '25', '26'),
            '3' => array('34', '35', '36', '37', '38', '80', '39', '81', '40'),
            '4' => array('41', '42', '43', '44', '45', '46', '47', '48', '49',
                                     '51', '52', '82',  '53', '83', '84', '54', '55', '85', '56', '57', '58'),
            '5' => array('59', '60', '61', '62', '63', '64', '65', '66'),
            '6' => array('67', '68', '69'),
            '7' => array('70', '71', '72', '73', '75', '76', '78',
                        '27', '28', '29', '30', '31', '32', '33'));
	// Guarda quantas páginas o questionário possui
	var $totalPaginas = 7;
	function index()
	{
	}
	// Calcula a pontuação geral do candidato
	function calcular_pontuacao($candidato_id, $pagina)
	{
		// Pontuação social total do candidato
		$pontuacao_social = 0;
		// Pontuação economica total do candidato
		$pontuacao_economica = 0;
		// Rendimento bruto do candidato
		$rendimento_bruto = 0;
		// Número de pessoas que vivem na casa do candidato (incluindo o próprio candidato)
		$numero_pessoas = 1;
		// Para cada página do questionário, calculamos a pontuação das questões
		foreach ($this->paginasQuestionario as $pagina)
		{
			// Para cada questão da página atual do questionário, calculamos a pontuação das questões
			foreach ($pagina as $questao_id)
			{
				// Obtemos a resposta da questão atual e do candidato especificado
				$resposta = $this->QuestaoQuestionario->RespostaQuestaoQuestionario->find(
					'first', array('conditions' => array('RespostaQuestaoQuestionario.questao_questionario_id' => $questao_id,
                                                                             'RespostaQuestaoQuestionario.candidato_id' => $candidato_id),
                                                        'fields' => array('pontuacao_social', 'pontuacao_economica'),
                                                        'recursive' => '0'));
				// Convertermos em um objeto do tipo SimpleXml a pontuação social e econômica da questão atual
				$xml_pontuacao_social = simplexml_load_string($resposta['RespostaQuestaoQuestionario']['pontuacao_social']);
				$xml_pontuacao_economica = simplexml_load_string($resposta['RespostaQuestaoQuestionario']['pontuacao_economica']);
				// Verificamos existe pontuação econômica para a questão atual
				if ($xml_pontuacao_economica)
				{
					// Se estamos na questão 67 (rendimento bruto do candidato e das pessoas que moram com ele)
					if ($questao_id == '67')
					{							
						// Soma à variável $rendimento_bruto o rendimento bruto do candidato e das pessoas que moram com ele
						$rendimento_bruto += (float)$xml_pontuacao_economica->valor;
						// Guarda o número de pessoas que moram com o  candidato
						$numero_pessoas += $xml_pontuacao_economica->campos_pontuados;
					}
					// Se estamos na questão 68 (rendimentos adicionais)
					else if ($questao_id == '68')
					{							
						// Soma à variável $rendimento_bruto os rendimentos adicionais
						$rendimento_bruto += (float)$xml_pontuacao_economica->valor;
					}
					else
					{							
						// Soma a pontuação econômica do candidato
						$pontuacao_economica += (float)$xml_pontuacao_economica->valor;
					}
				}
				// Verificamos se existe pontuação social para a questão atual
				if ($xml_pontuacao_social)
				{
					// Se estamos na questão 2 (idade do candidato)
					if ($questao_id == '2')
					{
						if ($xml_pontuacao_social->valor == 15)
							$pontuacao_social += 10;
						else if ($xml_pontuacao_social->valor == 16)
							$pontuacao_social += 10;
						else if ($xml_pontuacao_social->valor == 17)
							$pontuacao_social += 8;
						else if ($xml_pontuacao_social->valor >= 18 && $xml_pontuacao_social->valor <= 21)
							$pontuacao_social += 5;
						else if ($xml_pontuacao_social->valor >= 22 && $xml_pontuacao_social->valor <= 29)
							$pontuacao_social += 3;
                                                else if ($xml_pontuacao_social->valor >= 30 && $xml_pontuacao_social->valor <= 39)
							$pontuacao_social += 1;
                                                else if ($xml_pontuacao_social->valor >= 40)
							$pontuacao_social += 0.1;
					}
                                        else
					{
						// Soma a pontuação social do candidato
						$pontuacao_social += (float)$xml_pontuacao_social->valor;
					}
				}
			}
		}
		// Carregamos os dados do candidato que iremos atualizar
		$candidato = $this->Candidato->find('first', array('conditions' => array('Candidato.candidato_id' => $candidato_id),
														   'fields' => array('pontuacao_social', 'pontuacao_economica', 'ano'),
														   'recursive' => '0'));
		// Obtemos o valor do salário minímo guardado nas configurações do processo seletivo ao qual o
		//   candidato pertence
		$processo_seletivo = $this->ProcessoSeletivo->find('first', array(
			'conditions' => array('ProcessoSeletivo.ano' => $candidato['Candidato']['ano'])));
		$salario_minimo = $processo_seletivo['ConfiguracaoProcessoSeletivo']['valor_salario_minimo'];
		//$salario_minimo = 415;
		// Se não conseguiu obter o valor do salario minimo das configurações, atribue um valor
		//   grande para o salario minimo para que a pontuação economica fique muito pequeno e perceba-se
		//   que houve um erro
		if ($salario_minimo == '')
			$salario_minimo = 10000000;
		// Calcula a pontuação econômica do candidato com base no rendimento bruto, no número de pessoas
		//   que vivem na casa do candidato e no valor do salário mínimo atual
		$pontuacao_economica += ($rendimento_bruto / $numero_pessoas) / $salario_minimo;
		// Definimos o id do candidato que queremos alterar
		$this->Candidato->id = $candidato_id;
		// Guardamos a pontuação social do candidato
		$candidato['Candidato']['pontuacao_social'] = $pontuacao_social;
		// Guardamos a pontuação econômica do candidato
		$candidato['Candidato']['pontuacao_economica'] = $pontuacao_economica;
		// Atualizamos o candidato com a nova pontuação calculada
		$this->Candidato->save($candidato);
	}
	// Salva as respostas do candidato
	function salvar_respostas($numero_inscricao, $ano, $pagina, $novaPagina = null)
	{		
		// Verifica se a página fornecida está dentro do limite de páginas do questionário
		if ($pagina <= 0 || $pagina > $this->totalPaginas)
			$this->redirect('/candidatos/preencher_questionario');
		// Obtem o id do candidato informado
		$candidato_id = $this->Candidato->obterId($numero_inscricao, $ano);
		$turma_candidato = '';
		$idade_candidato = '';
		$trabalho_candidato = '';
		$sexo_candidato = '';
		$cor_candidato = '';
		$estado_civil_candidato = '';
		$numero_filhos_candidato = '';
		$etnia_candidato = array('','');
		// Se o candidato informado existe, então grava as respostas
		if ($candidato_id)
		{	
			// Para cada questão que a página atual contém, compõe a resposta em xml, calcula a pontuação
			//    e salva no banco de dados
			foreach ($this->paginasQuestionario[$pagina] as $questao_id)
			{
				$resposta = array();
				$etnia_count = 0;
				// Como o formulário enviado possui a resposta de várias questões,
				//   coloca-se no vetor $resposta apenas as respostas da questão que estamos salvando agora
				foreach ($this->params['form'] as $campo => $valor)
				{
					// Procura pelo campo cujo id é igual ao id da questão atual, e guarda o seu valor
					if (preg_match("/^qid" . $questao_id . "_.*/", $campo, $matches))
					{
						$resposta[$campo] = $valor;
						// Armazenamos os valores de algumas questões para guardar na ficha de inscrição do candidato
						if ($valor != '')
						{
							switch ($questao_id)
							{
								case '3' : $turma_candidato = $valor;
									break;
								case '27' :
									switch ($valor)
									{
										case '1' : $sexo_candidato = 'feminino';
											break;
										case '2' : $sexo_candidato = 'masculino';
											break;
									}
									break;
								case '28' :
									switch ($valor)
									{
										case '1' : $cor_candidato = 'preto';
											break;
										case '2' : $cor_candidato = 'pardo';
											break;
										case '3' : $cor_candidato = 'amarelo';
											break;
										case '4' : $cor_candidato = 'branco';
											break;
										case '5' : $cor_candidato = 'indigena';
											break;
									}
									break;
								case '29' :									
									switch ($campo)
									{
										case 'qid29_chkOpcao_1' : $etnia_candidato[$etnia_count] = 'afrodescendente';
											break;
										case 'qid29_chkOpcao_2' : $etnia_candidato[$etnia_count] = 'branco';
											break;	
										case 'qid29_chkOpcao_3' : $etnia_candidato[$etnia_count] = 'indígena';
											break;
										case 'qid29_chkOpcao_4' : $etnia_candidato[$etnia_count] = 'oriental';
											break;
									}
									$etnia_count++;
									break;
							}
						}
					}
				}
				// Compõe a resposta da questão atual como um xml
				$respostaXml = $this->QuestaoQuestionario->RespostaQuestaoQuestionario->compor_resposta_xml($resposta);
				/*if ($this->QuestaoQuestionario->find('count', 
					array('conditions' => array('questao_questionario_id' => $questao_id,
						  						'pontuacao NOT' => ''),
						  'recursive' => '0')) > 0);*/
				{
					// Obtem o campo pontuação da questão atual para fazer o cálculo da pontuação
					$questaoQuestionario = $this->QuestaoQuestionario->find('first', 
						array('conditions' => array('questao_questionario_id' => $questao_id),
							  'fields' => array('pontuacao'),
							  'recursive' => '0'));
					// Calcula a pontuação da questão atual com base na resposta fornecida
					$pontuacao = $this->QuestaoQuestionario->RespostaQuestaoQuestionario->calcular_pontuacao_questao(
						$resposta, $questaoQuestionario['QuestaoQuestionario']['pontuacao']);
				}
				// Obtém o id da resposta da questão atual e do candidato fornecido
				$resposta_id = $this->QuestaoQuestionario->RespostaQuestaoQuestionario->obterId($questao_id, $candidato_id);
				// Se o id retornado é nulo, então significa que não havia nenhuma resposta salva anteriormente
				if (!$resposta_id)
				{
					$this->QuestaoQuestionario->RespostaQuestaoQuestionario->create();
					// Colocando '' no campo $resposta_id indica que quando o método save for chamado será 
					//   inserida uma nova tupla na tabela
					$resposta_id = '';
				}
				// Guardamos no vetor $data os campos da tabela com os valores que desejamos
				$data = array('RespostaQuestaoQuestionario' => array(
								  'resposta_questao_questionario_id' => $resposta_id,
								  'questao_questionario_id' => $questao_id,
								  'candidato_id' => $candidato_id,
								  'resposta' => $respostaXml,
								  'pontuacao_social' => $pontuacao['pontuacao_social'],
								  'pontuacao_economica' => $pontuacao['pontuacao_economica']));
				// Se o campo resposta_id for diferente de '', então a tabela será atualizada com o novo valor,
				//   caso contrário, será inserida uma nova tupla na tabela
				$this->QuestaoQuestionario->RespostaQuestaoQuestionario->save($data);
			}
			// Carregamos os dados do candidato que iremos atualizar
			$candidato = $this->Candidato->find('first', array('conditions' => array('Candidato.candidato_id' => $candidato_id),
													   'fields' => array('turma', 'sexo', 'cor'),
													   'recursive' => '0'));
			$this->Candidato->id = $candidato_id;
			if ($pagina == 1) {
				$candidato['Candidato']['turma'] = $turma_candidato;
			}
			else if($pagina == 2) {
				$candidato['Candidato']['sexo'] = $sexo_candidato;
				$candidato['Candidato']['cor'] = $cor_candidato;
				$candidato['Candidato']['etnia1'] = $etnia_candidato[0];
				$candidato['Candidato']['etnia2'] = $etnia_candidato[1];
				//print_r($etnia_candidato);
			}
			$candidato['Candidato']['questionario_vazio'] = '0';
			$this->Candidato->save($candidato);
			// faz a soma das pontuações das questões da página atual e guarda no candidato
			$this->calcular_pontuacao($candidato_id, $pagina);
			// Se clicou no botão Próxima, então a nova página é a próxima
			if (!empty($this->params['form']['Proximo']))
			{
				$novaPagina = $pagina + 1;
			}
			// Se clicou no botão Anterior, então a nova página é a anterior
			else if (!empty($this->params['form']['Anterior']))
			{
				$novaPagina = $pagina - 1;
			}
			// Se clicou no botão Salvar, então redireciona para página de escolher qual candidato
			//   irá preencher o questionário
			else if (!empty($this->params['form']['Cancelar']))
			{
				$this->Session->setFlash('O questionário foi salvo corretamente.');
				$this->redirect('/candidatos/preencher_questionario');
			}
			// redireciona para a nova página
			$this->redirect('/questao_questionarios/preencher/' . $numero_inscricao . '/' . $ano . '/' . $novaPagina);
		}
		else
		{
			// Se o candidato informado não existe, redireciona para página de escolher qual 
			//   candidato irá preencher o questionário
			$this->redirect('/candidatos/preencher_questionario');
		}
	}
	function preencher($numero_inscricao, $ano, $pagina)
	{		
		$this->set('content_title', 'Preenchimento de questionário');
		// Verifica se a página fornecida está dentro do limite de páginas do questionário
		if ($pagina <= 0 || $pagina > $this->totalPaginas)
			$pagina = 1;
		// Obtem o id do candidato informado
		$candidato_id = $this->Candidato->obterId($numero_inscricao, $ano);
		// Se o candidato existe, então tenta carregar as respostas dele
		if ($candidato_id)
		{
			// Para cada questão que a página atual contém, obtém a resposta dela
			foreach ($this->paginasQuestionario[$pagina] as $questao_id)
			{
				// Obtem o código html da questão atual
				$questaoQuestionario[$questao_id]['Questao'] = $this->QuestaoQuestionario->obterQuestao($questao_id);
				// Obtem a resposta da questão atual
				$questaoQuestionario[$questao_id]['Resposta'] = 
					$this->QuestaoQuestionario->RespostaQuestaoQuestionario->obterResposta($questao_id, $candidato_id);
				// Convertemos a string da resposta em um objeto do tipo SimpleXml
				$questaoQuestionario[$questao_id]['Resposta'] = str_replace('&', '&amp;', $questaoQuestionario[$questao_id]['Resposta']);
				$questaoQuestionario[$questao_id]['Resposta'] = simplexml_load_string($questaoQuestionario[$questao_id]['Resposta']);
			}
			// Guardamos o número de inscrição fornecido
			$this->set('numero_inscricao', $numero_inscricao);
			// Guardamos o ano fornecido
			$this->set('ano', $ano);
			// Guardamos o nome do candidato
			$this->set('nome', $this->Candidato->obterNome($numero_inscricao, $ano));
			// Guardamos as questões obtidas
			$this->set('questoes', $questaoQuestionario);
			// Mostra a página requisitada
			$this->render('questionario_pagina_' . $pagina);
		}
		else
		{
			// Se o candidato informado não existe, redireciona para página de escolher qual 
			//   candidato irá preencher o questionário
			$this->redirect('/candidatos/preencher_questionario');
		}
	}
	function entity_converter()
	{
		if (empty($this->data))
		{
			$this->set('toHtml', '');
		}
		else
		{
			//$this->data['QuestaoQuestionario']['xmlString'] = htmlspecialchars(($this->data['QuestaoQuestionario']['xmlString']));
			//$this->set('toHtml', htmlspecialchars(($this->data['QuestaoQuestionario']['xmlString'])));
			$this->set('specialChars', $this->data['QuestaoQuestionario']['xmlString']);
		}
	}
	function beforeFilter() {
		parent::beforeFilter(); 
		$this->Auth->allow(array('*'));
		$this->set('moduloAtual', 'candidatos');
	}
        function pdf_questionario($numero_inscricao, $ano)
        {
		// Obtem o id do candidato informado
		$candidato_id = $this->Candidato->obterId($numero_inscricao, $ano);
		// Se o candidato existe, então tenta carregar as respostas dele
		if ($candidato_id)
		{
			// Para cada questão que a página atual contém, obtém a resposta dela
			foreach ($this->paginasQuestionario[1] as $questao_id)
			{
				// Obtem o código html da questão atual
				$questaoQuestionario[$questao_id]['Questao'] = $this->QuestaoQuestionario->obterQuestao($questao_id);
				// Obtem a resposta da questão atual
				$questaoQuestionario[$questao_id]['Resposta'] = 
					$this->QuestaoQuestionario->RespostaQuestaoQuestionario->obterResposta($questao_id, $candidato_id);
				// Convertemos a string da resposta em um objeto do tipo SimpleXml
				$questaoQuestionario[$questao_id]['Resposta'] = str_replace('&', '&amp;', $questaoQuestionario[$questao_id]['Resposta']);
				$questaoQuestionario[$questao_id]['Resposta'] = simplexml_load_string($questaoQuestionario[$questao_id]['Resposta']);
			}
                        foreach ($this->paginasQuestionario[2] as $questao_id)
			{
				// Obtem o código html da questão atual
				$questaoQuestionario[$questao_id]['Questao'] = $this->QuestaoQuestionario->obterQuestao($questao_id);
				// Obtem a resposta da questão atual
				$questaoQuestionario[$questao_id]['Resposta'] = 
					$this->QuestaoQuestionario->RespostaQuestaoQuestionario->obterResposta($questao_id, $candidato_id);
				// Convertemos a string da resposta em um objeto do tipo SimpleXml
				$questaoQuestionario[$questao_id]['Resposta'] = str_replace('&', '&amp;', $questaoQuestionario[$questao_id]['Resposta']);
				$questaoQuestionario[$questao_id]['Resposta'] = simplexml_load_string($questaoQuestionario[$questao_id]['Resposta']);
			}
                        foreach ($this->paginasQuestionario[3] as $questao_id)
			{
				// Obtem o código html da questão atual
				$questaoQuestionario[$questao_id]['Questao'] = $this->QuestaoQuestionario->obterQuestao($questao_id);
				// Obtem a resposta da questão atual
				$questaoQuestionario[$questao_id]['Resposta'] = 
					$this->QuestaoQuestionario->RespostaQuestaoQuestionario->obterResposta($questao_id, $candidato_id);
				// Convertemos a string da resposta em um objeto do tipo SimpleXml
				$questaoQuestionario[$questao_id]['Resposta'] = str_replace('&', '&amp;', $questaoQuestionario[$questao_id]['Resposta']);
				$questaoQuestionario[$questao_id]['Resposta'] = simplexml_load_string($questaoQuestionario[$questao_id]['Resposta']);
			}
                        foreach ($this->paginasQuestionario[4] as $questao_id)
			{
				// Obtem o código html da questão atual
				$questaoQuestionario[$questao_id]['Questao'] = $this->QuestaoQuestionario->obterQuestao($questao_id);
				// Obtem a resposta da questão atual
				$questaoQuestionario[$questao_id]['Resposta'] = 
					$this->QuestaoQuestionario->RespostaQuestaoQuestionario->obterResposta($questao_id, $candidato_id);
				// Convertemos a string da resposta em um objeto do tipo SimpleXml
				$questaoQuestionario[$questao_id]['Resposta'] = str_replace('&', '&amp;', $questaoQuestionario[$questao_id]['Resposta']);
				$questaoQuestionario[$questao_id]['Resposta'] = simplexml_load_string($questaoQuestionario[$questao_id]['Resposta']);
			}
                        foreach ($this->paginasQuestionario[5] as $questao_id)
			{
				// Obtem o código html da questão atual
				$questaoQuestionario[$questao_id]['Questao'] = $this->QuestaoQuestionario->obterQuestao($questao_id);
				// Obtem a resposta da questão atual
				$questaoQuestionario[$questao_id]['Resposta'] = 
					$this->QuestaoQuestionario->RespostaQuestaoQuestionario->obterResposta($questao_id, $candidato_id);
				// Convertemos a string da resposta em um objeto do tipo SimpleXml
				$questaoQuestionario[$questao_id]['Resposta'] = str_replace('&', '&amp;', $questaoQuestionario[$questao_id]['Resposta']);
				$questaoQuestionario[$questao_id]['Resposta'] = simplexml_load_string($questaoQuestionario[$questao_id]['Resposta']);
			}
			foreach ($this->paginasQuestionario[6] as $questao_id)
			{
                                if($questao_id != 67){
                                    // Obtem o código html da questão atual
                                    $questaoQuestionario[$questao_id]['Questao'] = $this->QuestaoQuestionario->obterQuestao($questao_id);
                                    // Obtem a resposta da questão atual
                                    $questaoQuestionario[$questao_id]['Resposta'] = 
                                            $this->QuestaoQuestionario->RespostaQuestaoQuestionario->obterResposta($questao_id, $candidato_id);
                                    // Convertemos a string da resposta em um objeto do tipo SimpleXml
                                    $questaoQuestionario[$questao_id]['Resposta'] = str_replace('&', '&amp;', $questaoQuestionario[$questao_id]['Resposta']);
                                    $questaoQuestionario[$questao_id]['Resposta'] = simplexml_load_string($questaoQuestionario[$questao_id]['Resposta']);
                                }
			}
                        foreach ($this->paginasQuestionario[7] as $questao_id)
			{
				// Obtem o código html da questão atual
				$questaoQuestionario[$questao_id]['Questao'] = $this->QuestaoQuestionario->obterQuestao($questao_id);
				// Obtem a resposta da questão atual
				$questaoQuestionario[$questao_id]['Resposta'] = 
					$this->QuestaoQuestionario->RespostaQuestaoQuestionario->obterResposta($questao_id, $candidato_id);
				// Convertemos a string da resposta em um objeto do tipo SimpleXml
				$questaoQuestionario[$questao_id]['Resposta'] = str_replace('&', '&amp;', $questaoQuestionario[$questao_id]['Resposta']);
				$questaoQuestionario[$questao_id]['Resposta'] = simplexml_load_string($questaoQuestionario[$questao_id]['Resposta']);
			}
                        App::import('Vendor', 'mpdf/mpdf');
                        //$mpdf=new mPDF('en-x','A4','','',10,10,30,35,5,10);
                        $mpdf=new mPDF();
                        $mpdf->SetTopMargin(1);
                        $mpdf->SetLeftMargin(1);
                        $mpdf->SetRightMargin(1);
                        $mpdf->SetAutoPageBreak(true, 35);
                        $mpdf->mirrorMargins = 1;  // Use different Odd/Even headers and footers and mirror margins
                        $mpdf->defaultheaderline = 1;
                        $mpdf->defaultfooterfontsize = 1;
                        //css
                        $styles = '<style>
                            body {
                                font-family: helvica;
                                font-size: 12px;
                            }
                            p {
                                margin: 0px;
                                padding: 0px;
                            }
                            </style>';
                        $mpdf->WriteHTML($styles);
                        //cabeçalho
                        $html = '<h3>Questionário sócio-econômico</h3>
                            <h3>Candidato:'.$this->Candidato->obterNome($numero_inscricao, $ano).'</h3>
                            <h3>Número de inscrição: '.$numero_inscricao.' </h3>
                            <h3>Ano: '.$ano.' </h3>
                            <hr/>
                            ';
                        $mpdf->WriteHTML($html);
                        foreach ($questaoQuestionario as $questao)
                        {				
                                //$questionario->preencher_questao($questao['Questao'], $questao['Resposta']);
                                //echo html_entity_decode($questao['Questao']);
                                $questaoHtml = $this->QuestaoQuestionario->montar_questao($questao['Questao']);
                                $this->QuestaoQuestionario->preencher_questao($questaoHtml, $questao['Resposta']);
                                $mpdf->WriteHTML($questaoHtml);
                        }
                        $html = '<p>
                            _
                            </p>
                            ';
                        $mpdf->WriteHTML($html);
                        $mpdf->Output('questionario_'.$numero_inscricao.'_'.$ano.'.pdf', 'I');
		}
		else
		{
			// Se o candidato informado não existe, redireciona para página de escolher qual 
			//   candidato irá preencher o questionário
			$this->redirect('/candidatos/preencher_questionario');
		}
        }
}
?>