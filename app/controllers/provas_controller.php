<?php 
/**
 * Classe correspondente ao Módulo Candidatos
 */
class ProvasController extends AppController {

	//var $scaffold;

	var $layout = 'tricolor_layout';
	var $pageTitle = 'candidatos';
	var $paginate;
    var $uses = array('Prova', 'Candidato');

	//var $helpers = array('Chart');
	var $components = array('Excel', 'Fpdf', 'Upload');

	function index()
	{

	}

	function inserir()
	{
		$this->set('content_title', 'Cadastrar uma nova Prova');

		if (!empty($this->data))
		{
			if(!$this->data['Prova']['ano'] == '')
			{
				$this->Prova->create();
				$this->Prova->set($this->data);			

				// Verifica se nÃ£o existe um candidato com o número de inscrição e ano informados
				if (!$this->Prova->existe())
				{
					if ($this->Prova->save())
						$this->Session->setFlash('Prova cadastrada com sucesso.');
						//$this->redirect('/provas/inserir_questoes/'.$this->data['ano']);
						$this->redirect('/provas/listar_todas');
				}
				else
				{
					$this->Session->setFlash('ATENÇÃO! Já existe uma prova cadastrada para este ano.' .
							'Pro favor, altere a prova existente.');
				}
			}
			else
			{
				$this->Session->setFlash('Insira um ano válido!');
			}
		}
	}

	function inserir_questoes($ano)
	{
		$this->set('content_title', 'Inserir Questão');

		$this->Prova->QuestaoProva->create();

		$prova_id = $this->Prova->obterId($ano);

		$condicao = array('QuestaoProva.prova_id' => $prova_id);

		$num_questoes = $this->Prova->QuestaoProva->find('count', array('conditions' => $condicao));

		if ($num_questoes > 0)
		{
			//possui questoes
			$this->Prova->QuestaoProva->order = 'QuestaoProva.numero_questao';
			$questoes = $this->Prova->QuestaoProva->find('all', array('conditions' => $condicao));

			$this->set('questoes', $questoes);
		}
		else
		{
			//nao possue questoes
		}
		$this->set('num_questoes', $num_questoes);
		$this->set('prova_ano', $ano);
		$this->set('prova_id', $prova_id);
	}

	function listar_todas()
	{
		$this->set('content_title','Listar as provas');

		$this->Prova->recursive = '1';

		$this->paginate = array('limit' => 20, 'order' => array('Prova.ano' => 'asc'));

		$provas = $this->paginate('Prova');
		$this->set('provas', $provas);

	}

	function upload($prova_id, $ano)
	{
		if (!empty($this->data))
		{
			$path = 'arquivos/provas';
			$this->Upload->allowedExtensions = array('txt', 'doc', 'docx', 'xls', 'xlsx', 'pdf');
			$this->Upload->setPath($path);
			$novo_arquivo = $this->Upload->copyUploadedFile($this->data['Prova']['arquivo'], '');

			//print_r($novo_arquivo);
			print_r($this->data['Prova']['arquivo']);

			if (!$novo_arquivo)
			{
				$this->Session->setFlash('Não foi possível fazer o upload do arquivo da prova');
			}
			else
			{
				$this->Prova->prova_id = $prova_id;
				$this->data['Prova']['arquivo'] = $this->data['Prova']['arquivo']['name'];

				if ($this->Prova->save($this->data))
				{
					$this->Sessoin->setFlash('Upload do arquivo realizado com sucesso');
					$this->redirect('/provas/visualizar/'.$ano);
				}
				else
				{
					$this->Session->setFlash('Não foi possível fazer o upload do arquivo');
					$this->redirect('/provas/visualizar/'.$ano);
				}
			}
		}

		$this->redirect('/provas/visualizar/'.$ano);
	}

	function visualizar($ano)
	{
		$this->set('content_title', 'Visualização da prova de ' . $ano); 

		$this->Prova->QuestaoProva->create();

		$prova_id = $this->Prova->obterId($ano);

		$num_questoes = $this->Prova->QuestaoProva->numeroQuestoesProva($prova_id);

		$arquivo = $this->Prova->obterArquivo($prova_id);

		if ($num_questoes > 0)
		{
			//possui questoes
			$this->set('questoes', $this->Prova->QuestaoProva->getAllQuestoesProva($prova_id));
		}
		$this->set('num_questoes', $num_questoes);
		$this->set('prova_ano', $ano);
		$this->set('prova_id', $prova_id);
		$this->set('prova_arquivo', $arquivo);
	}

	/*
	 * CORRIGIR
	 */
	function alterar($ano)
	{
		$this->set('content_title', 'Alterar Prova'); 

		$this->set('content_title','Editar Ano da Prova');

		if (!empty($this->data))
		{
			$this->render('listar_todas');
		}
		else
		{
			$this->Prova->QuestaoProva->create();

			$prova_id = $this->Prova->obterId($ano);

			$num_questoes = $this->Prova->QuestaoProva->numeroQuestoesProva($prova_id);

			if ($num_questoes > 0)
			{
				//possui questoes
				$questoes = $this->Prova->QuestaoProva->find('all', array('conditions' => $condicao));

				$this->set('questoes', $questoes);
			}
			else
			{
				//nao possue questoes
			}
			$this->set('num_questoes', $num_questoes);
			$this->set('prova_ano', $ano);
			$this->set('prova_id', $prova_id);
		}

	}

