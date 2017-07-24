<?php
/**
 * Classe correspondente ao Módulo Candidatos
 */
class CriteriosDaFaseEliminatoriasController extends AppController {
	//var $scaffold;
	var $layout = 'tricolor_layout';
	var $pageTitle = 'candidatos';
	var $paginate;
	var $helpers = array('Chart');
	var $uses = array('CriteriosDaFaseEliminatoria', 'ProcessoSeletivo', 'Candidato');

	function index()
	{
	}
	function iniciar()
	{
		$this->set('content_title', 'Fase Eliminatória');
		if (empty($this->data))
		{
			// Mostra o formulário para informar o ano do processo seletivo
		}
		else
		{
			//App::import('Model', 'ProcessoSeletivo');
			$this->ProcessoSeletivo = new ProcessoSeletivo();

			$ano = $this->data['CriteriosDaFaseEliminatoria']['ano_fase'];

			if ($this->ProcessoSeletivo->existe($ano))
			{
				//$this->redirect('/criterios_da_fase_eliminatorias/visualizar_status/' . $ano);
				$this->set('ano', $ano);
				$this->set('content_title', 'Fase Eliminatória do Processo Seletivo ' . $ano);
				$this->render('selecionar_turma');
			}
			else
			{
				$this->Session->setFlash('Não há um processo seletivo para o ano informado.');
			}
		}
	}
	function visualizar_status($ano, $turma)
	{
		if ($turma == 1)
			$nome_turma = 'Turma de 1 ano';
		else if ($turma == 2)
			$nome_turma = 'Turma de 2 anos';

		$this->set('content_title', 'Fase Eliminatória do Processo Seletivo ' . $ano . ' - ' . $nome_turma);
		//App::import('Model', 'ProcessoSeletivo');
		$this->ProcessoSeletivo = new ProcessoSeletivo();
		$processo_seletivo_id = $this->ProcessoSeletivo->obterId($ano);
		//echo "Processo_id: ".$processo_seletivo_id;

		if ($processo_seletivo_id)
		{
			if (!$this->CriteriosDaFaseEliminatoria->existeCriterio($processo_seletivo_id))
			{
				$dados['CriteriosDaFaseEliminatoria']['processo_seletivo_id'] = $processo_seletivo_id;
				$this->CriteriosDaFaseEliminatoria->save($dados);
				$nova_fase_eliminatoria = true;
			}
			else
				$nova_fase_eliminatoria = false;

			$condicao = array('CriteriosDaFaseEliminatoria.processo_seletivo_id' => $processo_seletivo_id);
			$criterios = $this->CriteriosDaFaseEliminatoria->find('first', array('conditions' => $condicao));

			$temp = $criterios['CriteriosDaFaseEliminatoria'];

			//if (empty($temp['pontuacao_social_minima_um_ano']) || empty($temp['pontuacao_social_maxima_um_ano']) ||
				//empty($temp['pontuacao_economica_minima_um_ano']) || empty($temp['pontuacao_economica_maxima_um_ano']))

			if ($temp['pontuacao_social_minima_um_ano'] == '' || $temp['pontuacao_social_maxima_um_ano'] == '' ||
				$temp['pontuacao_economica_minima_um_ano'] == '' || $temp['pontuacao_economica_maxima_um_ano'] == '')
				{
					$nova_fase_turma_1ano = true;
				}

			//if (empty($temp['pontuacao_social_minima_dois_anos']) || empty($temp['pontuacao_social_maxima_dois_anos']) ||
				//empty($temp['pontuacao_economica_minima_dois_anos']) || empty($temp['pontuacao_economica_maxima_dois_anos']))

			if ($temp['pontuacao_social_minima_dois_anos'] == '' || $temp['pontuacao_social_maxima_dois_anos'] == '' ||
				$temp['pontuacao_economica_minima_dois_anos'] == '' || $temp['pontuacao_economica_maxima_dois_anos'] == '')
				{
					$nova_fase_turma_2anos = true;
				}

			$this->set('criterios', $criterios);
			$this->set('nova_fase', $nova_fase_eliminatoria);

			if ($turma == 1)
			{
				if (isset($nova_fase_turma_1ano))
					$this->redirect('/criterios_da_fase_eliminatorias/definir_criterios_turma_1ano' . '/' . $criterios['CriteriosDaFaseEliminatoria']['criterios_da_fase_eliminatoria_id']);
				else
					$this->render('fase_eliminatoria_turma_1ano');
			}
			else if ($turma == 2)
			{
				if (isset($nova_fase_turma_2anos))
					$this->redirect('/criterios_da_fase_eliminatorias/definir_criterios_turma_2anos' . '/' . $criterios['CriteriosDaFaseEliminatoria']['criterios_da_fase_eliminatoria_id']);
				else
					$this->render('fase_eliminatoria_turma_2anos');
			}
		}
	}
	function definir_criterios_turma_1ano($fase_eliminatoria_id)
	{
		$ano = $this->CriteriosDaFaseEliminatoria->obterAno($fase_eliminatoria_id);

		$this->set('content_title', 'Fase Eliminatória do Processo Seletivo ' . $ano . ' - Turma de 1 ano');
		$this->set('fase_eliminatoria_id', $fase_eliminatoria_id);

		if (!empty($this->data))
		{
			$this->data['CriteriosDaFaseEliminatoria']['criterios_da_fase_eliminatoria_id'] = $fase_eliminatoria_id;

			$this->CriteriosDaFaseEliminatoria->set($this->data);

			if ($this->CriteriosDaFaseEliminatoria->save($this->data))
			{
				$this->redirect('/criterios_da_fase_eliminatorias/resultados_turma_1ano/' . $fase_eliminatoria_id);
			}
		}
		else
		{
			$this->CriteriosDaFaseEliminatoria->id = $fase_eliminatoria_id;
			$this->data = $this->CriteriosDaFaseEliminatoria->read();
		}
	}

