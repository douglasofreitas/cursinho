<?php 
class QuestaoProva extends AppModel {

	var $name = 'QuestaoProva';

	var $useTable = 'questao_prova';
	var $primaryKey = 'questao_prova_id';

	var $order = "QuestaoProva.numero_questao ASC";

	var $belongsTo = array('Prova' => array('className' => 'Prova', 'foreignKey' => 'prova_id'),
				'HabilidadeAvaliada' => array('className' => 'HabilidadeAvaliada', 'foreignKey' => 'habilidade_avaliada_id'));

	var $hasMany = array('RespostaQuestaoProva' => array('className' => 'RespostaQuestaoProva',
														 'foreignKey' => 'questao_prova_id'));

	var $validate = array(
		'numero_questao' 	=> array('rule' => 'notEmpty',
									'message' => 'Você deve informar o número da questao.'),
		'prova_id'		 	=> array('rule' => 'notEmpty',
									'message' => 'Você deve informar o ano da prova.')
	);

	function existeQuestao($prova_id, $questao_num)
	{
		$condicao = array('QuestaoProva.prova_id' => $prova_id, 'QuestaoProva.numero_questao' => $questao_num);
		if ($this->find('count', array('conditions' => $condicao, 'recursive' => '0')) == 0)
			return false;
		else
			return true;
	}

	function obterId($prova_id, $numero_questao)
	{
		$condicao = array('QuestaoProva.prova_id' => $prova_id, 'QuestaoProva.numero_questao' => $numero_questao);
		$questao = $this->find('first', array('conditions' => $condicao, 'recursive' => '0'));

		return $questao['QuestaoProva']['questao_prova_id'];
	}

	function numeroQuestoesProva($prova_id)
	{
		$condicao = array('QuestaoProva.prova_id' => $prova_id);
		$numero_questoes = $this->find('count', array('conditions' => $condicao, 'recursive' => '0'));

		return $numero_questoes;
	}

	function getAllQuestoesProva($prova_id)
	{
		$condicao = array('QuestaoProva.prova_id' => $prova_id);
		$questoes = $this->find('all', array('conditions' => $condicao, 'recursive' => '0'));

		return $questoes;
	}

	function getQuestao($prova_id, $numero_questao)
	{
		$condicao = array('QuestaoProva.prova_id' => $prova_id, 'QuestaoProva.numero_questao' => $numero_questao);
		$questao = $this->find('first', array('conditions' => $condicao, 'recursive' => '0'));

		return $questao;
	}	

}
?>
