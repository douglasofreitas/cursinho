<?php 
class CriteriosDaFaseEliminatoria extends AppModel {

	var $name = 'CriteriosDaFaseEliminatoria';

	var $useTable = 'criterios_da_fase_eliminatoria';
	var $primaryKey = 'criterios_da_fase_eliminatoria_id';

        var $belongsTo = array(
		'ProcessoSeletivo' => array(
			'className' => 'ProcessoSeletivo',
			'foreignKey' => 'processo_seletivo_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	var $validate = array(
		'pontuacao_social_minima_um_ano' => array('rule' => 'notEmpty',
												  'message' => 'campo obrigatório'),

		'pontuacao_social_maxima_um_ano' => array('rule' => 'notEmpty',
												  'message' => 'campo obrigatório'),

		'pontuacao_economica_minima_um_ano' => array('rule' => 'notEmpty',
												  'message' => 'campo obrigatório'),

		'pontuacao_economica_maxima_um_ano' => array('rule' => 'notEmpty',
												  'message' => 'campo obrigatório'),

		'pontuacao_social_minima_dois_anos' => array('rule' => 'notEmpty',
												  'message' => 'campo obrigatório'),

		'pontuacao_social_minima_dois_anos' => array('rule' => 'notEmpty',
												  'message' => 'campo obrigatório'),

		'pontuacao_social_maxima_dois_anos' => array('rule' => 'notEmpty',
												  'message' => 'campo obrigatório'),

		'pontuacao_economica_minima_dois_anos' => array('rule' => 'notEmpty',
												  'message' => 'campo obrigatório'),

		'pontuacao_economica_maxima_dois_anos' => array('rule' => 'notEmpty',
												  'message' => 'campo obrigatório'),

		'pontuacao_social_minima_dois_anos' => array('rule' => 'notEmpty',
												  'message' => 'campo obrigatório')
	);

	function existeCriterio($processo_seletivo_id)
	{
		$condicao = array('CriteriosDaFaseEliminatoria.processo_seletivo_id' => $processo_seletivo_id);

		if ($this->find('count', array('conditions' => $condicao)) > 0)
			return true;
		else
			return false;
	}

	function obterAno($fase_eliminatoria_id)
	{
		$condicao = array('criterios_da_fase_eliminatoria_id' => $fase_eliminatoria_id);		

		$fase_eliminatoria = $this->find('first', array('conditions' => $condicao));

		if ($fase_eliminatoria)
		{
			App::import('Model', 'ProcessoSeletivo');
			$this->ProcessoSeletivo = new ProcessoSeletivo();

			return $this->ProcessoSeletivo->obterAno($fase_eliminatoria['CriteriosDaFaseEliminatoria']['processo_seletivo_id']);
		}
		else
			return null;
	}

}
?>