	function definir_criterios_turma_2anos($fase_eliminatoria_id)
	{
		$this->set('fase_eliminatoria_id', $fase_eliminatoria_id);

		if (!empty($this->data))
		{
			$this->data['CriteriosDaFaseEliminatoria']['criterios_da_fase_eliminatoria_id'] = $fase_eliminatoria_id;

			$this->CriteriosDaFaseEliminatoria->set($this->data);

			if ($this->CriteriosDaFaseEliminatoria->save($this->data))
			{
				$this->redirect('/criterios_da_fase_eliminatorias/resultados_turma_2anos/' . $fase_eliminatoria_id);
			}
		}
		else
		{
			$this->CriteriosDaFaseEliminatoria->id = $fase_eliminatoria_id;
			$this->data = $this->CriteriosDaFaseEliminatoria->read();
		}
	}
	function resultados_turma_1ano($fase_eliminatoria_id)
	{
		$this->set('content_title', 'Resultados da Fase Eliminatória - Turma de 1 ano');

		//App::import('Model', 'Candidato');
		$this->Candidato = new Candidato();

		$criterios = $this->CriteriosDaFaseEliminatoria->find('first', array('conditions' => 
			array('CriteriosDaFaseEliminatoria.criterios_da_fase_eliminatoria_id' => $fase_eliminatoria_id)));

		$this->Candidato->query('UPDATE candidato SET fase_eliminatoria_status = 0 WHERE candidato.processo_seletivo_id = '
			.  $criterios['CriteriosDaFaseEliminatoria']['processo_seletivo_id'] . ' AND turma = 1');

		$condicao = array('Candidato.pontuacao_social >=' => $criterios['CriteriosDaFaseEliminatoria']['pontuacao_social_minima_um_ano'],
						  'Candidato.pontuacao_social <=' => $criterios['CriteriosDaFaseEliminatoria']['pontuacao_social_maxima_um_ano'],
						  'Candidato.pontuacao_economica >=' => $criterios['CriteriosDaFaseEliminatoria']['pontuacao_economica_minima_um_ano'],
						  'Candidato.pontuacao_economica <=' => $criterios['CriteriosDaFaseEliminatoria']['pontuacao_economica_maxima_um_ano'],
						  'Candidato.turma' => '1',
						  'Candidato.ano' => $this->CriteriosDaFaseEliminatoria->obterAno($fase_eliminatoria_id));

		$this->Session->write('Candidatos.relatorio.condicao', $condicao);
		$this->Session->write('Candidatos.relatorio.nomeArquivo', 'resultado_fase_eliminatoria_turma1ano.xls');

		$this->paginate = array('limit' => 30, 'order' => array('Candidato.numero_inscricao' => 'asc'),
								'recursive' => '0');
		$candidatos = $this->paginate('Candidato', $condicao);
		$this->set('candidatos', $candidatos);
		$this->set('fase_eliminatoria_id', $fase_eliminatoria_id);

		$candidatos = $this->Candidato->find('all', array('conditions' => $condicao, 'recursive' => '0', 'fields' => 'fase_eliminatoria_status'));

		foreach ($candidatos as $candidato)
		{
			$this->Candidato->id = $candidato['Candidato']['candidato_id'];
			$candidato['Candidato']['fase_eliminatoria_status'] = '1';

			$this->Candidato->save($candidato);
		}

		$this->redirect('visualizar_status/' . $this->CriteriosDaFaseEliminatoria->obterAno($fase_eliminatoria_id) . '/1');
	}

