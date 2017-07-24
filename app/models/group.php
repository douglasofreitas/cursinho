<?php 
class Group extends AppModel {

	var $name = 'Group';

	var $useTable = 'group';
	var $primaryKey = 'id';

	var $validate = array(
		'id' => array('numeric'),
		'nome' => array('notempty')
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $hasMany = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
	var $actsAs = array('Acl' => array('requester'));

	function parentNode() {
		return null;
	}

	function obterId($nome)
	{
		$condicao = array('Group.nome' => $nome);

		$group = $this->find('first', array('conditions' => $condicao, 
												'recursive' => '0'));

		return $group['Group']['id'];
	}

	function getNome($id)
	{
		$condicao = array('Group.id' => $id);

		$group = $this->find('first', array('conditions' => $condicao, 
												'recursive' => '0'));

		return $group['Group']['nome'];
	}

}
?>