	function preencher()
	{
		$this->set('content_title', 'Inserir Resposta de Prova do Candidato'); 

		if (!empty($this->data))
		{
			$this->set('numero_inscricao', $this->data['Prova']['candidato_numero_inscricao']);
			$this->set('ano', $this->data['Prova']['candidato_ano']);

			//verifica se o candidato existe e se pode fazer a prova
			App::import('Model', 'Candidato');
			$this->Candidato = new Candidato;
			App::import('Model', 'RespostaQuestaoProva'); 
 	    	$this->RespostaQuestaoProva = new RespostaQuestaoProva; 

    	   	$candidato_id = $this->Candidato->obterId($this->data['Prova']['candidato_numero_inscricao'], $this->data['Prova']['candidato_ano']); 

			if($this->Candidato->existe($this->data['Prova']['candidato_numero_inscricao'], $this->data['Prova']['candidato_ano']))
			{
				//verifica se passou na fase elinatória
				if($this->Candidato->getFaseEliminatoriaStatus($this->data['Prova']['candidato_numero_inscricao'], $this->data['Prova']['candidato_ano']))
				{
					//passou na fase eliminatoria, pode fazer o preenchimento

					//agora verifica se existe a prova do ano selecionado
					if($this->Prova->existe($this->data['Prova']['candidato_ano']))
					{
						//passa para o formulario de preenchimento de prova
			            // verifica se ele ja preencheu a prova antes. 
			            if($this->Candidato->fezProva($candidato_id)) 
			            {
			            	//fez prova, agora deve verificar se foi prova especial ou convecional
			            	if($this->Candidato->fezProvaEspecial($candidato_id))
			            	{
			            		//prova especial 
			              		$this->redirect('/candidatos/alterar_nota_prova_especial/'.$this->data['Prova']['candidato_numero_inscricao'].'/'.$this->data['Prova']['candidato_ano']);
			            	}
			            	else
			            	{
			            		//prova convencional
			            		$this->redirect('/resposta_questao_provas/alterar/'.$this->data['Prova']['candidato_numero_inscricao'].'/'.$this->data['Prova']['candidato_ano']);
			            	}
			            } 
			            else 
			            { 
				            $this->redirect('/resposta_questao_provas/inserir/'.$this->data['Prova']['candidato_numero_inscricao'].'/'.$this->data['Prova']['candidato_ano']);
			            }
					}
					else
					{
						//não tem prova cadastrada para este processo seletivo,
						$this->Session->setFlash('Prova do ano de '. $this->data['Prova']['candidato_ano'] .' não esta cadastrada, favor cadastra-la!');
						$this->redirect('/provas/listar_todas');
					}

				}
				else
				{
					//não passou, perguntar se deseja aprovar o candidato na fase eliminatória.
					$this->render('confirmar_preenchimento');
				}
			}
			else
			{
				//candidato não existe, carregar a página novamente
				$this->Session->setFlash('Candidato não cadastrado! Verifique se digitou corretamente.');
				$this->render('preencher_questoes_prova');
			}
		}
		else
		{
			//formulario ainda não preenchido    
			$this->render('preencher_questoes_prova');
		}	
	}

    function preencher_lista()
    {
        if (!empty($this->data))
        {
            if(!empty($this->data['Prova']['ano_fase']))
            $this->redirect('/provas/listar_candidatos/'.$this->data['Prova']['ano_fase'].'/'.$this->data['Prova']['turma']);
        }
        else
        {
            $this->Session->setFlash('Preencha o ano do processo seletivo');
            $this->redirect('/provas/preencher');
        }
        $this->render('branco');
    }

	function preencher_direto($numero_inscricao, $ano)
	{
		$this->set('content_title', 'Inserir Respostas de Prova do Candidato');

		$this->set('numero_inscricao', $numero_inscricao);
		$this->set('ano', $ano);

		App::import('Model', 'Candidato');
		$this->Candidato = new Candidato;
		App::import('Model', 'RespostaQuestaoProva');
		$this->RespostaQuestaoProva = new RespostaQuestaoProva;

		$candidato_id = $this->Candidato->obterId($numero_inscricao, $ano);

		//verifica se passou na fase elinatória
		if($this->Candidato->getFaseEliminatoriaStatus($numero_inscricao, $ano))
		{
			//passou na fase eliminatoria, pode fazer o preenchimento
			//passa para o formulario de preenchimento de prova

			// verifica se ele ja preencheu a prova antes.
			if($this->Candidato->fezProva($candidato_id))
			{
				$this->redirect('/resposta_questao_provas/alterar/'.$numero_inscricao.'/'.$ano);
			}
			else
			{
				$this->redirect('/resposta_questao_provas/inserir/'.$numero_inscricao.'/'.$ano);
			}
		}
		else
		{
			//não passou, perguntar se deseja aprovar o candidato na fase eliminatória.
			$this->set('content_title', 'Confirmar Preenchimento das Respostas');
			$this->render('confirmar_preenchimento');	
		}
	}

	function relatorio()
	{
		$this->set('content_title', 'Relatórios Estatísticos sobre a Prova');
	}

	/*
	 * Relatório estatisico de acordo com a figura 1
	 */
	function relatorio_estatistico_1()
	{
		$this->set('content_title', 'Relatório Estatístico 1');

		if(empty($this->data))
		{
			//pedir o ano da prova
			$this->set('tipo_relatorio', 'relatorio_estatistico_1');
			$this->render('escolha_ano_prova');
		}
		else
		{

			//verifica se a prova esta cadastrada.
			if($this->Prova->existe($this->data['Prova']['ano']))
			{
				//pega as tabelas para o relatorio
				$tabelas = $this->get_tabelas_rel_estat_1($this->data['Prova']['ano']);

				//passa os dados para a view
				$this->set('prova_ano', $this->data['Prova']['ano']);
				$this->set('num_candidatos', $tabelas[3]);
				$this->set('num_questoes', $tabelas[2]);
				$this->set('tabela_questao_estatistica', $tabelas[0]);
				$this->set('count_alt_errada', $tabelas[1]);
			}
			else
			{
				//prova não existe
				$this->Session->setFlash('Prova não existe! Digite um ano válido');
				$this->set('tipo_relatorio', 'relatorio_estatistico_1');
				$this->render('escolha_ano_prova');
			}			
		}
	}

