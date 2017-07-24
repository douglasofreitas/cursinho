<?php
/**
 * Classe correspondente ao Módulo Candidatos
 */
class ConfiguracaoProcessoSeletivosController extends AppController {

	//var $scaffold;

	var $layout = 'tricolor_layout';
	var $pageTitle = 'coordenador';
	var $paginate;

	function index()
	{

	}
	function visualizar_configuracao()
	{
		$this->set('content_title', 'Configurações do sistema');

		$configuracoes = $this->ConfiguracaoProcessoSeletivo->find('all');

		print_r($configuracoes);
	}

	function adicionar_configuracao($processo_seletivo_id = null)
	{
		$this->set('content_title', 'Definir configurações para o processo seletivo');


        //verifica se já existe configuração
        $config = $this->ConfiguracaoProcessoSeletivo->find('first', array('conditions' => array('ConfiguracaoProcessoSeletivo.processo_seletivo_id' => $processo_seletivo_id)));
        if(!empty($config)){
            $this->redirect('/configuracao_processo_seletivos/edit/'.$processo_seletivo_id );
        }

		if (!empty($this->data))
		{
            if (!empty($this->data['ConfiguracaoProcessoSeletivo']['valor_inscricao'])) {
                $this->data['ConfiguracaoProcessoSeletivo']['valor_inscricao'] = $this->valorFormatBeforeSave($this->data['ConfiguracaoProcessoSeletivo']['valor_inscricao']);
            }
            if (!empty($this->data['ConfiguracaoProcessoSeletivo']['valor_salario_minimo'])) {
                $this->data['ConfiguracaoProcessoSeletivo']['valor_salario_minimo'] = $this->valorFormatBeforeSave($this->data['ConfiguracaoProcessoSeletivo']['valor_salario_minimo']);
            }
            if (!empty($this->data['ConfiguracaoProcessoSeletivo']['data_prova'])) {
                $this->data['ConfiguracaoProcessoSeletivo']['data_prova'] = $this->dateFormatBeforeSave($this->data['ConfiguracaoProcessoSeletivo']['data_prova']);
            }
            if (!empty($this->data['ConfiguracaoProcessoSeletivo']['data_questionario'])) {
                $this->data['ConfiguracaoProcessoSeletivo']['data_questionario'] = $this->dateFormatBeforeSave($this->data['ConfiguracaoProcessoSeletivo']['data_questionario']);
            }
            if (!empty($this->data['ConfiguracaoProcessoSeletivo']['data_limite_pagamento'])) {
                $this->data['ConfiguracaoProcessoSeletivo']['data_limite_pagamento'] = $this->dateFormatBeforeSave($this->data['ConfiguracaoProcessoSeletivo']['data_limite_pagamento']);
            }
            if (!empty($this->data['ConfiguracaoProcessoSeletivo']['inscricao_online_inicio'])) {
                $this->data['ConfiguracaoProcessoSeletivo']['inscricao_online_inicio'] = $this->dateFormatBeforeSave($this->data['ConfiguracaoProcessoSeletivo']['inscricao_online_inicio']);
            }
            if (!empty($this->data['ConfiguracaoProcessoSeletivo']['inscricao_online_fim'])) {
                $this->data['ConfiguracaoProcessoSeletivo']['inscricao_online_fim'] = $this->dateFormatBeforeSave($this->data['ConfiguracaoProcessoSeletivo']['inscricao_online_fim']);
            }

            if(!empty($this->data['ConfiguracaoProcessoSeletivo']['inscricao_online']))
                $this->data['ConfiguracaoProcessoSeletivo']['inscricao_online'] = 1;
            else
                $this->data['ConfiguracaoProcessoSeletivo']['inscricao_online'] = 0;
            if(!empty($this->data['ConfiguracaoProcessoSeletivo']['ativo']))
                $this->data['ConfiguracaoProcessoSeletivo']['ativo'] = 1;
            else
                $this->data['ConfiguracaoProcessoSeletivo']['ativo'] = 0;

            $this->data['ConfiguracaoProcessoSeletivo']['forma_pagamento'] = json_encode($this->data['ConfiguracaoProcessoSeletivo']['forma_pagamento']);


			if ($this->ConfiguracaoProcessoSeletivo->save($this->data))
			{
                $this->Session->setFlash('Configuração salva');
                $this->redirect('/processo_seletivos/view/'.$processo_seletivo_id);
			}
		}
		else
		{
			$this->set('processo_seletivo_id', $processo_seletivo_id);
		}

        $this->render('configuracao');
	}

