<?php 
/**
 * Classe correspondente ao Módulo Docentes
 */
class DocentesController extends AppController {

	//var $scaffold;

	var $layout = 'tricolor_layout';
	var $pageTitle = 'docente';
	var $paginate;

	var $helpers = array('Chart');

	var $uses = array();

	function index()
	{
		$this->set('content_title', 'Módulo Docente'); 
	}

}
?>
