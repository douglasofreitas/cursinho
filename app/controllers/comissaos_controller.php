<?php 
class ComissaosController extends AppController {

	//var $scaffold;

	var $layout = 'tricolor_layout';
	var $pageTitle = 'comissao';
	var $paginate;

	var $helpers = array('Chart');

	var $uses = array();

	function index()
	{
		$this->set('content_title', 'Módulo Comissão'); 
	}

}
?>