	function get_tabelas_rel_estat_1($prova_ano)
	{
		{
			//gerar o relatorio
			App::import('Model', 'QuestaoProva');
			$this->QuestaoProva = new QuestaoProva();
			App::import('Model', 'Candidato');
			$this->Candidato = new Candidato();
			App::import('Model', 'RespostaQuestaoProva');
			$this->RespostaQuestaoProva = new RespostaQuestaoProva();

			//selecionar todos os candidatos que realizaram a prova
			$candidatos = $this->Candidato->getAllCandidatosPorProva($prova_ano);
			$num_candidatos = 0;
			foreach($candidatos as $candidato)
			{
				$num_candidatos++;
			}

			//selecionar todas as questões da prova
			$questoes_prova = $this->QuestaoProva->getAllQuestoesProva($this->Prova->obterId($prova_ano));
			$num_questoes = 0;

			//preparando a tabela dos dados do relatório
			//preparando vetor para verificar a alternativa mais assinalada em cada questao
			$tabela_questao_estatistica = array();
			$count_alt_errada = array();
			foreach($questoes_prova as $questao)
			{
				$num_questoes++;
				//tabela
				$tabela_questao_estatistica[$questao['QuestaoProva']['numero_questao']]['alteranativa_correta'] = $questao['QuestaoProva']['alternativa_correta'];
				$tabela_questao_estatistica[$questao['QuestaoProva']['numero_questao']]['habilidade_avaliada'] = $questao['HabilidadeAvaliada']['habilidade'];
				$tabela_questao_estatistica[$questao['QuestaoProva']['numero_questao']]['erro_1_ano'] = 0;
				$tabela_questao_estatistica[$questao['QuestaoProva']['numero_questao']]['erro_2_ano'] = 0;
				$tabela_questao_estatistica[$questao['QuestaoProva']['numero_questao']]['erro_sem_turma'] = 0;
				$tabela_questao_estatistica[$questao['QuestaoProva']['numero_questao']]['acerto_1_ano'] = 0;
				$tabela_questao_estatistica[$questao['QuestaoProva']['numero_questao']]['acerto_2_ano'] = 0;
				$tabela_questao_estatistica[$questao['QuestaoProva']['numero_questao']]['acerto_sem_turma'] = 0;
				$tabela_questao_estatistica[$questao['QuestaoProva']['numero_questao']]['mais_errada_1_ano'] = '';
				$tabela_questao_estatistica[$questao['QuestaoProva']['numero_questao']]['mais_errada_2_ano'] = '';
				$tabela_questao_estatistica[$questao['QuestaoProva']['numero_questao']]['mais_errada_sem_turma'] = '';
				$tabela_questao_estatistica[$questao['QuestaoProva']['numero_questao']]['anulada'] = false;

				//vetor de contadores de alternativas marcadas
				$count_alt_errada['turma_1_ano'][$questao['QuestaoProva']['numero_questao']]['A'] = 0;
				$count_alt_errada['turma_2_ano'][$questao['QuestaoProva']['numero_questao']]['A'] = 0;
				$count_alt_errada['sem_turma'][$questao['QuestaoProva']['numero_questao']]['A'] = 0;
				$count_alt_errada['turma_1_ano'][$questao['QuestaoProva']['numero_questao']]['B'] = 0;
				$count_alt_errada['turma_2_ano'][$questao['QuestaoProva']['numero_questao']]['B'] = 0;
				$count_alt_errada['sem_turma'][$questao['QuestaoProva']['numero_questao']]['B'] = 0;
				$count_alt_errada['turma_1_ano'][$questao['QuestaoProva']['numero_questao']]['C'] = 0;
				$count_alt_errada['turma_2_ano'][$questao['QuestaoProva']['numero_questao']]['C'] = 0;
				$count_alt_errada['sem_turma'][$questao['QuestaoProva']['numero_questao']]['C'] = 0;
				$count_alt_errada['turma_1_ano'][$questao['QuestaoProva']['numero_questao']]['D'] = 0;
				$count_alt_errada['turma_2_ano'][$questao['QuestaoProva']['numero_questao']]['D'] = 0;
				$count_alt_errada['sem_turma'][$questao['QuestaoProva']['numero_questao']]['D'] = 0;
				$count_alt_errada['turma_1_ano'][$questao['QuestaoProva']['numero_questao']]['E'] = 0;
				$count_alt_errada['turma_2_ano'][$questao['QuestaoProva']['numero_questao']]['E'] = 0;
				$count_alt_errada['sem_turma'][$questao['QuestaoProva']['numero_questao']]['E'] = 0;
			}

			//agora deve ser feito uma contagem das questoes erradas
			$num_candidatos_turma1ano = 0;
			$num_candidatos_turma2ano = 0;
			$num_candidatos_semturma = 0;
			foreach($candidatos as $candidato)
			{
				//somente verifica a turma do candidato para atualizar os conadores
				if($candidato['Candidato']['turma'] == 1)
				{
					$num_candidatos_turma1ano++;
				}
				else
				{
					if($candidato['Candidato']['turma'] == 2)
					{
						$num_candidatos_turma2ano++;
					}
					else
					{
						$num_candidatos_semturma++;
					}
				}

				$respostas_questoes_prova = $this->RespostaQuestaoProva->getRespostasCandidato($candidato['Candidato']['candidato_id']); 

				foreach($questoes_prova as $questao)
				{
					//verifica se a questao foi anulada
					if($questao['QuestaoProva']['anulada'] == 0)
					{
						//questao não foi anulada, continuar com a contagem

						$tabela_questao_estatistica[$questao['QuestaoProva']['numero_questao']]['anulada'] = 0;

						$questao_prova_atual = $questao['QuestaoProva']['questao_prova_id'];

						//procurar a resposta correspondente a questao atual
						foreach($respostas_questoes_prova  as $resposta)
						{
							if($questao_prova_atual == $resposta['RespostaQuestaoProva']['questao_prova_id'])
							{
								if($questao['QuestaoProva']['alternativa_correta'] == $resposta['RespostaQuestaoProva']['alternativa_marcada'])
								{
									//acertou a questao, agora verifica se é turma de 1 ou 2 anos
									if($candidato['Candidato']['turma'] == 1)
									{
										//turma de 1 ano
										$tabela_questao_estatistica[$questao['QuestaoProva']['numero_questao']]['acerto_1_ano']++;
									}
									else
									{
										if($candidato['Candidato']['turma'] == 2)
										{
											//turma de 2 ano
											$tabela_questao_estatistica[$questao['QuestaoProva']['numero_questao']]['acerto_2_ano']++;
										}
										else
										{
											//candidato sem turma
											$tabela_questao_estatistica[$questao['QuestaoProva']['numero_questao']]['acerto_sem_turma']++;
										}
									}
								}
								else
								{
									//errou a questao, agora verifica se é turma de 1 ou 2 anos
									if($candidato['Candidato']['turma'] == 1)
									{
										$tabela_questao_estatistica[$questao['QuestaoProva']['numero_questao']]['erro_1_ano']++;
										$count_alt_errada['turma_1_ano'][$questao['QuestaoProva']['numero_questao']][$resposta['RespostaQuestaoProva']['alternativa_marcada']]++;
									}
									else
									{
										if($candidato['Candidato']['turma'] == 2)
										{
											$tabela_questao_estatistica[$questao['QuestaoProva']['numero_questao']]['erro_2_ano']++;
											$count_alt_errada['turma_2_ano'][$questao['QuestaoProva']['numero_questao']][$resposta['RespostaQuestaoProva']['alternativa_marcada']]++;
										}
										else
										{
											//candidato sem turma
											$tabela_questao_estatistica[$questao['QuestaoProva']['numero_questao']]['erro_sem_turma']++;
											$count_alt_errada['sem_turma'][$questao['QuestaoProva']['numero_questao']][$resposta['RespostaQuestaoProva']['alternativa_marcada']]++;
										}
									}
								}
							}
						}
					}
					else
					{
						$tabela_questao_estatistica[$questao['QuestaoProva']['numero_questao']]['anulada'] = 1;
					}
				}	
			}

			//selecionando a alternativa errada mais marcada
			for($i = 1; $i <= $num_questoes; $i++)
			{
				//turma de 1 ano
				$alt_mais_marcada = 'A';
				if($count_alt_errada['turma_1_ano'][$i]['A'] < $count_alt_errada['turma_1_ano'][$i]['B'])
					$alt_mais_marcada = 'B';
				if($count_alt_errada['turma_1_ano'][$i]['B'] < $count_alt_errada['turma_1_ano'][$i]['C'])
					$alt_mais_marcada = 'C';
				if($count_alt_errada['turma_1_ano'][$i]['C'] < $count_alt_errada['turma_1_ano'][$i]['D'])
					$alt_mais_marcada = 'D';
				if($count_alt_errada['turma_1_ano'][$i]['D'] < $count_alt_errada['turma_1_ano'][$i]['E'])
					$alt_mais_marcada = 'E';
				$count_alt_errada['turma_1_ano'][$i]['mais_marcada'] = $alt_mais_marcada;

				//turma de 2 anos
				$alt_mais_marcada = 'A';
				if($count_alt_errada['turma_2_ano'][$i]['A'] < $count_alt_errada['turma_2_ano'][$i]['B'])
					$alt_mais_marcada = 'B';
				if($count_alt_errada['turma_2_ano'][$i]['B'] < $count_alt_errada['turma_2_ano'][$i]['C'])
					$alt_mais_marcada = 'C';
				if($count_alt_errada['turma_2_ano'][$i]['C'] < $count_alt_errada['turma_2_ano'][$i]['D'])
					$alt_mais_marcada = 'D';
				if($count_alt_errada['turma_2_ano'][$i]['D'] < $count_alt_errada['turma_2_ano'][$i]['E'])
					$alt_mais_marcada = 'E';
				$count_alt_errada['turma_2_ano'][$i]['mais_marcada'] = $alt_mais_marcada;

				//sem turma definida
				$alt_mais_marcada = 'A';
				if($count_alt_errada['sem_turma'][$i]['A'] < $count_alt_errada['sem_turma'][$i]['B'])
					$alt_mais_marcada = 'B';
				if($count_alt_errada['sem_turma'][$i]['B'] < $count_alt_errada['sem_turma'][$i]['C'])
					$alt_mais_marcada = 'C';
				if($count_alt_errada['sem_turma'][$i]['C'] < $count_alt_errada['sem_turma'][$i]['D'])
					$alt_mais_marcada = 'D';
				if($count_alt_errada['sem_turma'][$i]['D'] < $count_alt_errada['sem_turma'][$i]['E'])
					$alt_mais_marcada = 'E';
				$count_alt_errada['sem_turma'][$i]['mais_marcada'] = $alt_mais_marcada;

			}

			//$count_alt_errada[$questao['QuestaoProva']['numero_questao']][$resposta['RespostaQuestaoProva']['alternativa_marcada']]++;

			$tabela_questao_estatistica['semTurma']['total'] = $num_candidatos_semturma;
			$tabela_questao_estatistica['Turma1ano']['total'] = $num_candidatos_turma1ano;
			$tabela_questao_estatistica['Turma2ano']['total'] = $num_candidatos_turma2ano;

			/*passa os dados para a view
			$this->set('prova_ano', $prova_ano);
			$this->set('num_candidatos', $num_candidatos);
			$this->set('num_questoes', $num_questoes);
			$this->set('tabela_questao_estatistica', $tabela_questao_estatistica);
			$this->set('count_alt_errada', $count_alt_errada);
			*/
			$tabelas = array();
			$tabelas[0] = $tabela_questao_estatistica;
			$tabelas[1] = $count_alt_errada;
			$tabelas[2] = $num_questoes;
			$tabelas[3] = $num_candidatos;

			return $tabelas;

		}
	}

