<?php 
class Turma extends AppModel {

	var $useTable = 'turmas';
	var $primaryKey = 'id';

	var $name = 'Turma';

        var $validate = array(
		'ano_letivo'        => array('rule' => 'notEmpty',
                                                        'message' => 'Ano letivo obrigatório'),
		'nome'              => array('rule' => 'notEmpty',
                                                        'message' => 'Nome obrigatório')					
	);

        var $belongsTo = array(
                'Unidade' => array('className' => 'Unidade','foreignKey' => 'unidade_id')
        );

        var $hasMany = array(
            'Estudante' => array('className' => 'Estudante',
                'foreignKey' => 'turma_id')
        );

	function getSelectForm()
	{
                $array_turma = array();
		$turmas = $this->find('all');

		foreach($turmas as $t){
			$array_turma[$t['Turma']['id']] = $t['Turma']['nome'].' ('.$t['Turma']['ano_letivo'].')';
		}
		return $array_turma;
	}

}
?>
