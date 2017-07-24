<?php 
class Prova extends AppModel {

	var $name = 'Prova';

	var $useTable = 'prova';
	var $primaryKey = 'prova_id';

	var $hasMany = array('QuestaoProva' => array('className' => 'QuestaoProva',
												 'foreignKey' => 'prova_id'));

	function naoExiste($ano = null)
	{
		if($ano == null)
		{
			$condicao = array('ano' => $this->data['Prova']['ano']);
		}
		else
		{
			$condicao = array('ano' => $ano);
		}

		if ($this->find('count', array('conditions' => $condicao, 'recursive' => '0')) == 0)
			return true;
		else
			return false;
	}

	function existe($ano = null)
	{
		if($ano == null)
		{
			$condicao = array('ano' => $this->data['Prova']['ano']);
		}
		else
		{
			$condicao = array('ano' => $ano);
		}

		if ($this->find('count', array('conditions' => $condicao, 'recursive' => '0')) == 0)
			return false;
		else
			return true;
	}
	function obterId($ano)
	{		
		$condicao = array('ano' => $ano);
		$prova = $this->find('first', array('conditions' => $condicao, 'recursive' => '0'));
		if($prova)			
			return $prova['Prova']['prova_id'];
		else
			return false;
	}

	function obterAno($id)
	{		
		$condicao = array('Prova.prova_id' => $id);
		$prova = $this->find('first', array('conditions' => $condicao, 'recursive' => '0'));
		if($prova)			
			return $prova['Prova']['ano'];
		else
			return false;
	}

	function obterArquivo($id)
	{
		$condicao = array('Prova.prova_id' => $id);
		$prova = $this->find('first', array('conditions' => $condicao, 'recursive' => '0'));
		if($prova)			
			//return $prova['Prova']['arquivo'];
			return false;
		else
			return false;
	}
}
?>