	/*
	 * Relatório estatisico de acordo com a figura 2
	 */
	function relatorio_estatistico_2()
	{
		if(empty($this->data))
		{
			//pedir o ano da prova
			$this->set('tipo_relatorio', 'relatorio_estatistico_2');
			$this->render('escolha_ano_prova');
		}
		else
		{
			$this->set('content_title', 'Relatório Estatístico 2');

			//verifica se a prova esta cadastrada.
			if($this->Prova->existe($this->data['Prova']['ano']))
			{
				//pega os dados do relatório
				$tabelas = $this->get_tabelas_rel_estat_2($this->data['Prova']['ano']);

				//passa os dados para a view
				$this->set('prova_ano', $this->data['Prova']['ano']);
				$this->set('num_candidatos', $tabelas[2]);
				$this->set('num_questoes', $tabelas[1]);
				$this->set('tabela_questao_estatistica', $tabelas[0]);
			}
			else
			{
				//prova não existe
				$this->Session->setFlash('Prova não existe! Digite um ano válido');
				$this->set('tipo_relatorio', 'relatorio_estatistico_2');
				$this->render('escolha_ano_prova');
			}	
		}
	}

	function get_tabelas_rel_estat_2($prova_ano)
	{
		//gerar o relatorio
		App::import('Model', 'QuestaoProva');
		$this->QuestaoProva = new QuestaoProva();
		App::import('Model', 'Candidato');
		$this->Candidato = new Candidato();
		App::import('Model', 'RespostaQuestaoProva');
		$this->RespostaQuestaoProva = new RespostaQuestaoProva();

		//selecionar todos os candidatos que realizaram a prova
		$candidatos = $this->Candidato->getAllCandidatosPorProva($prova_ano);
		$num_candidatos = 0;
		foreach($candidatos as $candidato)
		{
			$num_candidatos++;
		}

		//selecionar todas as questões da prova
		$questoes_prova = $this->QuestaoProva->getAllQuestoesProva($this->Prova->obterId($prova_ano));
		$num_questoes = 0;

		//preparando a tabela dos dados do relatório
		$tabela_questao_estatistica = array();
		foreach($questoes_prova as $questao)
		{
			$num_questoes++;
			$tabela_questao_estatistica['semTurma'][$questao['QuestaoProva']['numero_questao']]['acertos'] = 0;
			$tabela_questao_estatistica['semTurma'][$questao['QuestaoProva']['numero_questao']]['erros'] = 0;
			$tabela_questao_estatistica['Turma1ano'][$questao['QuestaoProva']['numero_questao']]['acertos'] = 0;
			$tabela_questao_estatistica['Turma1ano'][$questao['QuestaoProva']['numero_questao']]['erros'] = 0;
			$tabela_questao_estatistica['Turma2ano'][$questao['QuestaoProva']['numero_questao']]['acertos'] = 0;
			$tabela_questao_estatistica['Turma2ano'][$questao['QuestaoProva']['numero_questao']]['erros'] = 0;
			$tabela_questao_estatistica['anulada'][$questao['QuestaoProva']['numero_questao']] = false;
		}

		//agora deve ser feito uma contagem das questoes erradas
		$num_candidatos_turma1ano = 0;
		$num_candidatos_turma2ano = 0;
		$num_candidatos_semturma = 0;
		foreach($candidatos as $candidato)
		{
			//somente verifica a turma do candidato para atualizar os conadores
			if($candidato['Candidato']['turma'] == 1)
			{
				$num_candidatos_turma1ano++;
			}
			else
			{
				if($candidato['Candidato']['turma'] == 2)
				{
					$num_candidatos_turma2ano++;
				}
				else
				{
					$num_candidatos_semturma++;
				}
			}

			$respostas_questoes_prova = $this->RespostaQuestaoProva->getRespostasCandidato($candidato['Candidato']['candidato_id']); 

			foreach($questoes_prova as $questao)
			{
				//verifica se a questao foi anulada
				if($questao['QuestaoProva']['anulada'] == 0)
				{

					$tabela_questao_estatistica['anulada'][$questao['QuestaoProva']['numero_questao']] = 0;

					//questao não foi anulada, continuar com a contagem
					$questao_prova_atual = $questao['QuestaoProva']['questao_prova_id'];

					//procurar a resposta correspondente a questao atual
					foreach($respostas_questoes_prova  as $resposta)
					{
						if($questao_prova_atual == $resposta['RespostaQuestaoProva']['questao_prova_id'])
						{
							if($questao['QuestaoProva']['alternativa_correta'] == $resposta['RespostaQuestaoProva']['alternativa_marcada'])
							{
								//acertou a questao, agora verifica se é turma de 1 ou 2 anos
								if($candidato['Candidato']['turma'] == 1)
								{
									//turma de 1 ano
									$tabela_questao_estatistica['Turma1ano'][$questao['QuestaoProva']['numero_questao']]['acertos']++;
								}
								else
								{
									if($candidato['Candidato']['turma'] == 2)
									{
										//turma de 2 ano
										$tabela_questao_estatistica['Turma2ano'][$questao['QuestaoProva']['numero_questao']]['acertos']++;
									}
									else
									{
										//candidato sem turma
										$tabela_questao_estatistica['semTurma'][$questao['QuestaoProva']['numero_questao']]['acertos']++;
									}
								}
							}
							else
							{
								//errou a questao, agora verifica se é turma de 1 ou 2 anos
								if($candidato['Candidato']['turma'] == 1)
								{
									$tabela_questao_estatistica['Turma1ano'][$questao['QuestaoProva']['numero_questao']]['erros']++;
								}
								else
								{
									if($candidato['Candidato']['turma'] == 2)
									{
										$tabela_questao_estatistica['Turma2ano'][$questao['QuestaoProva']['numero_questao']]['erros']++;
									}
									else
									{
										//candidato sem turma
										$tabela_questao_estatistica['semTurma'][$questao['QuestaoProva']['numero_questao']]['erros']++;
									}
								}
							}
						}
					}
				}
				else
				{
					$tabela_questao_estatistica['anulada'][$questao['QuestaoProva']['numero_questao']] = 1;
				}
			}	
		}

		$tabela_questao_estatistica['semTurma']['total'] = $num_candidatos_semturma;
		$tabela_questao_estatistica['Turma1ano']['total'] = $num_candidatos_turma1ano;
		$tabela_questao_estatistica['Turma2ano']['total'] = $num_candidatos_turma2ano;

		/*
		//passa os dados para a view
		$this->set('prova_ano', $prova_ano);
		$this->set('num_candidatos', $num_candidatos);
		$this->set('num_questoes', $num_questoes);
		$this->set('tabela_questao_estatistica', $tabela_questao_estatistica);
		*/

		$tabelas = array();
		$tabelas[0] = $tabela_questao_estatistica;
		$tabelas[1] = $num_questoes;
		$tabelas[2] = $num_candidatos;
		return $tabelas;
	}