    function edit($processo_seletivo_id = null)
    {
        $this->set('content_title', 'Definir configurações para o processo seletivo');

        if (!empty($this->data))
        {
            $this->data['ConfiguracaoProcessoSeletivo']['processo_seletivo_id'] = $processo_seletivo_id;

            if (!empty($this->data['ConfiguracaoProcessoSeletivo']['valor_inscricao'])) {
                $this->data['ConfiguracaoProcessoSeletivo']['valor_inscricao'] = $this->valorFormatBeforeSave($this->data['ConfiguracaoProcessoSeletivo']['valor_inscricao']);
            }
            if (!empty($this->data['ConfiguracaoProcessoSeletivo']['valor_salario_minimo'])) {
                $this->data['ConfiguracaoProcessoSeletivo']['valor_salario_minimo'] = $this->valorFormatBeforeSave($this->data['ConfiguracaoProcessoSeletivo']['valor_salario_minimo']);
            }
            if (!empty($this->data['ConfiguracaoProcessoSeletivo']['data_prova'])) {
                $this->data['ConfiguracaoProcessoSeletivo']['data_prova'] = $this->dateFormatBeforeSave($this->data['ConfiguracaoProcessoSeletivo']['data_prova']);
            }
            if (!empty($this->data['ConfiguracaoProcessoSeletivo']['data_questionario'])) {
                $this->data['ConfiguracaoProcessoSeletivo']['data_questionario'] = $this->dateFormatBeforeSave($this->data['ConfiguracaoProcessoSeletivo']['data_questionario']);
            }
            if (!empty($this->data['ConfiguracaoProcessoSeletivo']['data_limite_pagamento'])) {
                $this->data['ConfiguracaoProcessoSeletivo']['data_limite_pagamento'] = $this->dateFormatBeforeSave($this->data['ConfiguracaoProcessoSeletivo']['data_limite_pagamento']);
            }
            if (!empty($this->data['ConfiguracaoProcessoSeletivo']['inscricao_online_inicio'])) {
                $this->data['ConfiguracaoProcessoSeletivo']['inscricao_online_inicio'] = $this->dateFormatBeforeSave($this->data['ConfiguracaoProcessoSeletivo']['inscricao_online_inicio']);
            }
            if (!empty($this->data['ConfiguracaoProcessoSeletivo']['inscricao_online_fim'])) {
                $this->data['ConfiguracaoProcessoSeletivo']['inscricao_online_fim'] = $this->dateFormatBeforeSave($this->data['ConfiguracaoProcessoSeletivo']['inscricao_online_fim']);
            }


            if(!empty($this->data['ConfiguracaoProcessoSeletivo']['inscricao_online']))
                $this->data['ConfiguracaoProcessoSeletivo']['inscricao_online'] = 1;
            else
                $this->data['ConfiguracaoProcessoSeletivo']['inscricao_online'] = 0;
            if(!empty($this->data['ConfiguracaoProcessoSeletivo']['ativo']))
                $this->data['ConfiguracaoProcessoSeletivo']['ativo'] = 1;
            else
                $this->data['ConfiguracaoProcessoSeletivo']['ativo'] = 0;

            $array_forma_pagamento = array();
            foreach($this->data['ConfiguracaoProcessoSeletivo']['forma_pagamento'] as $index => $item){
                array_push($array_forma_pagamento, $item );
            }
            $this->data['ConfiguracaoProcessoSeletivo']['forma_pagamento'] = json_encode($array_forma_pagamento) ;

            $this->ConfiguracaoProcessoSeletivo->set($this->data);

            if ($this->ConfiguracaoProcessoSeletivo->save())
            {
                $this->Session->setFlash('Configuração salva');
                $this->redirect('/processo_seletivos/view/'.$processo_seletivo_id);
            }
        }
        else
        {
            $this->set('processo_seletivo_id', $processo_seletivo_id);
        }

        $this->set('processo_seletivo_id', $processo_seletivo_id);
        $this->data = $this->ConfiguracaoProcessoSeletivo->find('first', array('conditions' => array('ConfiguracaoProcessoSeletivo.processo_seletivo_id' => $processo_seletivo_id)));

        $this->render('configuracao');
    }
}

?>
