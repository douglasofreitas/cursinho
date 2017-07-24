<?php
class UnidadesController extends AppController {
	//var $scaffold;
	var $layout = 'tricolor_layout';
	var $pageTitle = 'coordenador';
	var $paginate;
	var $uses = array('Unidade');
	function index()
	{
	    $this->set('content_title', 'Unidades');
            $unidades = $this->Unidade->find('all');
            $this->set('unidades', $unidades );
	}
	function inserir($ano = 0)
	{
		$this->set('content_title', 'Inserir unidade');
		if(!empty($this->data))
		{
			if($this->Unidade->save($this->data)){
                            $this->Session->setFlash('Unidade salva');
                            $this->redirect('/unidades/index');
                        }else{
                            $this->Session->setFlash('Erro ao salvar a unidade. Verifique se os campos foram preenchidos');
                            $this->redirect('/unidades/inserir');
                        }
		}else{

                    $select_unidades = $this->Unidade->getSelectForm();

                    $this->set('metodo_destino', 'inserir');
                    $this->set('select_unidades', $select_unidades);

                    $this->render('unidade');
                }

	}

        function editar($id = null){
                $this->set('content_title', 'Editar unidade');
		if(!empty($this->data))
		{
			if($this->Unidade->save($this->data)){
                            $this->Session->setFlash('Unidade salva');
                            $this->redirect('/unidades/index');
                        }else{
                            $this->Session->setFlash('Erro ao editar a unidade. Verifique se os campos foram preenchidos');
                            $this->redirect('/unidades/inserir');
                        }
		}else{
                    $this->data = $this->Unidade->read(null, $id);
                }
                $select_unidades = $this->Unidade->getSelectForm();
		$this->set('metodo_destino', 'editar');
                $this->set('select_unidades', $select_unidades);
                $this->render('unidade');
        }

        function remover($id = null){
            if($this->Unidade->delete($id)){
                $this->Session->setFlash('Unidade removida');
                $this->redirect('/unidades/index');
            }else{
                $this->Session->setFlash('Erro ao remover a unidade. Verifique se á alunos associados.');
                $this->redirect('/unidades/index');
            }
        }

}
?>