	/*
	 * Exportar o relatorio estatistico 1 no formato definido pelo $tipo
	 */
	function exportar_rel_est_1($prova_ano, $tipo)
	{
		if($tipo == 'excel')
		{
			/*
			 * Exemplo de uso:

			$this->Excel->iniciando('teste.xls');
			$myArr=array('CODIGO','DESCRICAO','VALOR');
   			$this->Excel->writeLine($myArr);
   			$this->Excel->fechando();

   			*/

   			$tabelas = $this->get_tabelas_rel_estat_1($prova_ano);

   			$tabela_questao_estatistica = $tabelas[0];
			$count_alt_errada = $tabelas[1];
			$num_questoes = $tabelas[2];

   			$this->Excel->iniciando('relatorio_est_1.xls');
   			$myArr=array('Prova Ano: ', $prova_ano);
   			$this->Excel->writeLine($myArr);
   			$myArr=array(' ');
   			$this->Excel->writeLine($myArr);

			$myArr=array('Num Questao', 'Alt. correta','Habilidade Avaliada', '% erro', ' ', 'alt. errada mais marcada(%)', ' ');
   			$this->Excel->writeLine($myArr);
   			$myArr=array(' ', ' ', ' ', 'T. 1 ano', 'T. 2 anos', 'T. 1 ano', 'T. 2 anos');
   			$this->Excel->writeLine($myArr);

   			$string = '';
   			$string1 = '';
			$string2 = '';
			$string3 = '';
			$string4 = '';
			$string5 = '';
			$string6 = '';
			$string7 = '';

			for($i = 1; $i <= $num_questoes; $i++)
			{

				if($tabela_questao_estatistica[$i]['anulada'] == 1)
				{
					$string1 = $i;
					$string2 = 'anulada';
					$string3 = '-';
					$string4 = '-';
					$string5 = '-';
					$string5 = '-';
					$string7 = '-';
				}
				else
				{
					$string1 = $i;

					$string2 = $tabela_questao_estatistica[$i]['alteranativa_correta'];

					$string3 = $tabela_questao_estatistica[$i]['habilidade_avaliada'];

					if($tabela_questao_estatistica['Turma1ano']['total'] == 0)
					{
						$string4 = '0';
					}
					else
					{
						$string = $tabela_questao_estatistica[$i]['erro_1_ano']/ $tabela_questao_estatistica['Turma1ano']['total'] *100;
						if(strlen($string)>5)
						{
							$string4 = substr($string, 0, 5);
						}
						else
						{
							$string4 = $string;
						}
					}
					if($tabela_questao_estatistica['Turma2ano']['total'] == 0)
					{
						$string5 = '0';
					}
					else
					{
						$string = $tabela_questao_estatistica[$i]['erro_2_ano']/ $tabela_questao_estatistica['Turma2ano']['total'] *100;
						if(strlen($string)>5)
						{
							$string5 = substr($string, 0, 5);
						}
						else
						{
							$string5 = $string;
						}
					}
					if($tabela_questao_estatistica['Turma1ano']['total'] == 0)
					{
						$string6 = '0';
					}
					else
					{
						$string = $count_alt_errada['turma_1_ano'][$i]['mais_marcada'].' - '.($count_alt_errada['turma_1_ano'][$i][$count_alt_errada['turma_1_ano'][$i]['mais_marcada']]/ $tabela_questao_estatistica['Turma1ano']['total'] *100);
						if(strlen($string)>9)
						{
							$string6 = substr($string, 0, 9);
						}
						else
						{
							$string6 = $string;
						}
					}
					if($tabela_questao_estatistica['Turma2ano']['total'] == 0)
					{
						$string7 = '0';
					}
					else
					{
						$string = $count_alt_errada['turma_2_ano'][$i]['mais_marcada'].' - '.($count_alt_errada['turma_2_ano'][$i][$count_alt_errada['turma_2_ano'][$i]['mais_marcada']]/ $tabela_questao_estatistica['Turma2ano']['total'] *100);
						if(strlen($string)>9)
						{
							$string7 = substr($string, 0, 9);
						}
						else
						{
							$string7 = $string;
						}
					}
				}

				$myArr=array($string1, $string2, $string3, $string4, $string5, $string6, $string7);
   				$this->Excel->writeLine($myArr);

			}

			$this->Excel->fechando();

		}
		else
			if($tipo == 'pdf')
			{

				$this->Fpdf->FpdfComponent("P", "mm", "A4");
				$pdf = $this->Fpdf; 

				//cabeçalho
				$pdf->header_tipo('relatorio_prova');

				$pdf->AddPage();

				$pdf->SetFont('','',10);
				$pdf->Cell(0,10,'Relatório estatístico 1',0,1);
				$tabelas = $this->get_tabelas_rel_estat_1($prova_ano);

	   			$tabela_questao_estatistica = $tabelas[0];
				$count_alt_errada = $tabelas[1];
				$num_questoes = $tabelas[2];

				//usando a tabela AddTableCPV(), veja explicação na função no componente
				$header = array();
				$header[] = array('N. Questão', 'Alt. correta', 'Habilidade Avaliada', '% Erro', '', '% mais assinalada.', '');
				$header[] = array('', '', '', 'T. 1 ano', 'T. 2 anos', 'T. 1 ano', 'T. 2 anos');
				$width = array(23, 20, 50, 15, 19, 36, 19);
				$data = array();

				$string = '';
	   			$string1 = '';
				$string2 = '';
				$string3 = '';
				$string4 = '';
				$string5 = '';
				$string6 = '';
				$string7 = '';

				for($i = 1; $i <= $num_questoes; $i++)
				{

					if($tabela_questao_estatistica[$i]['anulada'] == 1)
					{
						$string1 = $i;
						$string2 = 'anulada';
						$string3 = '-';
						$string4 = '-';
						$string5 = '-';
						$string5 = '-';
						$string7 = '-';
					}
					else
					{
						$string1 = $i;

						$string2 = $tabela_questao_estatistica[$i]['alteranativa_correta'];

						$string3 = $tabela_questao_estatistica[$i]['habilidade_avaliada'];

						if($tabela_questao_estatistica['Turma1ano']['total'] == 0)
						{
							$string4 = '0';
						}
						else
						{
							$string = $tabela_questao_estatistica[$i]['erro_1_ano']/ $tabela_questao_estatistica['Turma1ano']['total'] *100;
							if(strlen($string)>5)
							{
								$string4 = substr($string, 0, 5);
							}
							else
							{
								$string4 = $string;
							}
						}
						if($tabela_questao_estatistica['Turma2ano']['total'] == 0)
						{
							$string5 = '0';
						}
						else
						{
							$string = $tabela_questao_estatistica[$i]['erro_2_ano']/ $tabela_questao_estatistica['Turma2ano']['total'] *100;
							if(strlen($string)>5)
							{
								$string5 = substr($string, 0, 5);
							}
							else
							{
								$string5 = $string;
							}
						}
						if($tabela_questao_estatistica['Turma1ano']['total'] == 0)
						{
							$string6 = '0';
						}
						else
						{
							$string = $count_alt_errada['turma_1_ano'][$i]['mais_marcada'].' - '.($count_alt_errada['turma_1_ano'][$i][$count_alt_errada['turma_1_ano'][$i]['mais_marcada']]/ $tabela_questao_estatistica['Turma1ano']['total'] *100);
							if(strlen($string)>9)
							{
								$string6 = substr($string, 0, 9);
							}
							else
							{
								$string6 = $string;
							}
						}
						if($tabela_questao_estatistica['Turma2ano']['total'] == 0)
						{
							$string7 = '0';
						}
						else
						{
							$string = $count_alt_errada['turma_2_ano'][$i]['mais_marcada'].' - '.($count_alt_errada['turma_2_ano'][$i][$count_alt_errada['turma_2_ano'][$i]['mais_marcada']]/ $tabela_questao_estatistica['Turma2ano']['total'] *100);
							if(strlen($string)>9)
							{
								$string7 = substr($string, 0, 9);
							}
							else
							{
								$string7 = $string;
							}
						}
					}

					$data[] = array($string1, $string2, $string3, $string4, $string5, $string6, $string7);

				}

				$pdf->AddTableCPV($header, $width, $data);

				$pdf->Output('rel_est_1.pdf','D');
			}
		$this->render('branco');
	}

