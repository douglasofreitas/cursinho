<?php 
/**
 * Classe correspondente ao Módulo Candidatos
 */
class CriteriosDaFaseClassificatoriasController extends AppController {
	//var $scaffold;
	var $layout = 'tricolor_layout';
	var $pageTitle = 'candidatos';
	var $paginate;
	var $helpers = array('Chart');
	var $uses = array('Candidato', 'CriteriosDaFaseClassificatoria', 'ProcessoSeletivo');
	function index()
	{
		$this->set('content_title','Critérios da fase de classificação');
	}
	function iniciar()
	{	
		$this->set('content_title','Fase Classificatória');
		//selecionar o ano para executar a fase de classificação
		if(empty($this->data))
		{
			//obter processo seletivo.
			$this->set('metodo_destino', 'iniciar');
			$this->render('ano_fase');
		}
		else
		{
			if($this->data['CriteriosDaFaseClassificatoria']['ano_fase'] != '')
			{
				//verifica se ja foi feito a fase eliminatória do ano informado
				if($this->ProcessoSeletivo->faseEliminatoriaEfetuada($this->ProcessoSeletivo->obterId($this->data['CriteriosDaFaseClassificatoria']['ano_fase'])))
				{
					$ano_fase = $this->data['CriteriosDaFaseClassificatoria']['ano_fase'];
					unset($this->data);
					$this->redirect('/criterios_da_fase_classificatorias/visualizar_status/'.$ano_fase);
				}
				else
				{
					$this->Session->setFlash('Fase Eliminatória ainda não realizada! Favor executa-la para continuar o processo seletivo.');
					$this->redirect('/candidatos/index');
				}
			}
			else
			{
				$this->Session->setFlash('Insira um ano válido!');
				$this->set('metodo_destino', 'iniciar');
				$this->render('ano_fase');
			}
		}
	}
	function iniciar_proxima_chamada()
	{
		$this->set('content_title','Próxima Chamada');
		//selecionar o ano para executar a fase de classificação
		if(empty($this->data))
		{
			//obter processo seletivo.
			$this->set('metodo_destino', 'iniciar_proxima_chamada');
			$this->render('ano_fase');
		}
		else
		{
			$ano_fase = $this->data['CriteriosDaFaseClassificatoria']['ano_fase'];
			unset($this->data);
			$this->redirect('/criterios_da_fase_classificatorias/proxima_chamada/'.$ano_fase);
		}
	}
	function inserir_criterios($ano_fase)
	{
		$this->set('content_title','Inserir Critérios');
		$processo_seletivo_id = $this->ProcessoSeletivo->obterId($ano_fase);
		$this->set('ano_fase', $ano_fase);
		$this->set('processo_seletivo_id', $processo_seletivo_id);
		if(empty($this->data))
		{
			//exibir formlário com as vagas a serem inseridas no sistema
			//verifica se o processo seletivo ja contem os critérios ja adicionado ao banco
			$this->CriteriosDaFaseClassificatoria->create();
			if(!$this->CriteriosDaFaseClassificatoria->hasFaseClassificatoria($processo_seletivo_id))
			{
				//ainda não tem os critérios, será criado
			}
			else
			{
				//ja possui os critérios
				//redirecionar para o alterar critérios da fase classificatória
				$this->redirect('/criterios_da_fase_classificatorias/alterar_criterios/'.$processo_seletivo_id);
			}
		}
		else
		{
			//verifica se os campos estão em branco
			if($this->data['CriteriosDaFaseClassificatoria']['total_vagas_um_ano'] != '' 
			 	& $this->data['CriteriosDaFaseClassificatoria']['total_vagas_dois_anos'] != '')
			{
				//salva as alterações
				$this->CriteriosDaFaseClassificatoria->create();
				$this->data['CriteriosDaFaseClassificatoria']['processo_seletivo_id'] = $processo_seletivo_id;
				if($this->CriteriosDaFaseClassificatoria->save($this->data))
				{
					//salvou com sucesso
					//buscando os candidatos aprovados
					$this->buscar_aprovados($ano_fase);
					$this->Session->setFlash('Critérios da fase salvos com sucesso!');
					$this->redirect('/criterios_da_fase_classificatorias/visualizar_aprovados/'.$ano_fase);
				}
				else
				{
					//houve algum erro
					$this->Session->setFlash('Não foi possivel salvar os critérios!');
					$this->redirect('/criterios_da_fase_classificatorias/visualizar_aprovados/'.$ano_fase);
				}
			}
			else
			{
				$this->Session->setFlash('Insira pelo menos os totais de vagas!');
			}
		}
	}
	function alterar_criterios($ano_fase)
	{
		$this->set('content_title','Alterar Critérios');
		//verificar se ja foi feito pelo menos 2 chamadas, pois se tiver sido feito não poderá ser alterado os critérios, pois as chamadas ficarão inconsistentes
		if($this->Candidato->fezSomentePrmeiraChamada($ano_fase))
		{
			if(!empty($this->data))
			{
				//verifica se os campos estão em branco
				if($this->data['CriteriosDaFaseClassificatoria']['total_vagas_um_ano'] != '' 
				 	& $this->data['CriteriosDaFaseClassificatoria']['total_vagas_dois_anos'] != '')
				{
					//não houve proximas chamadas, então pode ser atualizado
					//atualizar
					$this->CriteriosDaFaseClassificatoria->create();
					if($this->CriteriosDaFaseClassificatoria->save($this->data))
					{
						//criterios salvos, atulizar os candidatos aprovados
						//nova busca pelos candidatos aprovados
						$this->buscar_aprovados($ano_fase);
						$this->Session->setFlash('Critérios alterados com sucesso!');
						$this->redirect('/criterios_da_fase_classificatorias/visualizar_aprovados/'.$ano_fase);
					}
					else
					{
						$this->Session->setFlash('Não foi possivel atualizar os critérios!');
						$this->redirect('/criterios_da_fase_classificatorias/visualizar_aprovados/'.$ano_fase);
					}
				}
				else
				{
					$this->Session->setFlash('Insira pelo menos os totais de vagas!');
					$this->set('ano_fase', $ano_fase);
				}
			}
			else
			{
				$processo_seletivo_id = $this->ProcessoSeletivo->obterId($ano_fase);
				$this->data = $this->CriteriosDaFaseClassificatoria->getCriterios($processo_seletivo_id);
				$this->set('ano_fase', $ano_fase);
			}
		}
		else
		{
			$this->Session->setFlash('Foi feita mais de uma chamada, portanto os critérios não podem ser alterados, por invalidar as primeiras chamadas.');
			$this->set('ano_fase', $ano_fase);	
			$this->render('branco');
		}
	}
	function listar_primeira_chamada()
	{
		$this->set('content_title','Listar aprovados da Primeira Chamada');
		//selecionar o ano para executar a fase de classificação
		if(empty($this->data))
		{
			//obter processo seletivo.
			$this->set('metodo_destino', 'listar_primeira_chamada');
			$this->render('ano_fase');
		}
		else
		{
			if(!$this->data['CriteriosDaFaseClassificatoria']['ano_fase'] == '')
			{
				$ano_fase = $this->data['CriteriosDaFaseClassificatoria']['ano_fase'];
				unset($this->data);
				$this->redirect('/candidatos/listar_primeira_chamada/'.$ano_fase);
			}
			else
			{
				$this->Session->setFlash('Insira um ano válido!');
				$this->set('metodo_destino', 'listar_primeira_chamada');
				$this->render('ano_fase');
			}
		}
	}
	function listar_ultima_chamada()
	{
		$this->set('content_title','Listar aprovados da Ultima Chamada');
		//selecionar o ano para executar a fase de classificação
		if(empty($this->data))
		{
			//obter processo seletivo.
			$this->set('metodo_destino', 'listar_ultima_chamada');
			$this->render('ano_fase');
		}
		else
		{
			if(!$this->data['CriteriosDaFaseClassificatoria']['ano_fase'] == '')
			{
				$ano_fase = $this->data['CriteriosDaFaseClassificatoria']['ano_fase'];
				unset($this->data);
				$this->redirect('/candidatos/listar_ultimos_aprovados/'.$ano_fase);
			}
			else
			{
				$this->Session->setFlash('Insira um ano válido!');
				$this->set('metodo_destino', 'listar_ultima_chamada');
				$this->render('ano_fase');
			}
		}
	}
	function proxima_chamada($ano_fase)
	{
		$this->set('content_title','Visualizar status da fase de classificação');
		//verificar se existe um processo seletivo com o ano informado
		if($this->ProcessoSeletivo->existe($ano_fase))
		{
			$processo_seletivo_id = $this->ProcessoSeletivo->obterId($ano_fase);
			if($this->CriteriosDaFaseClassificatoria->hasFaseClassificatoria($processo_seletivo_id))
			{
				//ja foi feita a fase de classificação, então pode ser feita a proxima chamada
				//fazer uma contagem da quantidade de vagas restantes para mostrar ao usuário
				$numero_vagas_1_ano = $this->CriteriosDaFaseClassificatoria->getTotalVagas1Ano($processo_seletivo_id);
				$numero_vagas_2_anos = $this->CriteriosDaFaseClassificatoria->getTotalVagas2Anos($processo_seletivo_id);
				$vagas_restantes_1_ano = $numero_vagas_1_ano - $this->Candidato->getCountCandidatosMatriculados($ano_fase, 1);
				$vagas_restantes_2_anos = $numero_vagas_2_anos - $this->Candidato->getCountCandidatosMatriculados($ano_fase, 2);
				$this->set('numero_vagas_1_ano', $numero_vagas_1_ano);
				$this->set('numero_vagas_2_anos', $numero_vagas_2_anos);
				$this->set('vagas_restantes_1_ano', $vagas_restantes_1_ano);
				$this->set('vagas_restantes_2_anos', $vagas_restantes_2_anos);
				$this->set('ano', $ano_fase);
			}
			else
			{
				//fase de classificação ainda precisa ser feita.
				$this->Session->setFlash('Ainda não foi feito a fase de classificação deste processo seletivo! Ele deve ser feito antes de usar esta opção.');
				$this->render('branco');
			}
		}
		else
		{
			//processo seletivo não existe
			$this->Session->setFlash('Processe Seletivo não existe. Favor verificar se o ano foi escrito corretamente.');
			$this->render('branco');
		}
	}
	function executar_proxima_chamada($ano_fase)
	{
		$this->buscar_aprovados_proxima_chamada($ano_fase);
		$this->redirect('/candidatos/listar_ultimos_aprovados/'.$ano_fase);
	}
	function visualizar_status($ano_fase)
	{
		$this->set('content_title','Visualizar status da fase de classificação');
		//verificar se existe um processo seletivo com o ano informado
		if($this->ProcessoSeletivo->existe($ano_fase))
		{
			$processo_seletivo_id = $this->ProcessoSeletivo->obterId($ano_fase);
			$has_fase_classificatoria = false;
			if($this->CriteriosDaFaseClassificatoria->hasFaseClassificatoria($processo_seletivo_id))
				$has_fase_classificatoria = true;
			$this->set('has_fase_classificatoria', $has_fase_classificatoria);
			$this->set('ano_fase', $ano_fase);
			$this->set('processo_seletivo_id', $processo_seletivo_id);
		}
		else
		{
			//processo seletivo não existe
			$this->Session->setFlash('Processe Seletivo não existe. Favor verificar se o ano foi escrito corretamente.');
			$this->render('branco');
		}
	}
	function visualizar_criterios($ano_fase)
	{
		$this->set('content_title','Visualizar Critérios');
		$processo_seletivo_id = $this->ProcessoSeletivo->obterId($ano_fase);
		$dados = $this->CriteriosDaFaseClassificatoria->getCriterios($processo_seletivo_id);
		$this->set('ano_fase', $ano_fase);
		$this->set('dados', $dados);
	}
	function visualizar_aprovados($ano_fase)
	{
		$this->redirect('/candidatos/listar_aprovados/'.$ano_fase);
	}
	function buscar_aprovados($ano_fase)
	{
		//de acordo com os critérios escolhidos para cada turma, pegar os candidatos selecionados
		//limpando os candidatos aprovados antes de iniciar o método
		$candidatos_aprovados = $this->Candidato->getCandidatosAprovados($ano_fase);
		foreach($candidatos_aprovados as $candidato)
		{
			$this->Candidato->setCandidatoClassificatoria($candidato['Candidato']['numero_inscricao'], $candidato['Candidato']['ano'], 0);
		}
		$candidatos_aprovados = $this->Candidato->getCandidatosUltimaChamada($ano_fase);		
		foreach($candidatos_aprovados as $candidato)
		{
			$this->Candidato->setCandidatosUltimaChamada($candidato['Candidato']['numero_inscricao'], $candidato['Candidato']['ano'], 0);
		}
		$candidatos_aprovados = $this->Candidato->getCandidatosPrimeraChamada($ano_fase);
		foreach($candidatos_aprovados as $candidato)
		{
			$this->Candidato->setPrimeiraChamada($candidato['Candidato']['numero_inscricao'], $candidato['Candidato']['ano'], 0);
		}
		$processo_seletivo_id = $this->ProcessoSeletivo->obterId($ano_fase);
		$criterios = $this->CriteriosDaFaseClassificatoria->getCriterios($processo_seletivo_id);
		$num_total_1_ano = $criterios['CriteriosDaFaseClassificatoria']['total_vagas_um_ano'];
		$num_total_2_anos = $criterios['CriteriosDaFaseClassificatoria']['total_vagas_dois_anos'];
		$num_candidatos_apro_1_ano = 0;
		$num_candidatos_apro_2_anos = 0;
		$erro = 0;
		/* 
		**************************************************** 
        * selecionando os candidatos da turma de 1 ano 
        * ************************************************** 
        */
		$turma = 1;
		$num_indigenas = $criterios['CriteriosDaFaseClassificatoria']['total_vagas_indigenas_um_ano'];
		$num_afro = $criterios['CriteriosDaFaseClassificatoria']['total_vagas_afro_um_ano'];
		$num_faber = $criterios['CriteriosDaFaseClassificatoria']['total_vagas_faber_um_ano'];
		//------- selecionando os candidatos preferenciais: indigenas -----------------------
		$indigenas = $this->Candidato->getCandidatosIndigenas($ano_fase, $turma);
		//echo 'numero de indigenas:'.sizeOf($indigenas).'; ';
		if ($this->Candidato->getCandidatosIndigenasCount($ano_fase, $turma)>0)
		{
			$count = 0;
			foreach($indigenas as $indio)
			{
				if($count < $num_indigenas & $num_candidatos_apro_1_ano <= $num_total_1_ano) 
				{
					if($this->Candidato->setCandidatoClassificatoria($indio['Candidato']['numero_inscricao'], $indio['Candidato']['ano'], 1))
					{
						if($this->Candidato->setCandidatosUltimaChamada($indio['Candidato']['numero_inscricao'], $indio['Candidato']['ano'], 1))
						{
							if($this->Candidato->setPrimeiraChamada($indio['Candidato']['numero_inscricao'], $indio['Candidato']['ano'], 1))
							{
								$num_candidatos_apro_1_ano++;
								$count++;	
							}
						}
					}
					else
					{
						//echo '!!erro ao aprovar o candidato!!';
						$erro = 1;
					}
				}
			}
		}
		//echo 'adicionados:'.$count.';<br/>';
		//------- selecionando os candidatos preferenciais: afro-descendentes -----------------------
		$afros = $this->Candidato->getCandidatosAfrodescendentes($ano_fase, $turma);
		//echo 'numero de afros:'.sizeOf($afros).';';
		if($this->Candidato->getCandidatosAfrodescendentesCount($ano_fase, $turma)>0)
		{
			//agora para adicionar os candidatos, devemos verificar se os candidatos ja estão na lista,
			//para não adicionar novamente
			$count = 0;
			$pode_adicionar = true;
			foreach($afros as $afro)
			{
				if($count < $num_afro & $num_candidatos_apro_1_ano <= $num_total_1_ano) 
				{
						if($this->Candidato->setCandidatoClassificatoria($afro['Candidato']['numero_inscricao'], $afro['Candidato']['ano'], 1))
						{
							if($this->Candidato->setCandidatosUltimaChamada($afro['Candidato']['numero_inscricao'], $afro['Candidato']['ano'], 1))
							{
								if($this->Candidato->setPrimeiraChamada($afro['Candidato']['numero_inscricao'], $afro['Candidato']['ano'], 1))
								{
									$num_candidatos_apro_1_ano++;
									$count++;
								}
							}
						}
						else
						{
							//echo '!!erro ao aprovar o candidato!!';
							$erro = 1;
						}
				}
			}
		}
		//echo 'adicionados:'.$count.';<br/>';
		//------- selecionando os candidatos preferenciais: faber -----------------------
		$fabers = $this->Candidato->getCandidatosFabers($ano_fase, $turma);
		//echo 'numero de fabers:'.sizeOf($fabers).';';
		if($this->Candidato->getCandidatosFabersCount($ano_fase, $turma)>0)
		{
			//agora para adicionar os candidatos, devemos verificar se os candidatos ja estão na lista,
			//para não adicionar novamente
			$count = 0;
			$pode_adicionar = true;
			foreach($fabers as $faber)
			{
				if($count < $num_faber & $num_candidatos_apro_1_ano <= $num_total_1_ano) 
				{
						if($this->Candidato->setCandidatoClassificatoria($faber['Candidato']['numero_inscricao'], $faber['Candidato']['ano'], 1))
						{
							if($this->Candidato->setCandidatosUltimaChamada($faber['Candidato']['numero_inscricao'], $faber['Candidato']['ano'], 1))
							{
								if($this->Candidato->setPrimeiraChamada($faber['Candidato']['numero_inscricao'], $faber['Candidato']['ano'], 1))
								{
									$num_candidatos_apro_1_ano++;
									$count++;
								}
							}
						}
						else
						{
							//echo '!!erro ao aprovar o candidato!!';
							$erro = 1;
						}
				}
			}
		}
		//echo 'adicionados:'.$count.';<br/>';
		//------- selecionando os candidatos preferenciais: ordenados por nota -----------------------
		$melhores = $this->Candidato->getCandidatosPorNotaProva($ano_fase, $turma);
		//echo 'numero de melhores:'.sizeOf($melhores).';';
		if($this->Candidato->getCandidatosPorNotaProvaCount($ano_fase, $turma)>0)
		{
			//agora para adicionar os candidatos, devemos verificar se os candidatos ja estão na lista,
			//para não adicionar novamente
			$count = 0;
			$pode_adicionar = true;
			foreach($melhores as $candidato)
			{
				if($num_candidatos_apro_1_ano < $num_total_1_ano)
				{
						if($this->Candidato->setCandidatoClassificatoria($candidato['Candidato']['numero_inscricao'], $candidato['Candidato']['ano'], 1))
						{
							if($this->Candidato->setCandidatosUltimaChamada($candidato['Candidato']['numero_inscricao'], $candidato['Candidato']['ano'], 1))
							{
								if($this->Candidato->setPrimeiraChamada($candidato['Candidato']['numero_inscricao'], $candidato['Candidato']['ano'], 1))
								{
									$num_candidatos_apro_1_ano++;
									$count++;
								}
							}
						}
						else
						{
							//echo '!!erro ao aprovar o candidato!!';
							$erro = 1;
						}
				}
			}
		}
		//echo 'adicionados:'.$count.';<br/>';
		/* ***************************************************************************************
		 * selecionando os candidatos da turma de 2 anos, igual para os da turma de 1 ano
		 * ***************************************************************************************
		 */
		$turma = 2;
		$num_indigenas = $criterios['CriteriosDaFaseClassificatoria']['total_vagas_indigenas_um_ano'];
		$num_afro = $criterios['CriteriosDaFaseClassificatoria']['total_vagas_afro_um_ano'];
		$num_faber = $criterios['CriteriosDaFaseClassificatoria']['total_vagas_faber_um_ano'];
		//------- selecionando os candidatos preferenciais: indigenas -----------------------
		$indigenas = $this->Candidato->getCandidatosIndigenas($ano_fase, $turma);
		//echo 'numero de indigenas:'.sizeOf($indigenas).';';
		if($this->Candidato->getCandidatosIndigenasCount($ano_fase, $turma)>0)
		{
			$count = 0;
			foreach($indigenas as $indio)
			{
				if($count < $num_indigenas & $num_candidatos_apro_2_anos <= $num_total_2_anos)
				{
					if($this->Candidato->setCandidatoClassificatoria($indio['Candidato']['numero_inscricao'], $indio['Candidato']['ano'], 1))
					{
						if($this->Candidato->setCandidatosUltimaChamada($indio['Candidato']['numero_inscricao'], $indio['Candidato']['ano'], 1))
						{
							if($this->Candidato->setPrimeiraChamada($indio['Candidato']['numero_inscricao'], $indio['Candidato']['ano'], 1))
							{
								$num_candidatos_apro_2_anos++;
								$count++;
							}
						}
					}
					else
					{
						//echo '!!erro ao aprovar o candidato!!';
						$erro = 1;
					}
				}
			}
		}
		//echo 'adicionados:'.$count.';<br/>';
		//------- selecionando os candidatos preferenciais: afro-descendentes -----------------------
		$afros = $this->Candidato->getCandidatosAfrodescendentes($ano_fase, $turma);
		//echo 'numero de afros:'.sizeOf($afros).';';
		if($this->Candidato->getCandidatosAfrodescendentesCount($ano_fase, $turma)>0)
		{
			//agora para adicionar os candidatos, devemos verificar se os candidatos ja estão na lista,
			//para não adicionar novamente
			$count = 0;
			$pode_adicionar = true;
			foreach($afros as $afro)
			{
				if($count < $num_afro & $num_candidatos_apro_2_anos <= $num_total_2_anos)
				{
						if($this->Candidato->setCandidatoClassificatoria($afro['Candidato']['numero_inscricao'], $afro['Candidato']['ano'], 1))
						{
							if($this->Candidato->setCandidatosUltimaChamada($afro['Candidato']['numero_inscricao'], $afro['Candidato']['ano'], 1))
							{
								if($this->Candidato->setPrimeiraChamada($afro['Candidato']['numero_inscricao'], $afro['Candidato']['ano'], 1))
								{
									$num_candidatos_apro_2_anos++;
									$count++;
								}
							}
						}
						else
						{
							//echo '!!erro ao aprovar o candidato!!';
							$erro = 1;
						}					
				}
			}
		}
		//echo 'adicionados:'.$count.';<br/>';
		//------- selecionando os candidatos preferenciais: faber -----------------------
		$fabers = $this->Candidato->getCandidatosFabers($ano_fase, $turma);
		//echo 'numero de fabers:'.sizeOf($fabers).';';
		if($this->Candidato->getCandidatosFabers($ano_fase, $turma)>0)
		{
			//agora para adicionar os candidatos, devemos verificar se os candidatos ja estão na lista,
			//para não adicionar novamente
			$count = 0;
			$pode_adicionar = true;
			foreach($fabers as $faber)
			{
				if($count < $num_faber & $num_candidatos_apro_2_anos <= $num_total_2_anos)
				{
						if($this->Candidato->setCandidatoClassificatoria($faber['Candidato']['numero_inscricao'], $faber['Candidato']['ano'], 1))
						{
							if($this->Candidato->setCandidatosUltimaChamada($faber['Candidato']['numero_inscricao'], $faber['Candidato']['ano'], 1))
							{
								if($this->Candidato->setPrimeiraChamada($faber['Candidato']['numero_inscricao'], $faber['Candidato']['ano'], 1))
								{
									$num_candidatos_apro_2_anos++;
									$count++;
								}	
							}
						}
						else
						{
							//echo '!!erro ao aprovar o candidato!!';
							$erro = 1;
						}
				}
			}
		}
		//echo 'adicionados:'.$count.';<br/>';
		//------- selecionando os candidatos preferenciais: ordenados por nota -----------------------
		$melhores = $this->Candidato->getCandidatosPorNotaProva($ano_fase, $turma);
		//echo 'numero de melhores:'.sizeOf($melhores).';';
		if($this->Candidato->getCandidatosPorNotaProvaCount($ano_fase, $turma)>0)
		{
			//agora para adicionar os candidatos, devemos verificar se os candidatos ja estão na lista,
			//para não adicionar novamente
			$count = 0;
			$pode_adicionar = true;
			foreach($melhores as $candidato)
			{
				if($num_candidatos_apro_2_anos < $num_total_2_anos)
				{
					if($this->Candidato->setCandidatoClassificatoria($candidato['Candidato']['numero_inscricao'], $candidato['Candidato']['ano'], 1))
					{
						if($this->Candidato->setCandidatosUltimaChamada($candidato['Candidato']['numero_inscricao'], $candidato['Candidato']['ano'], 1))
						{
							if($this->Candidato->setPrimeiraChamada($candidato['Candidato']['numero_inscricao'], $candidato['Candidato']['ano'], 1))
							{
								$num_candidatos_apro_2_anos++;
								$count++;
							}
						}
					}
					else
					{
						//echo '!!erro ao aprovar o candidato!!';
						$erro = 1;
					}
				}
			}
		}
		if($erro == 1)
		{
			//desfazer os candidatos aprovados.
			$candidatos_aprovados = $this->Candidato->getCandidatosUltimaChamada($ano_fase);		
			foreach($candidatos_aprovados as $candidato)
			{
				$this->Candidato->setCandidatosUltimaChamada($candidato['Candidato']['numero_inscricao'], $candidato['Candidato']['ano'], 0);
			}
			$candidatos_aprovados = $this->Candidato->getCandidatosAprovados($ano_fase);
			foreach($candidatos_aprovados as $candidato)
			{
				$this->Candidato->setCandidatoClassificatoria($candidato['Candidato']['numero_inscricao'], $candidato['Candidato']['ano'], 0);
			}
			$candidatos_aprovados = $this->Candidato->getCandidatosPrimeraChamada($ano_fase);
			foreach($candidatos_aprovados as $candidato)
			{
				$this->Candidato->setPrimeiraChamada($candidato['Candidato']['numero_inscricao'], $candidato['Candidato']['ano'], 0);
			}
			$this->Session->setFlash('Ocorreu um erro ao aprovar o candidato, por favor notificar os técnicos');
			$this->render('branco');
		}
	}
	function buscar_aprovados_proxima_chamada($ano_fase) 
            { 
            //para as proximas chamadas, e como não é necessário para salvar em que fase cada um passou, 
                    //somente é completada os alunos do ano selecionado. 
            //verificar se a quantidades de alunos do ano corrente esta batendo com o total, 
            //caso contrario, selecionar mais candidatos 
            $this->ProcessoSeletivo = new ProcessoSeletivo(); 
            $this->Candidato = new Candidato(); 
            $processo_seletivo_id = $this->ProcessoSeletivo->obterId($ano_fase); 
                //antes de fazer a seleção, deve ser limpa a lista de candidatos registrados na ultima
                //chamada, para que o sistema crie a nova lista agora.
                $candidatos_aprovados = $this->Candidato->getCandidatosUltimaChamada($ano_fase);		
                    foreach($candidatos_aprovados as $candidato)
                    {
                            $this->Candidato->setCandidatosUltimaChamada($candidato['Candidato']['numero_inscricao'], $candidato['Candidato']['ano'], 0);
                    }
                    //variavel 'erro'. Se algo der errado, o sistema deve desfazer as aprovações nos candidatos. 
                    $erro = 0;
                    //iniciar a seleção dos candidatos que pertenserão a ultima chamada.
            $criterios = $this->CriteriosDaFaseClassificatoria->getCriterios($processo_seletivo_id); 
            $num_total_1_ano = $criterios['CriteriosDaFaseClassificatoria']['total_vagas_um_ano']; 
            $num_total_2_anos = $criterios['CriteriosDaFaseClassificatoria']['total_vagas_dois_anos']; 
            $num_candidatos_novos_apro_1_ano = 0; 
            $num_candidatos_novos_apro_2_anos = 0; 
            //--- TURMA DE 1 ANO ---
            //verificar agora quantos candidatos que não foram matriculados.
                $num_candidatos_para_aprovar = $num_total_1_ano - $this->Candidato->getCountCandidatosMatriculados($ano_fase, '1');
            if($num_candidatos_para_aprovar > 0) 
            { 
                    //buscar mais candidatos para criar a nova lista de chamada 
                    $novos_candidatos = $this->Candidato->getCandidatoPorNotaNaoAprovado($ano_fase, '1');
                    echo count($novos_candidatos);
                    die();
                    if(count($novos_candidatos)>0)
                    {
                            foreach($novos_candidatos as $candidato)
                            {
                                    //adiciona candidatos até completar o número de vagas existentes
                                    if($num_candidatos_novos_apro_1_ano < $num_candidatos_para_aprovar)
                                    {
                                            if($this->Candidato->setCandidatosUltimaChamada($candidato['Candidato']['numero_inscricao'], $candidato['Candidato']['ano'], 1))
                                            {
                                                    if($this->Candidato->setCandidatoClassificatoria($candidato['Candidato']['numero_inscricao'], $candidato['Candidato']['ano'], 1))
                                                    {
                                                            $num_candidatos_novos_apro_1_ano++;
                                                    }
                                                    else
                                                    {
                                                            $erro = 1;
                                                    }
                                            }
                                            else
                                            {
                                                    $erro = 1;
                                            }
                                    }
                            }
                    }
            } 
            //--- TURMA DE 2 ANO ---
            //verificar agora quantos candidatos que não foram matriculados.
                $num_candidatos_para_aprovar = $num_total_2_anos - $this->Candidato->getCountCandidatosMatriculados($ano_fase, '2');
            if($num_candidatos_para_aprovar > 0) 
            { 
                    //buscar mais candidatos para criar a nova lista de chamada 
                    $novos_candidatos = $this->Candidato->getCandidatoPorNotaNaoAprovado($ano_fase, '2');
                    if($this->Candidato->getCandidatoPorNotaNaoAprovadoCount($ano_fase, '2')>0)
                    {
                            foreach($novos_candidatos as $candidato)
                            {
                                    //adiciona candidatos até completar o número de vagas existentes
                                    if($num_candidatos_novos_apro_2_anos < $num_candidatos_para_aprovar)
                                    {
                                            if($this->Candidato->setCandidatosUltimaChamada($candidato['Candidato']['numero_inscricao'], $candidato['Candidato']['ano'], 1))
                                            {
                                                    if($this->Candidato->setCandidatoClassificatoria($candidato['Candidato']['numero_inscricao'], $candidato['Candidato']['ano'], 1))
                                                    {
                                                            $num_candidatos_novos_apro_2_anos++;
                                                    }
                                                    else
                                                    {
                                                            $erro = 1;
                                                    }
                                            }
                                            else
                                            {
                                                    $erro = 1;
                                            }
                                    }
                            }
                    }
            }
            if($erro == 1)
            {
                    //erro durante o processo. Não aprovar os que foram aprovados
                        $candidatos_aprovados = $this->Candidato->getCandidatosUltimaChamada($ano_fase);		
                            foreach($candidatos_aprovados as $candidato)
                            {
                                    $this->Candidato->setCandidatosUltimaChamada($candidato['Candidato']['numero_inscricao'], $candidato['Candidato']['ano'], 0);
                                    $this->Candidato->setCandidatoClassificatoria($candidato['Candidato']['numero_inscricao'], $candidato['Candidato']['ano'], 0);
                            }
            }
        } 
	function beforeFilter() {
		parent::beforeFilter(); 
		$this->set('moduloAtual', 'candidatos');
	}	
        function listar_candidatos($ano, $turma)
	{
		$this->set('content_title', 'Lista de aprovados nas fases eliminatória e classificatória');
		$condicao = array('Candidato.ano' => $ano, 'Candidato.turma' => $turma);
		$this->paginate = array('order' => array('Candidato.ano' => 'desc',
                                                                         'Candidato.numero_inscricao' => 'asc'),
						        'recursive' => '0',
                                                        'fields' => array('numero_inscricao', 'ano', 'nome',
                                                                         'fase_eliminatoria_status', 'fase_classificatoria_status', 'fase_eliminatoria_social_status', 'fase_eliminatoria_economico_status', 'pontuacao_social', 'pontuacao_economica', 'nota_prova'),
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
																	   'fields' => array('candidato_id', 'nota_prova', 'fase_eliminatoria_status', 'fase_classificatoria_status')));
					if ($candidato['Candidato']['nota_prova'] != $valor['nota_prova']){
						$mudou = true;
						$candidato['Candidato']['nota_prova'] = $valor['nota_prova'];
					}
					if ($candidato['Candidato']['fase_eliminatoria_status'] != $valor['fase_eliminatoria_status']){
						$mudou = true;
						$candidato['Candidato']['fase_eliminatoria_status'] = $valor['fase_eliminatoria_status'];
					}	
					if ($candidato['Candidato']['fase_classificatoria_status'] != $valor['fase_classificatoria_status']){
						$mudou = true; 
						$candidato['Candidato']['fase_classificatoria_status'] = $valor['fase_classificatoria_status'];
					}
                    //validação
                    if($candidato['Candidato']['fase_classificatoria_status'] == 1){
                        $candidato['Candidato']['fase_eliminatoria_status'] = 1;
                        $candidato['Candidato']['fase_eliminatoria_social_status'] = 1;
                        $candidato['Candidato']['fase_eliminatoria_economico_status'] = 1;
                    }
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