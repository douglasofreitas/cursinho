<?php 
class CoordenadorsController extends AppController {

	//var $scaffold;

	var $layout = 'tricolor_layout';
	var $pageTitle = 'coordenador';
	var $paginate;

	var $helpers = array('Chart');

	var $uses = array('ProcessoSeletivo', 'ConfiguracaoProcessoSeletivo');

	function index()
	{
		$this->set('content_title', 'Módulo Coordenação'); 
	}

	function processo_seletivo()
	{
		$this->redirect('/processo_seletivos/visualizar_processos_seletivos');
	}

	function configuracao()
	{
		$this->redirect('/configuracao_processo_seletivos/visualizar_configuracao');
	}

	function lista_athenas_ru()
    {
    	$this->set('content_title', 'Listas Athenas / R.U.');

    	if(!empty($this->data))
		{
			if($this->data == '' or $this->data == ' ')
			{
				//carrrega a página para obter o ano
				$this->Session->setFlash('Digite um ano válido!');
				$this->set('metodo_destino', 'lista_athenas_ru');
				$this->render('ano');				
			}
			else
			{
				//mostra página para escolha da lista.
				$this->set('ano', $this->data['Ano']['ano']);
				$this->render('tipo_lista_athenas_ru');				
			}
		}
		else
		{
			//carrrega a página para obter o ano
			$this->set('metodo_destino', 'lista_athenas_ru');
			$this->render('ano');
		}
    }

}
?>
