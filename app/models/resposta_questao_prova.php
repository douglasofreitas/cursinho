<?php 
class RespostaQuestaoProva extends AppModel {

	var $name = 'RespostaQuestaoProva';

	var $useTable = 'resposta_questao_prova';
	var $primaryKey = 'resposta_questao_prova_id';

	var $belongsTo = array('Candidato' => array('className' => 'Candidato',
												'foreignKey' => 'candidato_id'),

						   'QuestaoProva' => array('className' => 'QuestaoProva',
												   'foreignKey' => 'questao_prova_id'));
	var $validate = array(
		'candidato_id' 	=> array('rule' => 'notEmpty',
									'message' => 'Você deve informar o Id do Candidato.'),
		'questao_prova_id'		 	=> array('rule' => 'notEmpty',
									'message' => 'Você deve informar o Id da questao a ser respondida.')
	);

	function obterId($candidato_id, $questao_prova_id)
	{
		$condicao = array('RespostaQuestaoProva.candidato_id' => $candidato_id, 'RespostaQuestaoProva.questao_prova_id' => $questao_prova_id);
		$resposta_questao = $this->find('first', array('conditions' => $condicao, 'recursive' => '0'));

		return $resposta_questao['RespostaQuestaoProva']['resposta_questao_prova_id'];
	}

	function getRespostasCandidato($candidato_id)
	{
		$condicao = array('RespostaQuestaoProva.candidato_id' => $candidato_id);
		$resposta_questao = $this->find('all', array('conditions' => $condicao, 'recursive' => '1'));

		return $resposta_questao;		
	}

	function getNumRespostas($candidato_id)
	{
		$condicao = array('RespostaQuestaoProva.candidato_id' => $candidato_id);
		$numero_respostas = $this->find('count', array('conditions' => $condicao, 'recursive' => '1'));

		return $numero_respostas;		
	}	

	function existeResposta($questao_prova_id)
	{
		$condicao = array('RespostaQuestaoProva.questao_prova_id' => $questao_prova_id,
							'RespostaQuestaoProva.alternativa_marcada <>' => '');
		$numero_respostas = $this->find('count', array('conditions' => $condicao, 'recursive' => '1'));

		if($numero_respostas>0)
			return true;
		else
			return false;	
	}	
}
?>