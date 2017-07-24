<?php 
class CriteriosDaFaseClassificatoria extends AppModel {

	var $name = 'CriteriosDaFaseClassificatoria';

	var $useTable = 'criterios_da_fase_classificatoria';
	var $primaryKey = 'criterios_da_fase_classificatoria_id';

        var $belongsTo = array(
		'ProcessoSeletivo' => array(
			'className' => 'ProcessoSeletivo',
			'foreignKey' => 'processo_seletivo_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	function obterId($processo_seletivo_id)
	{
		$condicao = array('CriteriosDaFaseClassificatoria.processo_seletivo_id' => $processo_seletivo_id);

		$criterios = $this->find('first', array('conditions' => $condicao, 
												'recursive' => '0'));

		return $criterios['CriteriosDaFaseClassificatoria']['criterios_da_fase_classificatoria_id'];
	}

	function hasFaseClassificatoria($processo_seletivo_id)
	{
		$condicao = array('CriteriosDaFaseClassificatoria.processo_seletivo_id' => $processo_seletivo_id);
		if ($this->find('count', array('conditions' => $condicao, 'recursive' => '0')) == 0)
			return false;
		else
			return true;
	}

	function getCriterios($processo_seletivo_id)
	{
		$condicao = array('CriteriosDaFaseClassificatoria.processo_seletivo_id' => $processo_seletivo_id);

		$criterios = $this->find('first', array('conditions' => $condicao, 
												'recursive' => '0'));

		return $criterios;
	}

	function getTotalVagas1Ano($processo_seletivo_id)
	{
		$condicao = array('CriteriosDaFaseClassificatoria.processo_seletivo_id' => $processo_seletivo_id);

		$criterios = $this->find('first', array('conditions' => $condicao, 
												'recursive' => '0'));
		return $criterios['CriteriosDaFaseClassificatoria']['total_vagas_um_ano'];
	}

	function getTotalVagas2Anos($processo_seletivo_id)
	{
		$condicao = array('CriteriosDaFaseClassificatoria.processo_seletivo_id' => $processo_seletivo_id);

		$criterios = $this->find('first', array('conditions' => $condicao, 
												'recursive' => '0'));
		return $criterios['CriteriosDaFaseClassificatoria']['total_vagas_dois_anos'];
	}

}
?>
