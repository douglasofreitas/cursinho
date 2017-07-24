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

        function getTurma($turma_id)
	{
		$condicao = array('Turma.id' => $turma_id);

		$turma = $this->find('first', array('conditions' => $condicao, 
												'recursive' => '-1'));

		return $turma;
	}

        function getAllTurmasPorAnoLetivo($ano)
	{
		$condicao = array('Turma.ano_letivo' => $ano);

		$turmas = $this->find('all', array('conditions' => $condicao, 
												'recursive' => '0'));

		return $turmas;
	}

	function getAllTurmasPorAnoLetivoUnidade($ano, $unidade)
	{
		$condicao = array('Turma.ano_letivo' => $ano, 'Turma.unidade_id' => $unidade);

		$turmas = $this->find('all', array('conditions' => $condicao, 
												'recursive' => '0'));

		return $turmas;
	}

	function possuiEstudantes($turma_id)
	{
		$condicao = array('Estudante.turma_id' => $turma_id);

		if($this->Estudante->find('count', array('conditions' => $condicao, 'recursive' => '-1')) > 0)
			return true;
		else
			return false;
	}

}
?>
