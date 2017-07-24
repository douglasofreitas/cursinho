<?php
class TurmasController extends AppController {
	//var $scaffold;
	var $layout = 'tricolor_layout';
	var $pageTitle = 'coordenador';
	var $paginate;
	var $uses = array('Turma', 'Unidade');
	function index()
	{
	    $this->set('content_title', 'Turmas');

            $turmas = $this->Turma->find('all', array('recursive' => '1'));
            $this->loadModel('Unidade');
            $unidades = $this->Unidade->find('all', array('recursive' => '0'));

            $array_unidades = array();
            foreach($unidades as $unid){
                $array_unidades[$unid['Unidade']['id']] = $unid;
            }

            $this->set('turmas', $turmas );
            $this->set('array_unidades', $array_unidades);
	}
	function inserir($ano = 0)
	{
		$this->set('content_title', 'Inserir turma');
		if(!empty($this->data))
		{
			if($this->Turma->save($this->data)){
                            $this->Session->setFlash('Turma salva');
                            $this->redirect('/turmas/index');
                        }else{
                            $this->Session->setFlash('Erro ao salvar a turma. Verifique se os campos foram preenchidos');
                            $this->redirect('/turmas/inserir');
                        }
		}else{

                    $select_unidades = $this->Unidade->getSelectForm();

                    $this->set('metodo_destino', 'inserir');
                    $this->set('select_unidades', $select_unidades);

                    $this->render('turma');
                }

	}

        function editar($id = null){
                $this->set('content_title', 'Editar turma');
		if(!empty($this->data))
		{
			if($this->Turma->save($this->data)){
                            $this->Session->setFlash('Turma salva');
                            $this->redirect('/turmas/index');
                        }else{
                            $this->Session->setFlash('Erro ao editar a turma. Verifique se os campos foram preenchidos');
                            $this->redirect('/turmas/inserir');
                        }
		}else{
                    $this->data = $this->Turma->read(null, $id);
                }
                $select_unidades = $this->Unidade->getSelectForm();
		$this->set('metodo_destino', 'editar');
                $this->set('select_unidades', $select_unidades);
                $this->render('turma');
        }

        function remover($id = null){
            if($this->Turma->delete($id)){
                $this->Session->setFlash('Turma removida');
                $this->redirect('/turmas/index');
            }else{
                $this->Session->setFlash('Erro ao remover a turma. Verifique se á alunos associados.');
                $this->redirect('/turmas/index');
            }
        }

}
?>