	/*
	 * Exportar o relatorio estatistico 2 no formato definido pelo $tipo
	 */
	function exportar_rel_est_2($prova_ano, $tipo)
	{
		if($tipo == 'excel')
		{
			$tabelas = $this->get_tabelas_rel_estat_2($prova_ano);

   			$tabela_questao_estatistica = $tabelas[0];
			$num_questoes = $tabelas[1];

   			$this->Excel->iniciando('relatorio_est_2.xls');

			$myArr=array('Curso Pré-Vestibular da UFSCar');
   			$this->Excel->writeLine($myArr);

			$myArr=array('Prova Ano: ', $prova_ano);
   			$this->Excel->writeLine($myArr);
   			$myArr=array(' ');
   			$this->Excel->writeLine($myArr);
			$myArr=array('Turma de 1 ano: ',$tabela_questao_estatistica['Turma1ano']['total'].' candidatos', '', '', 'Turma de 2 anos: ',$tabela_questao_estatistica['Turma2ano']['total'].' candidatos');
   			$this->Excel->writeLine($myArr);
   			$myArr=array();
   			$this->Excel->writeLine($myArr);
   			$myArr=array('Nº questão','% erros', 'total erros', '', 'Nº questão','% erros', 'total erros');
   			$this->Excel->writeLine($myArr);
   			$myArr=array();
   			$this->Excel->writeLine($myArr);

   			$string = '';
   			$string1 = ''; //num questao
			$string2 = ''; //% erros
			$string3 = ''; //total
			$string4 = ''; 
			$string5 = ''; //num questao
			$string6 = ''; //% erros
			$string7 = ''; //total
			$string8 = ''; 
			$string9 = ''; //num questao
			$string10 = ''; //% erros
			$string11 = ''; //total

			for($i = 1; $i <= $num_questoes; $i++)
			{

				if($tabela_questao_estatistica['anulada'][$i])
				{
					$string1 = $i; 
					$string2 = 'anulada'; 
					$string3 = 'anulada'; 
					$string5 = $i; 
					$string6 = 'anulada'; 
					$string7 = 'anulada'; 
					$string9 = $i; 
					$string10 = 'anulada'; 
					$string11 = 'anulada'; 
				}
				else
				{
					$string1 = $i; 
					$string5 = $i; 
					$string9 = $i; 

					//Turma de 1 ano

					if($tabela_questao_estatistica['Turma1ano']['total'] == 0)
					{
						$string2 = '0'; 
					}
					else
					{
						$string = $tabela_questao_estatistica['Turma1ano'][$i]['erros']/$tabela_questao_estatistica['Turma1ano']['total']*100;
						if(strlen($string)>5)
						{
							$string2 = substr($string, 0, 5);
						}
						else
						{
							$string2 = $string; 
						}
					}
					$string3 = $tabela_questao_estatistica['Turma1ano'][$i]['erros'];

					//turma de 2 anos

					if($tabela_questao_estatistica['Turma2ano']['total'] == 0)
					{
						$string6 = '0';
					}
					else
					{
						$string = $tabela_questao_estatistica['Turma2ano'][$i]['erros']/$tabela_questao_estatistica['Turma2ano']['total']*100;
						if(strlen($string)>5)
						{
							$string6 = substr($string, 0, 5);
						}
						else
						{
							$string6 = $string;
						}
					}
					$string7 = $tabela_questao_estatistica['Turma2ano'][$i]['erros'];

					//sem Turma Definida

					if($tabela_questao_estatistica['semTurma']['total'] == 0)
					{
						$string10 = '0'; 
					}
					else
					{
						$string = $tabela_questao_estatistica['semTurma'][$i]['erros']/$tabela_questao_estatistica['semTurma']['total']*100;
						if(strlen($string)>5)
						{
							$string10 = substr($string, 0, 5);
						}
						else
						{
							$string10 = $string;
						}
					}
					$string11 = $tabela_questao_estatistica['semTurma'][$i]['erros'];
				}

				$myArr=array($string1, $string2, $string3, $string4, $string5, $string6, $string7);
   				$this->Excel->writeLine($myArr);

			}

			$this->Excel->fechando();
		}
		else
			if($tipo == 'pdf')
			{
				$this->Fpdf->FpdfComponent("P", "mm", "A4");
				$pdf = $this->Fpdf;

				//cabeçalho
				$pdf->header_tipo('relatorio_prova');

				$pdf->AddPage();
				$pdf->SetFont('','',10);
				$pdf->Cell(0,10,'Relatório estatístico 2',0,1);

				$tabelas = $this->get_tabelas_rel_estat_2($prova_ano);

	   			$tabela_questao_estatistica = $tabelas[0];
				$num_questoes = $tabelas[1];

				//usando a tabela AddTableCPV(), veja explicação na função no componente
				$header = array();
				$header[] = array('N. Questão', '% Erros', 'Total de erros', ' ', 'N. Questão', '% Erros', 'Total de erros');
				$width = array(23, 20, 25, 30, 23, 20, 25);
				$data = array();

	   			$string = '';
	   			$string1 = ''; //num questao
				$string2 = ''; //% erros
				$string3 = ''; //total
				$string4 = ''; 
				$string5 = ''; //num questao
				$string6 = ''; //% erros
				$string7 = ''; //total
				$string8 = ''; 
				$string9 = ''; //num questao
				$string10 = ''; //% erros
				$string11 = ''; //total

				for($i = 1; $i <= $num_questoes; $i++)
				{

					if($tabela_questao_estatistica['anulada'][$i])
					{
						$string1 = $i; 
						$string2 = 'anulada'; 
						$string3 = 'anulada'; 
						$string5 = $i; 
						$string6 = 'anulada'; 
						$string7 = 'anulada'; 
						$string9 = $i; 
						$string10 = 'anulada'; 
						$string11 = 'anulada'; 
					}
					else
					{
						$string1 = $i; 
						$string5 = $i; 
						$string9 = $i; 

						//Turma de 1 ano

						if($tabela_questao_estatistica['Turma1ano']['total'] == 0)
						{
							$string2 = '0'; 
						}
						else
						{
							$string = $tabela_questao_estatistica['Turma1ano'][$i]['erros']/$tabela_questao_estatistica['Turma1ano']['total']*100;
							if(strlen($string)>5)
							{
								$string2 = substr($string, 0, 5);
							}
							else
							{
								$string2 = $string; 
							}
						}
						$string3 = $tabela_questao_estatistica['Turma1ano'][$i]['erros'];

						//turma de 2 anos

						if($tabela_questao_estatistica['Turma2ano']['total'] == 0)
						{
							$string6 = '0';
						}
						else
						{
							$string = $tabela_questao_estatistica['Turma2ano'][$i]['erros']/$tabela_questao_estatistica['Turma2ano']['total']*100;
							if(strlen($string)>5)
							{
								$string6 = substr($string, 0, 5);
							}
							else
							{
								$string6 = $string;
							}
						}
						$string7 = $tabela_questao_estatistica['Turma2ano'][$i]['erros'];

						//sem Turma Definida

						if($tabela_questao_estatistica['semTurma']['total'] == 0)
						{
							$string10 = '0'; 
						}
						else
						{
							$string = $tabela_questao_estatistica['semTurma'][$i]['erros']/$tabela_questao_estatistica['semTurma']['total']*100;
							if(strlen($string)>5)
							{
								$string10 = substr($string, 0, 5);
							}
							else
							{
								$string10 = $string;
							}
						}
						$string11 = $tabela_questao_estatistica['semTurma'][$i]['erros'];
					}

					$data[] = array($string1, $string2, $string3, $string4, $string5, $string6, $string7);

				}
				$pdf->AddTableCPV($header, $width, $data);
				$pdf->Output('rel_est_2.pdf','D');
			}
		$this->render('branco');
	}
	function beforeFilter() {
		parent::beforeFilter(); 
		$this->set('moduloAtual', 'candidatos');
	}

