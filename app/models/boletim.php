<?php 
class Boletim extends AppModel {

	var $useTable = 'boletim';
	var $primaryKey = 'boletim_id';

	var $name = 'Boletim';
	var $belongsTo = array('Estudante' => array('className' => 'Estudante',
												'foreignKey' => 'estudante_id'));

	function getBoletim($boletim_id)
	{
		$condicao = array('Boletim.boletim_id' => $boletim_id);

		$boletim = $this->find('first', array('conditions' => $condicao, 
												'recursive' => '-1'));

		return $boletim;
	}

	function getAllBoletimPorEstudante($estudante_id)
	{
		$condicao = array('Boletim.estudante_id' => $estudante_id);

		$boletins = $this->find('all', array('conditions' => $condicao, 
												'recursive' => '-1'));

		return $boletins;
	}

        function afterFind($results) {
		foreach ($results as $key => $val) {
			if (isset($val['Boletim']['data_atividade'])) {
				$results[$key]['Boletim']['data_atividade_form'] = $this->dataFormatAfterFind($val['Boletim']['data_atividade']);
			}
		}
		return $results;
	}

	function valorFormatAfterFind($valor) {
		return number_format($valor, 2, ',', '.');
	}

        function dataFormatAfterFind($valor) {
            $temp = split('-', $valor);
            return $temp[2].'/'.$temp[1].'/'.$temp[0];
	}
}
?>
