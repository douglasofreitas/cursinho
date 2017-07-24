<?php 
class Evasao extends AppModel {

	var $useTable = 'evasao';
	var $primaryKey = 'evasao_id';

	var $name = 'Evasao';

	var $belongsTo = array('Estudante' => array('className' => 'Estudante',
                                    'foreign_key' => 'estudante_id'));

	function obterId($estudante_id)
	{
		$condicao = array('Evasao.estudante_id' => $estudante_id);

		$evasao = $this->find('first', array('conditions' => $condicao, 
												'recursive' => '-1'));

		return $evasao['Evasao']['evasao_id'];
	}

	function getEvasaoPorEstudante($estudante_id)
	{
		$condicao = array('Evasao.estudante_id' => $estudante_id);

		$evasao = $this->find('first', array('conditions' => $condicao, 
												'recursive' => '1'));

		return $evasao;
	}

        function afterFind($results) {
		foreach ($results as $key => $val) {
			if (!empty($val['Evasao']['data'])) {
				$results[$key]['Evasao']['data_form'] = $this->dataFormatAfterFind($val['Evasao']['data']);
			}
		}
		return $results;
	}

        function dataFormatAfterFind($valor) {
            $temp = split('-', $valor);
            return $temp[2].'/'.$temp[1].'/'.$temp[0];
	}

}
?>
