<?php 
class Estado extends AppModel {

	var $name = 'Estado';

	var $useTable = 'estado';
	var $primaryKey = 'estado_id';

	var $hasMany = array('Cidade' => array('className' => 'Cidade',
										   'foreignKey' => 'estado_id'));
}
?>