	function resultados_turma_2anos($fase_eliminatoria_id)
	{
		$this->set('content_title', 'Resultados da Fase Eliminatória - Turma de 2 anos');

		//App::import('Model', 'Candidato');
		$this->Candidato = new Candidato();

		$criterios = $this->CriteriosDaFaseEliminatoria->find('first', array('conditions' => 
			array('CriteriosDaFaseEliminatoria.criterios_da_fase_eliminatoria_id' => $fase_eliminatoria_id)));

		$this->Candidato->query('UPDATE candidato SET fase_eliminatoria_status = 0 WHERE candidato.processo_seletivo_id = '
			.  $criterios['CriteriosDaFaseEliminatoria']['processo_seletivo_id'] . ' AND turma = 2');

		$condicao = array('Candidato.pontuacao_social >=' => $criterios['CriteriosDaFaseEliminatoria']['pontuacao_social_minima_dois_anos'],
						  'Candidato.pontuacao_social <=' => $criterios['CriteriosDaFaseEliminatoria']['pontuacao_social_maxima_dois_anos'],
						  'Candidato.pontuacao_economica >=' => $criterios['CriteriosDaFaseEliminatoria']['pontuacao_economica_minima_dois_anos'],
						  'Candidato.pontuacao_economica <=' => $criterios['CriteriosDaFaseEliminatoria']['pontuacao_economica_maxima_dois_anos'],
						  'Candidato.turma' => '2',
						  'Candidato.ano' => $this->CriteriosDaFaseEliminatoria->obterAno($fase_eliminatoria_id));

		$this->Session->write('Candidatos.relatorio.condicao', $condicao);
		$this->Session->write('Candidatos.relatorio.nomeArquivo', 'resultado_fase_eliminatoria_turma2anos.xls');

		$this->paginate = array('limit' => 30, 'order' => array('Candidato.numero_inscricao' => 'asc'),
								'recursive' => '0');
		$candidatos = $this->paginate('Candidato', $condicao);
		$this->set('candidatos', $candidatos);
		$this->set('fase_eliminatoria_id', $fase_eliminatoria_id);

		$candidatos = $this->Candidato->find('all', array('conditions' => $condicao, 'recursive' => '0', 'fields' => 'fase_eliminatoria_status'));

		foreach ($candidatos as $candidato)
		{
			$this->Candidato->id = $candidato['Candidato']['candidato_id'];
			$candidato['Candidato']['fase_eliminatoria_status'] = '1';

			$this->Candidato->save($candidato);
		}

		$this->redirect('visualizar_status/' . $this->CriteriosDaFaseEliminatoria->obterAno($fase_eliminatoria_id) . '/2');
	}

