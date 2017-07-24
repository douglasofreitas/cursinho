<?php 
class Advertencia extends AppModel {

	var $useTable = 'advertencia';
	var $primaryKey = 'advertencia_id';

	var $name = 'Advertencia';
	var $belongsTo = array('Estudante' => array('className' => 'Estudante',
												'foreignKey' => 'estudante_id'));

	function getAdvertencia($advertencia_id)
	{
		$condicao = array('Advertencia.advertencia_id' => $advertencia_id);

		$advertencia = $this->find('first', array('conditions' => $condicao, 
												'recursive' => '-1'));

		return $advertencia;
	}

	function getAllAdvertenciaPorEstudante($estudante_id)
	{
		$condicao = array('Advertencia.estudante_id' => $estudante_id);

		$advertencias = $this->find('all', array('conditions' => $condicao, 
												'recursive' => '-1'));

		return $advertencias;
	}

    function afterFind($results) {
		foreach ($results as $key => $val) {
			if (!empty($val['Advertencia']['data_advertencia'])) {
				$results[$key]['Advertencia']['data_advertencia_form'] = $this->dataFormatAfterFind($val['Advertencia']['data_advertencia']);
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
