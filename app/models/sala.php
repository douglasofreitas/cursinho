<?php 
class Sala extends AppModel {

	var $useTable = 'sala';
	var $primaryKey = 'sala_id';

	var $name = 'Sala';
	var $validate = array(
		'ano_letivo' => array('notempty'),
		'numero' => array('notempty'),
		'quantidade_vagas' => array('notempty')
	);
	var $hasMany = array('Estudante' => array('className' => 'Estudante',
										      'foreignKey' => 'sala_id'));
	var $belongsTo = array('Unidade' => array('className' => 'Unidade',
										   'foreignKey' => 'unidade_id'));

	var $order = array("Sala.numero" => "asc");

	function obterId($ano, $numero_sala)
	{
		$condicao = array('Sala.ano_letivo' => $ano, 'Sala.numero' => $numero_sala);

		$sala = $this->find('first', array('conditions' => $condicao, 
												'recursive' => '-1'));

		return $sala['Sala']['sala_id'];
	}

	function getSala($sala_id)
	{
		$condicao = array('Sala.sala_id' => $sala_id);

		$sala = $this->find('first', array('conditions' => $condicao, 
												'recursive' => '-1'));

		return $sala;
	}

	function existeSala($ano, $numero_sala)
	{
		$condicao = array('Sala.ano_letivo' => $ano, 'Sala.numero' => $numero_sala);

		if($this->find('count', array('conditions' => $condicao, 'recursive' => '-1')) > 0)
			return true;
		else
			return false;
	}

	function possuiSalas($ano)
	{
		$condicao = array('Sala.ano_letivo' => $ano);

		if($this->find('count', array('conditions' => $condicao, 'recursive' => '-1')) > 0)
			return true;
		else
			return false;
	}

	function getAllSalasPorAnoLetivo($ano)
	{
		$condicao = array('Sala.ano_letivo' => $ano);

		$salas = $this->find('all', array('conditions' => $condicao, 
												'recursive' => '0'));

		return $salas;
	}

	function getAllSalasPorAnoLetivoUnidade($ano, $unidade)
	{
		$condicao = array('Sala.ano_letivo' => $ano, 'Sala.unidade_id' => $unidade);

		$salas = $this->find('all', array('conditions' => $condicao, 
												'recursive' => '0'));

		return $salas;
	}

	function possuiEstudantes($sala_id)
	{
		$condicao = array('Estudante.sala_id' => $sala_id);

		if($this->Estudante->find('count', array('conditions' => $condicao, 'recursive' => '-1')) > 0)
			return true;
		else
			return false;
	}

}
?>
