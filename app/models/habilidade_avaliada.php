<?php 
class HabilidadeAvaliada extends AppModel {

	var $name = 'HabilidadeAvaliada';

	var $useTable = 'habilidade_avaliada';
	var $primaryKey = 'habilidade_avaliada_id';

	var $hasMany = array('QuestaoProva' => array('className' => 'QuestaoProva',
												 'foreignKey' => 'habilidade_avaliada_id'));

	function getHabilidades()
	{
		$condicao = array();
		$habilidades = $this->find('all', array('conditions' => $condicao, 'recursive' => '0'));

		return $habilidades;		
	}

}
?>