	function visualizar_resultados_turma_1ano($fase_eliminatoria_id)
	{
		$this->set('content_title', 'Resultados da Fase Eliminatória - Turma de 1 ano');
		$this->set('fase_eliminatoria_id', $fase_eliminatoria_id);

		//App::import('Model', 'Candidato');
		$this->Candidato = new Candidato();

			$criterios = $this->CriteriosDaFaseEliminatoria->find('first', array('conditions' => 
				array('CriteriosDaFaseEliminatoria.criterios_da_fase_eliminatoria_id' => $fase_eliminatoria_id)));

		$condicao = array('Candidato.processo_seletivo_id' => $criterios['CriteriosDaFaseEliminatoria']['processo_seletivo_id'],
						  'Candidato.fase_eliminatoria_status' => '1',
						  'Candidato.turma' => '1');

		$this->Session->write('Candidatos.relatorio.condicao', $condicao);
		$this->Session->write('Candidatos.relatorio.nomeArquivo', 'resultado_fase_eliminatoria_turma1ano.xls');

		$this->paginate = array('limit' => 30, 'order' => array('Candidato.numero_inscricao' => 'asc'),
								'recursive' => '0');
		// Guarda na sessão a variável Candidatos.filtro com valores dos campos do formulário
                $this->Session->write('Candidatos.filtro', $condicao);
                // Redireciona para a função que cuida de exibir os resultados
                $this->redirect('/candidatos/listar_filtro');
	}

	function visualizar_resultados_turma_2anos($fase_eliminatoria_id)
	{
		$this->set('content_title', 'Resultados da Fase Eliminatória - Turma de 2 anos');
		$this->set('fase_eliminatoria_id', $fase_eliminatoria_id);

		//App::import('Model', 'Candidato');
		$this->Candidato = new Candidato();

			$criterios = $this->CriteriosDaFaseEliminatoria->find('first', array('conditions' => 
				array('CriteriosDaFaseEliminatoria.criterios_da_fase_eliminatoria_id' => $fase_eliminatoria_id)));

		$condicao = array('Candidato.processo_seletivo_id' => $criterios['CriteriosDaFaseEliminatoria']['processo_seletivo_id'],
						  'Candidato.fase_eliminatoria_status' => '1',
						  'Candidato.turma' => '2');

		$this->Session->write('Candidatos.relatorio.condicao', $condicao);
		$this->Session->write('Candidatos.relatorio.nomeArquivo', 'resultado_fase_eliminatoria_turma1ano.xls');

		$this->paginate = array('limit' => 30, 'order' => array('Candidato.numero_inscricao' => 'asc'),
								'recursive' => '0');
		// Guarda na sessão a variável Candidatos.filtro com valores dos campos do formulário
                $this->Session->write('Candidatos.filtro', $condicao);
                // Redireciona para a função que cuida de exibir os resultados
                $this->redirect('/candidatos/listar_filtro');
	}

