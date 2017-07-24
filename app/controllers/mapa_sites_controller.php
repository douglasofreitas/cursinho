<?php 
/**
 * Classe correspondente ao Sistema Geral
 */
class MapaSitesController extends AppController {

	//var $scaffold;

	var $layout = 'tricolor_layout';
	var $pageTitle = 'home';
	var $paginate;

	var $helpers = array('Chart');

	var $uses = array();

	function index()
	{
		$this->set('content_title', 'Mapa do site'); 
	}

}
?>
