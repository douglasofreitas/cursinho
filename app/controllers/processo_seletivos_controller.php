<?php 
/**
 * Classe correspondente ao Módulo Candidatos
 */
class ProcessoSeletivosController extends AppController {

	//var $scaffold;

	var $layout = 'tricolor_layout';
	var $pageTitle = 'coordenador';
	var $paginate;

	function index()
    {
        $this->set('content_title', 'Processos Seletivos');

        $processos_seletivos = $this->ProcessoSeletivo->find('all', array('order' => 'ProcessoSeletivo.processo_seletivo_id DESC'));

        $this->set('processos_seletivos', $processos_seletivos);
    }

	function view($processo_seletivo_id)
	{
		$this->set('content_title', 'Processos Seletivos');

		$processo_seletivo = $this->ProcessoSeletivo->read(null, $processo_seletivo_id);

        //obter dados dos status do processo seletivo
        $this->loadModel('Candidato');
        $this->loadModel('Candidato');

        $num_candidatos_1 = $this->Candidato->countCandidatos($processo_seletivo['ProcessoSeletivo']['ano'], 1);
        $num_questionarios_1 = $this->Candidato->countCandidatosQuestionario($processo_seletivo['ProcessoSeletivo']['ano'], 1);
        $num_fase_aliminatoria_1 = $this->Candidato->countCandidatosFaseEliminatoria($processo_seletivo['ProcessoSeletivo']['ano'], 1);
        $num_provas_1 = $this->Candidato->countCandidatosProva($processo_seletivo['ProcessoSeletivo']['ano'], 1);
        $num_aprovados_1 = $this->Candidato->countCandidatosAprovados($processo_seletivo['ProcessoSeletivo']['ano'], 1);
        $num_matriculados_1 = $this->Candidato->countCandidatosMatriculados($processo_seletivo['ProcessoSeletivo']['ano'], 1);

        $num_candidatos_2 = $this->Candidato->countCandidatos($processo_seletivo['ProcessoSeletivo']['ano'], 2);
        $num_questionarios_2 = $this->Candidato->countCandidatosQuestionario($processo_seletivo['ProcessoSeletivo']['ano'], 2);
        $num_fase_aliminatoria_2 = $this->Candidato->countCandidatosFaseEliminatoria($processo_seletivo['ProcessoSeletivo']['ano'], 2);
        $num_provas_2 = $this->Candidato->countCandidatosProva($processo_seletivo['ProcessoSeletivo']['ano'], 2);
        $num_aprovados_2 = $this->Candidato->countCandidatosAprovados($processo_seletivo['ProcessoSeletivo']['ano'], 2);
        $num_matriculados_2 = $this->Candidato->countCandidatosMatriculados($processo_seletivo['ProcessoSeletivo']['ano'], 2);

        $this->set('num_candidatos_1', $num_candidatos_1 );
        $this->set('num_questionarios_1', $num_questionarios_1 );
        $this->set('num_fase_aliminatoria_1', $num_fase_aliminatoria_1 );
        $this->set('num_provas_1', $num_provas_1 );
        $this->set('num_aprovados_1', $num_aprovados_1 );
        $this->set('num_matriculados_1', $num_matriculados_1 );

        $this->set('num_candidatos_2', $num_candidatos_2 );
        $this->set('num_questionarios_2', $num_questionarios_2 );
        $this->set('num_fase_aliminatoria_2', $num_fase_aliminatoria_2 );
        $this->set('num_provas_2', $num_provas_2 );
        $this->set('num_aprovados_2', $num_aprovados_2 );
        $this->set('num_matriculados_2', $num_matriculados_2 );

        $this->set('processo_seletivo', $processo_seletivo );
	}

	function adicionar_processo_seletivo()
	{
		$this->set('content_title', 'Adicionar novo processo seletivo');

		if (!empty($this->data))
		{
			$this->ProcessoSeletivo->set($this->data);

			if ($this->ProcessoSeletivo->save())
			{
				//$this->Session->setFlash('Processo seletivo adicionado');

				$processo_seletivo_id = $this->ProcessoSeletivo->obterId($this->data['ProcessoSeletivo']['ano']);
				$this->redirect('/configuracao_processo_seletivos/adicionar_configuracao/' . $processo_seletivo_id);
			}
		}

        $this->render('processo_seletivo');
	}

    function edit($id)
    {
        $this->set('content_title', 'Editar processo seletivo');

        if (!empty($this->data))
        {
            $this->ProcessoSeletivo->set($this->data);

            if ($this->ProcessoSeletivo->save())
            {
                //$this->Session->setFlash('Processo seletivo adicionado');

                $processo_seletivo_id = $this->ProcessoSeletivo->obterId($this->data['ProcessoSeletivo']['ano']);
                $this->redirect('/configuracao_processo_seletivos/edit/' . $processo_seletivo_id);
            }
        }

        $this->data = $this->ProcessoSeletivo->read(null, $id);

        $this->render('processo_seletivo');
    }

}
?>
