<?php
/**
 * Classe correspondente ao Sistema Geral
 */
/* SVN FILE: $Id$ */
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.controller
 * @since         CakePHP(tm) v 0.2.9
 * @version       $Revision$
 * @modifiedby    $LastChangedBy$
 * @lastmodified  $Date$
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       cake
 * @subpackage    cake.cake.libs.controller
 */
class PagesController extends AppController {
/**
 * Controller name
 *
 * @var string
 * @access public
 */
	var $name = 'Pages';
/**
 * Default helper
 *
 * @var array
 * @access public
 */
	var $helpers = array('Html');
/**
 * This controller does not use a model
 *
 * @var array
 * @access public
 */
	var $uses = array();

	var $layout = 'tricolor_layout';
	var $pageTitle = 'home';

/**
 * Displays a view
 *
 * @param mixed What page to display
 * @access public
 */
	function display() {
		$path = func_get_args();
		$count = count($path);
		if (!$count) {
			$this->redirect('/');
		}
		$page = $subpage = $title = null;
		if (!empty($path[0])) {
			$page = $path[0];
		}
		if (!empty($path[1])) {
			$subpage = $path[1];
		}
		if (!empty($path[$count - 1])) {
			$title = Inflector::humanize($path[$count - 1]);
		}

		//print_r($path);

		if($path[0] == 'home'){
			$this->set('content_title', 'Home');

            //se for estudante/candidato, redirecionar para a página padrão
            if(!empty($_SESSION['Auth']['User']['group_id']))
            if($_SESSION['Auth']['User']['group_id'] == 3)
                $this->redirect('/candidatos/index');
        }
        if($path[0] == 'quem_somos')
			$this->set('content_title', 'Quem Somos');
		if($path[0] == 'contato')
			$this->set('content_title', 'Contato');

		//$this->set(compact('page', 'subpage', 'title'));
		$this->render(join('/', $path));
	}

	function beforeFilter() {
		parent::beforeFilter(); 
		//$this->Auth->allow('login', 'logout');
		$this->Auth->allow('*');
	}

}
?>