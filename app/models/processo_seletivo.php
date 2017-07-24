<?php 
class ProcessoSeletivo extends AppModel {

	var $name = 'ProcessoSeletivo';

	var $useTable = 'processo_seletivo';
	var $primaryKey = 'processo_seletivo_id';

	var $hasOne = 'ConfiguracaoProcessoSeletivo';

        var $hasMany = array(
		'CriteriosDaFaseEliminatoria' => array(
			'className' => 'CriteriosDaFaseEliminatoria',
			'foreignKey' => 'processo_seletivo_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
                'CriteriosDaFaseClassificatoria' => array(
			'className' => 'CriteriosDaFaseClassificatoria',
			'foreignKey' => 'processo_seletivo_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	var $validate = array(
		'ano'			   => array('rule' => 'notEmpty',
									'message' => 'Você deve informar o ano')
	);

	function existe($ano)
	{
		$condicao = array('ProcessoSeletivo.ano' => $ano);

		$processo = $this->find('count', array('conditions' => $condicao, 
												'recursive' => '0'));

		if ($processo > 0 )
			return true;
		else
			return false;
	}

	function obterId($ano)
	{
		$condicao = array('ProcessoSeletivo.ano' => $ano);

		$processo = $this->find('first', array('conditions' => $condicao, 
												'recursive' => '0'));

		if ($processo)
			return $processo['ProcessoSeletivo']['processo_seletivo_id'];
		else
			return null;
	}

	function obterAno($processo_seletivo_id)
	{
		$condicao = array('ProcessoSeletivo.processo_seletivo_id' => $processo_seletivo_id);

		$processo = $this->find('first', array('conditions' => $condicao, 
												'recursive' => '0'));

		if ($processo)
			return $processo['ProcessoSeletivo']['ano'];
		else
			return null;
	}

    /*
     * Esta função não será mais usada pois o processo ocorre de forma livre
     */
	function faseEliminatoriaEfetuada($processo_seletivo_id)
	{
        return true;

        /*
		$condicao = array('CriteriosDaFaseEliminatoria.processo_seletivo_id' => $processo_seletivo_id );
		$processo = $this->CriteriosDaFaseEliminatoria->find('first', array('conditions' => $condicao, 
												'recursive' => '0'));

		if ($processo)
			return true;
		else
			return false;
        */
	}

    /*
     * Esta função não será mais usada pois o processo ocorre de forma livre
     */
	function faseClassificatoriaEfetuada($processo_seletivo_id)
	{

		$condicao = array('CriteriosDaFaseClassificatoria.processo_seletivo_id' => $processo_seletivo_id);

		$processo = $this->CriteriosDaFaseClassificatoria->find('first', array('conditions' => $condicao, 
												'recursive' => '0'));

		if ($processo)
			return true;
		else
			return false;
	}

    function getProcessoAtivo(){
        $condicao = array('ConfiguracaoProcessoSeletivo.ativo' => 1 );

        $processo = $this->find('first', array('conditions' => $condicao,
            'recursive' => '0'));

        if ($processo)
            return $processo;
        else
            return false;
    }

    function getProcessoAtual(){
        $condicao = array('ConfiguracaoProcessoSeletivo.ativo' => 1, 'ConfiguracaoProcessoSeletivo.inscricao_online' => 1, 'ConfiguracaoProcessoSeletivo.inscricao_online_inicio <= ' => date('Y-m-d', strtotime('now')), 'ConfiguracaoProcessoSeletivo.inscricao_online_fim >= ' => date('Y-m-d', strtotime('now')));

        $processo = $this->find('first', array('conditions' => $condicao,
            'recursive' => '0'));

        if ($processo)
            return $processo;
        else
            return false;
    }

    function getAnoProcessoAtual(){
        $condicao = array('ConfiguracaoProcessoSeletivo.ativo' => 1, 'ConfiguracaoProcessoSeletivo.inscricao_online' => 1, 'ConfiguracaoProcessoSeletivo.inscricao_online_inicio <= ' => date('Y-m-d', strtotime('now')), 'ConfiguracaoProcessoSeletivo.inscricao_online_fim >= ' => date('Y-m-d', strtotime('now')));

        $processo = $this->find('first', array('conditions' => $condicao,
            'recursive' => '0'));

        if ($processo)
            return $processo['ProcessoSeletivo']['ano'];
        else
            return false;
    }

    function getValorInscricaoAtual(){
        $condicao = array('ConfiguracaoProcessoSeletivo.ativo' => 1, 'ConfiguracaoProcessoSeletivo.inscricao_online' => 1, 'ConfiguracaoProcessoSeletivo.inscricao_online_inicio <= ' => date('Y-m-d', strtotime('now')), 'ConfiguracaoProcessoSeletivo.inscricao_online_fim >= ' => date('Y-m-d', strtotime('now')));

        $processo = $this->find('first', array('conditions' => $condicao,
            'recursive' => '0'));

        if ($processo)
            return $processo['ConfiguracaoProcessoSeletivo']['valor_inscricao'];
        else
            return false;
    }

    function getDataLimitePagamentoAtual(){
        $condicao = array('ConfiguracaoProcessoSeletivo.ativo' => 1, 'ConfiguracaoProcessoSeletivo.inscricao_online' => 1, 'ConfiguracaoProcessoSeletivo.inscricao_online_inicio <= ' => date('Y-m-d', strtotime('now')), 'ConfiguracaoProcessoSeletivo.inscricao_online_fim >= ' => date('Y-m-d', strtotime('now')));

        $processo = $this->find('first', array('conditions' => $condicao,
            'recursive' => '0'));

        if ($processo)
            return $processo['ConfiguracaoProcessoSeletivo']['data_limite_pagamento'];
        else
            return false;
    }



    function getProcessoSeletivoAberto(){
        $condicao = array('ConfiguracaoProcessoSeletivo.ativo' => 1, 'ConfiguracaoProcessoSeletivo.inscricao_online' => 1, 'ConfiguracaoProcessoSeletivo.inscricao_online_inicio <= ' => date('Y-m-d', strtotime('now')), 'ConfiguracaoProcessoSeletivo.inscricao_online_fim >= ' => date('Y-m-d', strtotime('now')));

        $processo = $this->find('first', array('conditions' => $condicao,
            'recursive' => '0'));

        if ($processo)
            return $processo;
        else
            return false;
    }

}
?>