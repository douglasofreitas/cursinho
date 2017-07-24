<?php
class AdvertenciasController extends AppController {

	//var $scaffold;

	var $layout = 'tricolor_layout';
	var $pageTitle = 'estudantes';
	var $paginate;

	var $uses = array('Advertencia', 'Estudante');

	function index()
	{

	}

	function inserir($estudante_id = 0)
	{
		$this->set('content_title', 'Inserir Advertência'); 

		if(!empty($this->data))
		{
			if(isset($this->data['Advertencia']))
			{
				$this->data['Advertencia']['estudante_id'] = $estudante_id;
				//inserir advertência
				if($this->Advertencia->save($this->data))
				{
					$this->Session->setFlash('Advertência inserida com sucesso');
					$this->redirect('/advertencias/visualizar_direto/'.$this->data['Advertencia']['estudante_id']);
				}
				else
				{
					$this->Session->setFlash('Não foi possível incluir a advertência. Verifique se faltou algum campo para ser preenchido. Se ainda assim continuar com o problema, notifique o suporte técnico');
					$this->redirect('/estudantes/index/');
				}
			}
			else
			{
				//ainda tem que adicionar os dados de evasão
				$estudante_id = $this->Advertencia->Estudante->obterId($this->data['Candidato']['numero_inscricao'], $this->data['Candidato']['ano']);
				if($estudante_id > 0)
				{
                                        $estudante = $this->Advertencia->Estudante->read(null, $estudante_id);
                                        $this->set('estudante', $estudante);
					$this->set('metodo_destino', 'inserir');
					$this->set('estudante_id', $estudante_id);
					$this->render('advertencia');
				}
				else
				{
					$this->Session->setFlash('Estudante não encontrado. Por favor verifique os campos.');
					$this->set('metodo_destino', 'inserir');
					$this->render('inscricao_ano');
				}
			}
		}
		else
		{
			//carrrega a página para obtenção de dados

			$this->set('metodo_destino', 'inserir');
			$this->render('inscricao_ano');
		}
	}
	function inserir_direto($estudante_id = null)
	{
                if(empty($estudante_id))
                    $this->redirect('/estudantes/listar_todos');

		$this->set('content_title', 'Inserir Advertência'); 

		if(!empty($this->data))
		{
			if(isset($this->data['Advertencia']))
			{
				$this->data['Advertencia']['estudante_id'] = $estudante_id;
				//inserir advertência
				if($this->Advertencia->save($this->data))
				{
					$this->Session->setFlash('Advertência inserida com sucesso');
					$this->redirect('/advertencias/visualizar_direto/'.$this->data['Advertencia']['estudante_id']);
				}
				else
				{
					$this->Session->setFlash('Não foi possível incluir a advertência. Verifique se faltou algum campo para ser preenchido. Se ainda assim continuar com o problema, notifique o suporte técnico');
					$this->redirect('/estudantes/index/');
				}
			}
			else
			{
				//ainda tem que adicionar os dados de evasão
				$estudante_id = $this->Advertencia->Estudante->obterId($this->data['Candidato']['numero_inscricao'], $this->data['Candidato']['ano']);
				if($estudante_id > 0)
				{
                                        $estudante = $this->Advertencia->Estudante->read(null, $estudante_id);
                                        $this->set('estudante', $estudante);
					$this->set('metodo_destino', 'inserir');
					$this->set('estudante_id', $estudante_id);
					$this->render('advertencia');
				}
				else
				{
					$this->Session->setFlash('Estudante não encontrado. Por favor verifique os campos.');
					$this->set('metodo_destino', 'inserir');
					$this->render('inscricao_ano');
				}
			}
		}
		else
		{
			$estudante = $this->Advertencia->Estudante->read(null, $estudante_id);
			$this->set('metodo_destino', 'inserir');
                        $this->set('estudante', $estudante);
                        $this->set('estudante_id', $estudante_id);
                        $this->render('advertencia');
		}
	}

	function remover($advertencia_id = 0)
	{
		$advertencia = $this->Advertencia->getAdvertencia($advertencia_id);
		if($this->Advertencia->delete($advertencia))
		{
			$this->Session->setFlash('Advertência removida com sucesso');
			$this->redirect('/advertencias/visualizar_direto/'.$advertencia['Advertencia']['estudante_id']);
		}
		else
		{
			$this->Session->setFlash('Advertência não pode ser removida. Entre em contato com os técnicos');
			$this->redirect('/estudantes/index/');
		}
	}

	function editar_direto($advertencia_id = 0)
	{
		$this->set('content_title', 'Editar Advertência');

		if(isset($this->data['Advertencia']))
		{
			//edita advertencia
			if($this->Advertencia->save($this->data))
			{
				$this->Session->setFlash('Advertência alterada com sucesso');
				$this->redirect('/advertencias/visualizar_direto/'.$this->data['Advertencia']['estudante_id']);
			}
			else
			{
				$this->Session->setFlash('Não foi possível alterar a advertência. Verifique se faltou algum campo para ser preenchido. Se ainda assim continuar com o problema, notifique o suporte técnico');
				$this->redirect('/estudantes/index/');
			}
		}
		else
		{
			$this->data = $this->Advertencia->getAdvertencia($advertencia_id);
			$this->set('metodo_destino', 'editar_direto');
			$this->render('advertencia');
		}
	}

	function visualizar($estudante_id = 0)
	{
		$this->set('content_title', 'Visualizar Advertências'); 

		if(!empty($this->data))
		{
			//ainda deve vereficar se o estudante ja tem um evasão registrada, pois deve ter somente uma.
			$estudante_id = $this->Advertencia->Estudante->obterId($this->data['Candidato']['numero_inscricao'], $this->data['Candidato']['ano']);
			if($estudante_id > 0)
			{
				if($this->Estudante->possuiAdvertencia($estudante_id))
				{
					$this->data = $this->Advertencia->getAllAdvertenciaPorEstudante($estudante_id);
					$nome = $this->Estudante->obterNome($estudante_id);
					$this->set('estudante_id', $estudante_id);
					$this->set('nome', $nome);
					$this->render('visualizar');	
				}
				else
				{
					$this->Session->setFlash('Estudante não possui advertências. Verifique os dados inseridos');
					$this->set('metodo_destino', 'visualizar');
					$this->render('inscricao_ano');	
				}
			}
			else
			{
				$this->Session->setFlash('Estudante não existe. Por favor verifique os campos.');
				$this->set('metodo_destino', 'visualizar');
				$this->render('inscricao_ano');
			}
		}
		else
		{
			//carrrega a página para obtenção de dados

			$this->set('metodo_destino', 'visualizar');
			$this->render('inscricao_ano');
		}
	}

	function visualizar_direto($estudante_id)
	{
		$this->set('content_title', 'Visualizar Advertências'); 

		if($this->Estudante->possuiAdvertencia($estudante_id))
		{
			$this->data = $this->Advertencia->getAllAdvertenciaPorEstudante($estudante_id);
			$nome = $this->Estudante->obterNome($estudante_id);
			$this->set('estudante_id', $estudante_id);
			$this->set('nome', $nome);
			$this->render('visualizar');
		}
		else
		{
			$this->Session->setFlash('Este estudante não possui advertências.');
			$this->render('branco');
		}
	}

}
?>