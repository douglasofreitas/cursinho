<?php 
/**
 * Classe correspondente ao Sistema Geral
 */
class CidadesController extends AppController {

	function index()
	{

	}

	function ajax_obtem_cidades($allowEmpty = null)
	{
		$this->layout = null;

		$condicao = array('Cidade.estado_id' => $this->params['form']['estado_id']);

		$cidades = $this->Cidade->find('list', array('conditions' => $condicao,
													 'fields' => array('nome')));

		$this->set('estado_id', $this->params['form']['estado_id']);
		$this->set('cidades', $cidades);

		if ($allowEmpty)
			$this->set('allowEmpty', true);
		else
			$this->set('allowEmpty', false);
	}

}
?>