	function adicionar_excecao_turma_1ano($fase_eliminatoria_id)
	{
		$this->set('content_title', 'Adicionar exceção');
		$this->set('fase_eliminatoria_id', $fase_eliminatoria_id);

		if (!empty($this->data))
		{
			$criterios = $this->CriteriosDaFaseEliminatoria->find('first', array('conditions' => 
				array('CriteriosDaFaseEliminatoria.criterios_da_fase_eliminatoria_id' => $fase_eliminatoria_id)));

			$condicao = array('Candidato.numero_inscricao' => $this->data['CriteriosDaFaseEliminatoria']['numero_inscricao'], 
							  'Candidato.ano' => $this->data['CriteriosDaFaseEliminatoria']['ano'],
							  'Candidato.turma' => '1',
							  'Candidato.processo_seletivo_id' => $criterios['CriteriosDaFaseEliminatoria']['processo_seletivo_id']);

			//App::import('Model', 'Candidato');
			$this->Candidato = new Candidato();

			$candidato = $this->Candidato->find('first', array('conditions' => $condicao));

			if ($candidato)
			{
				$this->Candidato->id = $candidato['Candidato']['candidato_id'];
				$candidato['Candidato']['fase_eliminatoria_status'] = '1';

				$this->Candidato->save($candidato);

				$this->Session->setFlash('Candidato adicionado à lista de aprovados');
				$this->redirect('visualizar_status/' . $candidato['Candidato']['ano'] . '/' . $candidato['Candidato']['turma']);
			}
			else
			{
				$this->Session->setFlash('O candidato informado não pertence ao processo seletivo e/ou à turma de 1 ano');
			}
		}
	}

	function adicionar_excecao_turma_2anos($fase_eliminatoria_id)
	{
		$this->set('content_title', 'Adicionar exceção');
		$this->set('fase_eliminatoria_id', $fase_eliminatoria_id);

		if (!empty($this->data))
		{
			$criterios = $this->CriteriosDaFaseEliminatoria->find('first', array('conditions' => 
				array('CriteriosDaFaseEliminatoria.criterios_da_fase_eliminatoria_id' => $fase_eliminatoria_id)));

			$condicao = array('Candidato.numero_inscricao' => $this->data['CriteriosDaFaseEliminatoria']['numero_inscricao'], 
							  'Candidato.ano' => $this->data['CriteriosDaFaseEliminatoria']['ano'],
							  'Candidato.turma' => '2',
							  'Candidato.processo_seletivo_id' => $criterios['CriteriosDaFaseEliminatoria']['processo_seletivo_id']);

			//App::import('Model', 'Candidato');
			$this->Candidato = new Candidato();

			$candidato = $this->Candidato->find('first', array('conditions' => $condicao));

			if ($candidato)
			{
				$this->Candidato->id = $candidato['Candidato']['candidato_id'];
				$candidato['Candidato']['fase_eliminatoria_status'] = '1';

				$this->Candidato->save($candidato);

				$this->Session->setFlash('Candidato adicionado à lista de aprovados');
				$this->redirect('visualizar_status/' . $candidato['Candidato']['ano'] . '/' . $candidato['Candidato']['turma']);
			}
			else
			{
				$this->Session->setFlash('O candidato informado não pertence ao processo seletivo e/ou à turma de 2 anos');
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
                                    'fase_eliminatoria_status', 'fase_classificatoria_status',
                                    'fase_eliminatoria_social_status', 'fase_eliminatoria_economico_status',
                                    'pontuacao_social', 'pontuacao_economica'),
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
																	   'fields' => array('candidato_id', 'fase_eliminatoria_status', 'fase_eliminatoria_social_status', 'fase_eliminatoria_economico_status')));

					if ($candidato['Candidato']['fase_eliminatoria_status'] != $valor['fase_eliminatoria_status']){
						$mudou = true;
						$candidato['Candidato']['fase_eliminatoria_status'] = $valor['fase_eliminatoria_status'];
					}	
					if ($candidato['Candidato']['fase_eliminatoria_social_status'] != $valor['fase_eliminatoria_social_status']){
						$mudou = true; 
						$candidato['Candidato']['fase_eliminatoria_social_status'] = $valor['fase_eliminatoria_social_status'];
					}
					if ($candidato['Candidato']['fase_eliminatoria_economico_status'] != $valor['fase_eliminatoria_economico_status']){
						$mudou = true; 
						$candidato['Candidato']['fase_eliminatoria_economico_status'] = $valor['fase_eliminatoria_economico_status'];
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
