<?php

class BoletimsController extends AppController {

	//var $scaffold;

	var $layout = 'tricolor_layout';
	var $pageTitle = 'estudantes';
	var $paginate;

	var $uses = array('Boletim', 'Estudante');

	function index()
	{
		//$this->set('content_title', 'Boletim'); 
	}

	function inserir($estudante_id = 0)
	{
		$this->set('content_title', 'Inserir Nota'); 

		if(!empty($this->data))
		{
			if(isset($this->data['Boletim']))
			{
				$this->data['Boletim']['estudante_id'] = $estudante_id;
				//inserir nota
				if($this->Boletim->save($this->data))
				{
					$this->Session->setFlash('Nota inserida com sucesso');
					$this->redirect('/boletims/visualizar_direto/'.$this->data['Boletim']['estudante_id']);
				}
				else
				{
					$this->Session->setFlash('Não foi possível incluir a nota. Verifique se faltou algum campo para ser preenchido. Se ainda assim continuar com o problema, notifique o suporte técnico');
					$this->redirect('/estudantes/index/');
				}
			}
			else
			{
				//ainda tem que adicionar os dados da nota
                                if(!empty($this->data['Candidato']['estudante_id']))
                                        $estudante_id = $this->data['Candidato']['estudante_id'];
                                else
                                        $estudante_id = $this->Boletim->Estudante->obterId($this->data['Candidato']['numero_inscricao'], $this->data['Candidato']['ano']);
				if($estudante_id > 0)
				{
					$this->set('metodo_destino', 'inserir');
					$this->set('estudante_id', $estudante_id);
					$this->render('boletim');
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

	function inserir_direto($estudante_id)
	{

	}

	function remover($boletim_id = 0)
	{
		$boletim = $this->Boletim->getBoletim($boletim_id);
		if($this->Boletim->delete($boletim))
		{
			$this->Session->setFlash('Nota removida com sucesso');
			$this->redirect('/boletims/visualizar_direto/'.$boletim['Boletim']['estudante_id']);
		}
		else
		{
			$this->Session->setFlash('Nota não pode ser removida. Entre em contato com os técnicos');
			$this->redirect('/estudantes/index/');
		}
	}

	function editar_direto($boletim_id = 0)
	{
		$this->set('content_title', 'Editar Nota');

		if(isset($this->data['Boletim']))
		{
			//edita nota
			if($this->Boletim->save($this->data))
			{
				$this->Session->setFlash('Nota alterada com sucesso');
				$this->redirect('/boletims/visualizar_direto/'.$this->data['Boletim']['estudante_id']);
			}
			else
			{
				$this->Session->setFlash('Não foi possível alterar a Nota. Verifique se faltou algum campo para ser preenchido. Se ainda assim continuar com o problema, notifique o suporte técnico');
				$this->redirect('/estudantes/index/');
			}
		}
		else
		{
			$this->data = $this->Boletim->getBoletim($boletim_id);
			$this->set('metodo_destino', 'editar_direto');
			$this->render('boletim');
		}
	}

	function visualizar($estudante_id = 0)
	{
		$this->set('content_title', 'Visualizar Nota'); 

		if(!empty($this->data))
		{
			//ainda deve vereficar se o estudante ja tem um evasão registrada, pois deve ter somente uma.
                        if(!empty($this->data['Candidato']['estudante_id']))
                                $estudante_id = $this->data['Candidato']['estudante_id'];
                        else
                                $estudante_id = $this->Boletim->Estudante->obterId($this->data['Candidato']['numero_inscricao'], $this->data['Candidato']['ano']);
			if($estudante_id > 0)
			{
				if($this->Estudante->possuiBoletim($estudante_id))
				{
					$this->data = $this->Boletim->getAllBoletimPorEstudante($estudante_id);
					$nome = $this->Estudante->obterNome($estudante_id);
					$this->set('estudante_id', $estudante_id);
					$this->set('nome', $nome);
					$this->render('visualizar');	
				}
				else
				{
					$this->Session->setFlash('Estudante não possui notas.');
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
		$this->set('content_title', 'Visualizar Notas'); 

		if($this->Estudante->possuiBoletim($estudante_id))
		{
			$this->data = $this->Boletim->getAllBoletimPorEstudante($estudante_id);
			$nome = $this->Estudante->obterNome($estudante_id);
			$this->set('estudante_id', $estudante_id);
			$this->set('nome', $nome);
			$this->render('visualizar');	
		}
		else
		{
			$this->Session->setFlash('Este estudante não possui notas no boletim.');
			$this->render('branco');
		}

	}
}

?>
