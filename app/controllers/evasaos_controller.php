<?php
class EvasaosController extends AppController {

	//var $scaffold;

	var $layout = 'tricolor_layout';
	var $pageTitle = 'estudantes';
	var $paginate;

	var $uses = array('Evasao', 'Estudante', 'Candidato');

	function index()
	{
		$this->set('content_title', 'Evasão'); 
	}

	function inserir($estudante_id = 0)
	{
		$this->set('content_title', 'Registrar Evasão'); 

		if(!empty($this->data))
		{
			if(isset($this->data['Evasao']))
			{
				$this->data['Evasao']['estudante_id'] = $estudante_id;
				//registra evasão
				if($this->Evasao->save($this->data))
				{
                    $estudante = $this->Evasao->Estudante->read(null, $estudante_id);
                    $estudante['Estudante']['evasao'] = 1;
                    $this->Evasao->Estudante->create();
                    $this->Evasao->Estudante->save(array('Estudante' => $estudante['Estudante']));

					$this->Session->setFlash('Evasão registrada com sucesso');
					$this->redirect('/estudantes/index/');
				}
				else
				{
					$this->Session->setFlash('Não foi possível registrar a evasão. Verifique se faltou algum campo para ser preenchido. Se ainda assim continuar com o problema, notifique o suporte técnico');
					$this->redirect('/estudantes/index/');
				}
			}
			else
			{
				//ainda tem que adicionar os dados de evasão
				//$estudante_id = $this->Evasao->Estudante->obterId($this->data['Candidato']['numero_inscricao'], $this->data['Candidato']['ano']);
                $estudante_id = $this->data['Estudante']['estudante_id'];
				if($estudante_id == null)
				{
					$this->Session->setFlash('Estudante não encontrado. Por favor verifique os campos.');
					$this->set('metodo_destino', 'inserir');
					$this->render('inscricao_ano');
				}
				else
				{
					//ainda deve vereficar se o estudante ja tem um evasão registrada, pois deve ter somente uma.
					if($this->Estudante->possuiEvasao($estudante_id))
					{
                        $estudante = $this->Evasao->Estudante->read(null, $estudante_id);
                        $estudante['Estudante']['evasao'] = 1;
                        $this->Evasao->Estudante->create();
                        $this->Evasao->Estudante->save(array('Estudante' => $estudante['Estudante']));

						$this->Session->setFlash('Estudante ja possui registro de evasão. Verifique os dados caso tenha digitado incorretamente.');
						$this->set('metodo_destino', 'inserir');
						$this->render('inscricao_ano');	
					}
					else
					{
                        $estudante = $this->Evasao->Estudante->read(null, $estudante_id);


                        $this->set('estudante', $estudante);
						$this->set('metodo_destino', 'inserir');
						$this->set('estudante_id', $estudante_id);
						$this->render('evasao');
					}
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

	function editar($estudante_id = 0)
	{
		$this->set('content_title', 'Editar Evasão'); 

		if(!empty($this->data))
		{
			if(isset($this->data['Evasao']))
			{
				$this->data['Evasao']['estudante_id'] = $estudante_id;
				//altera evasão
				if($this->Evasao->save($this->data))
				{
					$this->Session->setFlash('Evasão alterada com sucesso');
					$this->redirect('/estudantes/index/');
				}
				else
				{
					$this->Session->setFlash('Não foi possível alterar a evasão. Verifique se faltou algum campo para ser preenchido. Se ainda assim continuar com o problema, notifique o suporte técnico');
					$this->redirect('/estudantes/index/');
				}
			}
			else
			{
				//ainda tem que adicionar as alterações
				$estudante_id = $this->Evasao->Estudante->obterId($this->data['Candidato']['numero_inscricao'], $this->data['Candidato']['ano']);
				if($estudante_id == null)
				{
					$this->Session->setFlash('Estudante não encontrado. Por favor verifique os campos.');
					$this->set('metodo_destino', 'editar');
					$this->render('inscricao_ano');
				}
				else
				{
					//ainda deve vereficar se o estudante ja tem um evasão registrada, pois deve ter somente uma.
					if($this->Estudante->possuiEvasao($estudante_id))
					{
                                                $estudante = $this->Evasao->Estudante->read(null, $estudante_id);
                                                $this->set('estudante', $estudante);
						$this->data = $this->Evasao->getEvasaoPorEstudante($estudante_id);
						$this->set('estudante_id', $estudante_id);
						$this->set('metodo_destino', 'editar');
						$this->render('evasao');	
					}
					else
					{
						$this->Session->setFlash('Estudante não possui evasão. Verifique os dados inseridos');
						$this->set('metodo_destino', 'editar');
						$this->render('inscricao_ano');	
					}
				}
			}
		}
		else
		{
			//carrrega a página para obtenção de dados

			$this->set('metodo_destino', 'editar');
			$this->render('inscricao_ano');
		}
	}

	function visualizar($estudante_id = 0)
	{
		$this->set('content_title', 'Visualizar Evasão'); 

		if(!empty($this->data))
		{
			//ainda deve vereficar se o estudante ja tem um evasão registrada, pois deve ter somente uma.
			$estudante_id = $this->Evasao->Estudante->obterId($this->data['Candidato']['numero_inscricao'], $this->data['Candidato']['ano']);
			if($this->Estudante->possuiEvasao($estudante_id))
			{
				$this->data = $this->Evasao->getEvasaoPorEstudante($estudante_id);
				$this->data['Candidato']['nome'] = $this->Estudante->obterNome($estudante_id);
				$this->set('estudante_id', $estudante_id);
				$this->render('visualizar');	
			}
			else
			{
				$this->Session->setFlash('Estudante não possui evasão. Verifique os dados inseridos');
				$this->set('metodo_destino', 'visualizar');
				$this->render('inscricao_ano');	
			}
		}
		else
		{
                        if(!empty ($estudante_id)){
                                $this->data = $this->Evasao->getEvasaoPorEstudante($estudante_id);
                                $this->data['Candidato']['nome'] = $this->Estudante->obterNome($estudante_id);
                                $estudante = $this->Evasao->Estudante->read(null, $estudante_id);
                                $this->set('estudante', $estudante);
                                $this->render('visualizar');	
                        }else{
                                //carrrega a página para obtenção de dados
                                $this->set('metodo_destino', 'visualizar');
                                $this->render('inscricao_ano');                            
                        }

		}
	}

	function visualizar_direto($estudante_id)
	{
		$this->set('content_title', 'Visualizar Evasão');

		if($this->Estudante->possuiEvasao($estudante_id))
		{
			$this->data = $this->Evasao->getEvasaoPorEstudante($estudante_id);
			$this->data['Candidato']['nome'] = $this->Estudante->obterNome($estudante_id);
			$this->set('estudante_id', $estudante_id);
			$this->render('visualizar');	
		}
		else
		{
			$this->Session->setFlash('Estudante não possui evasão.');
			$this->render('branco');	
		}
	}

	function remover($estudante_id = 0)
	{

        if($this->Evasao->delete($this->Evasao->getEvasaoPorEstudante($estudante_id)))
		{
            $estudante = $this->Evasao->Estudante->read(null, $estudante_id);
            $estudante['Estudante']['evasao'] = 0;
            unset($estudante['Estudante']['created']);
            $this->Evasao->Estudante->create();
            if($this->Evasao->Estudante->save(array('Estudante' => $estudante['Estudante']))){
                $this->Session->setFlash('Evasão removida com sucesso');
            }else{
                $this->Session->setFlash('Evasão removida com sucesso sem atualizar estudante');
            }
		}
		else
		{
			$this->Session->setFlash('Evasão não pode ser removida. Entre em contato com os técnicos');
		}

        $this->redirect('/estudantes/index/');
	}

	function editar_direto($estudante_id = 0)
	{
		$this->set('content_title', 'Editar Evasão');

		if(isset($this->data['Evasao']))
		{
			$this->data['Evasao']['estudante_id'] = $estudante_id;

			//edita evasão
			if($this->Evasao->save($this->data))
			{
				$this->Session->setFlash('Evasão alterada com sucesso');
				$this->redirect('/estudantes/index/');
			}
			else
			{
				$this->Session->setFlash('Não foi possível alterar a evasão. Verifique se faltou algum campo para ser preenchido. Se ainda assim continuar com o problema, notifique o suporte técnico');
				$this->redirect('/estudantes/index/');
			}
		}
		else
		{
                        $estudante = $this->Evasao->Estudante->read(null, $estudante_id);
                        $this->set('estudante', $estudante);
			$this->data = $this->Evasao->getEvasaoPorEstudante($estudante_id);
			$this->set('estudante_id', $estudante_id);
			$this->set('metodo_destino', 'editar_direto');
			$this->render('evasao');
		}
	}
}
?>
