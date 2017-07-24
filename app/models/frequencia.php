<?php 
class Frequencia extends AppModel {

	var $useTable = 'frequencia';
	var $primaryKey = 'frequencia_id';

	var $name = 'Frequencia';
	var $belongsTo = array('Estudante' => array('className' => 'Estudante',
												'foreignKey' => 'estudante_id'));

	function possuiRegistro($data)
	{
		$query = "SELECT * FROM frequencia WHERE data_frequencia = '".$data['Frequencia']['data_frequencia']['year']."-".$data['Frequencia']['data_frequencia']['month']."-".$data['Frequencia']['data_frequencia']['day']."'";
		$resultado = $this->query($query);

		if ($resultado)
			return true;
		else
			return false;		
	}

	function obterDiasLetivos($inicio_dia, $inicio_mes, $fim_dia, $fim_mes, $ano_letivo)
	{
		$query = "SELECT *, date_format(data_frequencia, '%d/%m/%Y') as data FROM frequencia WHERE data_frequencia >= '".$ano_letivo."-".$inicio_mes."-".$inicio_dia."' and data_frequencia <= '".$ano_letivo."-".$fim_mes."-".$fim_dia."' group by data_frequencia";
		$resultado = $this->query($query);

		if ($resultado)
			return $resultado;
		else
			return null;	
	}

	function obterFrequenciaEstudanteData($estudante_id, $inicio_dia, $inicio_mes, $fim_dia, $fim_mes, $ano_letivo)
	{
		$query = "SELECT *, date_format(data_frequencia, '%d/%m/%Y') as data FROM frequencia WHERE data_frequencia >= '".$ano_letivo."-".$inicio_mes."-".$inicio_dia."' and data_frequencia <= '".$ano_letivo."-".$fim_mes."-".$fim_dia."' and estudante_id = ".$estudante_id;
		$resultado = $this->query($query);

		if ($resultado)
			return $resultado;
		else
			return null;	
	}

        function obterFrequenciaEstudanteDataSimples($estudante_id, $dia, $mes, $ano_letivo)
	{
		$query = "SELECT *, date_format(data_frequencia, '%d/%m/%Y') as data FROM frequencia WHERE data_frequencia = '".$ano_letivo."-".$mes."-".$dia."' AND estudante_id = ".$estudante_id;
		$resultado = $this->query($query);

		if ($resultado)
			return $resultado;
		else
			return null;	
	}

	function existeFrequenciaPeriodo($inicio, $fim)
	{
		$query = "SELECT * FROM frequencia WHERE data_frequencia >= '".$inicio['year']."-".$inicio['month']."-".$inicio['day']."' and data_frequencia <= '".$fim['year']."-".$fim['month']."-".$fim['day']."' ";
		$resultado = $this->query($query);

		if ($resultado)
			return true;
		else
			return false;	
	}

	function existeFrequenciaAno($ano)
	{
		$query = "SELECT * FROM frequencia WHERE data_frequencia >= '".$ano."-01-01' and data_frequencia <= '".$ano."-12-31' ";
		$resultado = $this->query($query);

		if ($resultado)
			return true;
		else
			return false;	
	}
}
?>