    function listar_candidatos($ano, $turma)
    {
        $this->set('content_title', 'Preencher nota dos candidatos');
        $condicao = array('Candidato.ano' => $ano, 'Candidato.turma' => $turma);

        $this->paginate = array('order' => array('Candidato.ano' => 'desc',
            'Candidato.numero_inscricao' => 'asc'),
            'recursive' => '0',
            'fields' => array('numero_inscricao', 'ano', 'nome',
                'fase_eliminatoria_status', 'fase_classificatoria_status',
                'pontuacao_social', 'pontuacao_economica', 'nota_prova', 'prova_especial'),
            'limit' => '50');

        $candidatos = $this->paginate('Candidato', $condicao);

        $this->set('candidatos', $candidatos);
        $this->set('ano', $ano);
        $this->set('turma', $turma);

    }

    function listar_candidatos_action()
    {
        if (!empty($this->data))
        {
            foreach ($this->data['Candidato'] as $id => $valor)
            {
                $mudou = false;

                if ($id != 'url') {
                    $candidato = $this->Candidato->find('first', array('conditions' => array('Candidato.candidato_id' => $id),
                        'recursive' => '0',
                        'fields' => array('candidato_id', 'fase_eliminatoria_status', 'nota_prova')));

                    if ($candidato['Candidato']['fase_eliminatoria_status'] != $valor['fase_eliminatoria_status']){
                        $mudou = true;
                        $candidato['Candidato']['fase_eliminatoria_status'] = $valor['fase_eliminatoria_status'];
                    }
                    if ($candidato['Candidato']['nota_prova'] != $valor['nota_prova']){
                        $mudou = true;
                        $candidato['Candidato']['nota_prova'] = $valor['nota_prova'];
                    }

                    //validação
                    if($candidato['Candidato']['fase_eliminatoria_status'] == 1){
                        $candidato['Candidato']['fase_eliminatoria_social_status'] = 1;
                        $candidato['Candidato']['fase_eliminatoria_economico_status'] = 1;
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
}
?>