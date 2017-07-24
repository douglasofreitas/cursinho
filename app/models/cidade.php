<?php 
class Cidade extends AppModel {

	var $name = 'Cidade';

	var $useTable = 'cidade';
	var $primaryKey = 'cidade_id';
	var $belongsTo = array('Estado' => array('className' => 'Estado',
											 'foreignKey' => 'estado_id'));

	var $hasMany = array('Candidato' => array('className' => 'Candidato',
											  'foreignKey' => 'cidade'),
						'User' => array('className' => 'User',
											  'foreignKey' => 'cidade')
						);

	function obtemId($nome, $estado)
	{
		$condicao = array('Cidade.nome' => $nome, 'Cidade.estado_id' => $estado);

		$cidade = $this->find('first', array('conditions' => $condicao,
											 'recursive' => '0'));

		if ($cidade)
			return $cidade['Cidade']['cidade_id'];
		else
			return false;
	}
}
?>
