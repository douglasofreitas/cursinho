<?php 
class EstudanteTurma extends AppModel {

	var $useTable = 'estudante_turma';
	var $primaryKey = 'id';

	var $name = 'EstudanteTurma';

	var $belongsTo = array('Estudante' => array('className' => 'Estudante',
                                    'foreign_key' => 'estudante_id'));

}
?>
