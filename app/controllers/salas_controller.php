<?php
class SalasController extends AppController {
	//var $scaffold;
	var $layout = 'tricolor_layout';
	var $pageTitle = 'coordenador';
	var $paginate;
	var $helpers = array('Chart');
	var $uses = array('Sala', 'Estudante', 'Unidade');
	function index()
	{
		//$this->set('content_title', 'Salas');
	}

        function beforeFilter() {
		parent::beforeFilter(); 

                $array_unidade = $this->Unidade->getSelectForm();
                $this->set('unidades', $array_unidade);
	}

	function inserir($ano = 0)
	{
		$this->set('content_title', 'Inserir Salas');

		if(!empty($this->data))
		{
			if(isset($this->data['AnoLetivo']))
			{
				if($this->data['AnoLetivo']['ano'] != '' and  $this->data['AnoLetivo']['ano'] != ' ')
				{
					//obter dados de sala
					$this->set('ano', $this->data['AnoLetivo']['ano']);
					$this->set('metodo_destino', 'inserir');
					$this->data['Sala']['ano_letivo'] = $this->data['AnoLetivo']['ano'];
					$this->render('sala');
				}
				else
				{
					//obter processo seletivo.
					$this->Session->setFlash('Insira um ano válido');
					$this->set('metodo_destino', 'inserir');
					$this->render('ano_letivo');
				}
			}
			elseif (isset($this->data['Sala']))
			{
				//verifica se existe o número da sala.
				if(!$this->Sala->existeSala($this->data['Sala']['ano_letivo'], $this->data['Sala']['numero']))
				{
					if($this->Sala->save($this->data))
					{
						$this->Session->setFlash('Sala cadastrada.');
						$this->redirect('/salas/visualizar_direto/'.$this->data['Sala']['ano_letivo']);
					}
					else
					{
						$this->Session->setFlash('Não foi possivel cadastraar a sala. Entre em contato com o suporte. ');
						$this->redirect('/estudantes/index');
					}
				}
				else
				{
					$this->Session->setFlash('Número da sala ja registrado. Verifique as informalções informadas');
					$this->set('metodo_destino', 'inserir');
					$this->render('sala');
				}
			}
		}
		else
		{
			//obter processo seletivo.
			$this->set('metodo_destino', 'inserir');
			$this->render('ano_letivo');
		}
	}
	function inserir_direto($ano)
	{
		//obter dados de sala
		$this->set('ano', $ano);
		$this->set('metodo_destino', 'inserir');
		$this->data['Sala']['ano_letivo'] = $ano;
		$this->render('sala');
	}
	function visualizar($ano=0)
	{
		$this->set('content_title', 'Visualizar Salas');
		if(!empty($this->data))
		{
			$ano = $this->data['AnoLetivo']['ano'];
			if($ano != '' and $ano != ' ')
			{
                            $this->data = $this->Sala->getAllSalasPorAnoLetivo($ano);
                            $this->set('ano', $ano);
                            $this->render('visualizar');
			}
			else
			{
				//obter processo seletivo.
				$this->Session->setFlash('Insira um ano válido');
				$this->set('metodo_destino', 'visualizar');
				$this->render('ano_letivo');
			}

		}
		else
		{
			//carrrega a página para obtenção de dados
			$this->set('metodo_destino', 'visualizar');
			$this->render('ano_letivo');
		}
	}
	function editar_direto($sala_id)
	{
		$this->set('content_title', 'Editar Sala');

		if(isset($this->data['Sala']))
		{
			//edita advertencia
			if($this->Sala->save($this->data))
			{
				$this->Session->setFlash('Sala alterada com sucesso');
				$this->redirect('/salas/visualizar_direto/'.$this->data['Sala']['ano_letivo']);
			}
			else
			{
				$this->Session->setFlash('Não foi possível alterar a sala. Verifique se faltou algum campo para ser preenchido. Se ainda assim continuar com o problema, notifique o suporte técnico');
				$this->render('sala');
			}
		}
		else
		{
			$this->data = $this->Sala->getSala($sala_id);
			$this->set('metodo_destino', 'editar_direto');
			$this->render('sala');
		}
	}
	function visualizar_direto($ano)
	{
		$this->set('content_title', 'Visualizar Salas');
		$this->data = $this->Sala->getAllSalasPorAnoLetivo($ano);
		$this->set('ano', $ano);
		$this->render('visualizar');
	}
	function remover($sala_id)
	{
		//verifica se possui alunos na sala selecionada
		if($this->Sala->possuiEstudantes($sala_id))
		{
			//não pode remover
			$this->Session->setFlash('Não é possivel remover a sala, pois há estudantes alocados nela. Remova os estudantes antes de remover.');
			$this->redirect('/estudantes/index');
		}
		else
		{
			//remover a sala
			$this->Sala->create();
			$this->Sala->id = $sala_id;
			$sala = $this->Sala->getSala($sala_id);
			if($this->Sala->delete())
			{
				$this->Session->setFlash('Sala removida.');
				$this->redirect('/salas/visualizar_direto/'.$sala['Sala']['ano_letivo']);
			}
			else
			{
				$this->Session->setFlash('Não foi possivel remover a sala, altere os dados da sala ou entre em contato com os técnicos.');
				$this->redirect('/estudantes/index');
			}
		}
	}
	function alocar_estudantes()
	{
		$this->set('content_title', 'Alocar Estudantes');
		if(!empty($this->data))
		{
			$ano = $this->data['AnoLetivo']['ano'];
			if($ano != '' and $ano != ' ')
			{
				if($this->Sala->possuiSalas($ano))
				{
					if ($this->Sala->Estudante->possuiEstudantesAlocados($ano)) {
						$this->Session->setFlash('Já há estudantes alocados neste ano, por favor a alocação manualmente, através da ficha do estudante, ou remova os estudantes das salas para fazer a alocação automática.');
						$this->set('metodo_destino', 'alocar_estudantes');
						$this->render('ano_letivo');
					} else {
						//com o ano definido e com a confirmação da existencia de salas, deve ser pedido qual o critério de ordenação será utilizado.
						$this->redirect('/salas/definir_criterio_alocacao/'.$ano);
					}
				}
				else
				{
					$this->Session->setFlash('Este ano letivo não possui salas. Por favor cadastre para usar esta função.');
					$this->set('metodo_destino', 'alocar_estudantes');
					$this->render('ano_letivo');
				}
			}
			else
			{
				//obter processo seletivo.
				$this->Session->setFlash('Insira um ano válido');
				$this->set('metodo_destino', 'alocar_estudantes');
				$this->render('ano_letivo');
			}
		}
		else
		{
			//carrrega a página para obtenção de dados
			$this->set('metodo_destino', 'alocar_estudantes');
			$this->render('ano_letivo');
		}
	}
	/*
	 * São definidos dois critérios:
	 * 1 = por nota de prova
	 * 2 = por idade
	 */
	function definir_criterio_alocacao($ano, $criterio='')
	{
		$this->set('content_title', 'Alocar Estudantes');
		if($criterio != '')
		{
			if($criterio == 'nota')
			{
				// por nota
				//obter estudantes por nota de prova da ufscar
				$estudantes = $this->Sala->Estudante->getAllEstudantesPorNotaProvaUnidade($ano, 'ufscar');
				if($estudantes)
					$this->def_alocar_estudantes($ano, $estudantes, 'ufscar');
				else {
					$this->Session->setFlash('Nenhum estudante para ser alocado da unidade UFSCar.');
				}
				//obter estudantes por nota de prova de aracy
				$estudantes = $this->Sala->Estudante->getAllEstudantesPorNotaProvaUnidade($ano, 'aracy');
				if($estudantes)
					$this->def_alocar_estudantes($ano, $estudantes, 'aracy');
				else {
					$this->Session->setFlash('Nenhum estudante para ser alocado da unidade Aracy.');
				}

				// fim da alocação
				$this->redirect('/salas/visualizar_alocacao_estudantes_direto/'.$ano);
			}
			else
			{
				if($criterio == 'idade')
				{
					// por idade da UFSCar
					$estudantes = $this->Sala->Estudante->getAllEstudantesPorIdadeUnidade($ano, 'ufscar');
					if($estudantes)
						$this->def_alocar_estudantes($ano, $estudantes, 'ufscar');
					else {
						$this->Session->setFlash('Nenhum estudante para ser alocado da unidade UFSCar.');
					}
					// por idade de Aracy
					$estudantes = $this->Sala->Estudante->getAllEstudantesPorIdadeUnidade($ano, 'aracy');
					if($estudantes)
						$this->def_alocar_estudantes($ano, $estudantes, 'aracy');
					else {
						$this->Session->setFlash('Nenhum estudante para ser alocado da unidade Aracy.');
					}

					// fim da alocação
					$this->redirect('/salas/visualizar_alocacao_estudantes_direto/'.$ano);
				}
				else
				{
					//Não é uma escolha válida.
					$this->Session->setFlash('Escolha um critério válido.');
					$this->set('ano', $ano);
					$this->render('criterio_alocacao');
				}
			}
		}
		else
		{
			//escolher o critério
			$this->set('ano', $ano);
			$this->render('criterio_alocacao');
		}
	}
	function def_alocar_estudantes($ano, $estudantes, $unidade)
	{
		//obter as salas do ano selecionado
		$salas = $this->Sala->getAllSalasPorAnoLetivoUnidade($ano, $unidade);
		$num_salas = count($salas);
		//preparando variaveis de controle
		$num_estudantes = count($estudantes);
		$estudantes_alocados = 0;
		$estudante_atual = 0;
		$erro=0;
		//para cada sala, alocar cada estudante
		foreach($salas as $sala)
		{
			$data = array();
			$vagas = $sala['Sala']['quantidade_vagas'];
			$vagas_ocupadas = 0;
			$sala_id = $sala['Sala']['sala_id'];
			while($estudante_atual < $num_estudantes and $vagas_ocupadas < $vagas and $erro==0)
			{
				//alocar um estudante na sala atual.
				$data['Estudante']['estudante_id'] = $estudantes[$estudante_atual]['Estudante']['estudante_id'];
				$data['Estudante']['sala_id'] = $sala_id;
				if($this->Sala->Estudante->save($data))
				{
					//alocou estudante.
					$estudantes[$estudante_atual]['Estudante']['sala_id'] = $sala_id;
					$estudante_atual++;
					$vagas_ocupadas++;
				}
				else
				{
					//não conseguiu alocar o estudante.
					$erro=1;
				}
			}

		}

		//verifica se houve erro
		if($erro==1)
		{
			//trata erro, desfaz alocação
			foreach($estudantes as $estudante)
			{
				if ($estudante['Estudante']['sala_id'] >= 0)
				{
					$data = array();
					$data['Estudante']['estudante_id'] = $estudante['Estudante']['estudante_id'];
					$data['Estudante']['sala_id'] = $estudante['Estudante']['sala_id'];
					$this->Sala->Estudante->save($data);
				}
			}
			//redireciona
			$this->Session->setFlash('Houve um erro na alocação dos estudantes. Avise os técnicos.');
			$this->redirect('/salas/visualizar_alocacao_estudantes_direto/'.$ano);
		}
	}
	function desalocar_estudantes()
	{
		$this->set('content_title', 'Alocar Estudantes');
		if(!empty($this->data))
		{
			$ano = $this->data['AnoLetivo']['ano'];
			if($ano != '' and $ano != ' ')
			{
				if($this->Sala->possuiSalas($ano))
				{
					if ($this->Sala->Estudante->possuiEstudantesAlocados($ano)) {
						//com o ano definido e com a confirmação da existencia de salas, deve ser pedido qual o critério de ordenação será utilizado.
						$this->redirect('/salas/confirma_desalocacao/'.$ano);
					} else {
						$this->Session->setFlash('Não há estudantes para serem removidos de salas de aula.');
						$this->set('metodo_destino', 'desalocar_estudantes');
						$this->render('ano_letivo');
					}
				}
				else
				{
					$this->Session->setFlash('Este ano letivo não possui salas.');
					$this->set('metodo_destino', 'desalocar_estudantes');
					$this->render('ano_letivo');
				}
			}
			else
			{
				//obter processo seletivo.
				$this->Session->setFlash('Insira um ano válido');
				$this->set('metodo_destino', 'desalocar_estudantes');
				$this->render('ano_letivo');
			}
		}
		else
		{
			//carrrega a página para obtenção de dados
			$this->set('metodo_destino', 'desalocar_estudantes');
			$this->render('ano_letivo');
		}
	}
	function confirma_desalocacao($ano, $resposta='sem_resposta')
	{
		if($resposta != 'sem_resposta'){
			if($resposta == 'sim')
			$this->redirect('/salas/desalocar_estudantes_direto/'.$ano);
			else
			$this->redirect('/salas/visualizar_alocacao_estudantes_direto/'.$ano);
		} else{
			$this->set('ano', $ano);
		}
	}
	function desalocar_estudantes_direto($ano)
	{
		//obter estudantes do ano selecionado para remover da sala alocado.
		$estudantes = $this->Sala->Estudante->getEstudantesAlocados($ano);
		$erro = 0;
		foreach ($estudantes as $estudante) {
			$dados = $estudante;
			$dados['Estudante']['sala_id'] = null;
			if(!$this->Sala->Estudante->save($dados))
			$erro = 1;
		}
		if ($erro == 1) {
			$this->Session->setFlash('Houve um erro durante a remoção dos estudantes. Por favor notificar os técnicos.');
		} else {
			$this->Session->setFlash('Remoção feita com sucesso.');
		}
		$this->redirect('/salas/visualizar_alocacao_estudantes_direto/'.$ano);
	}
	function visualizar_alocacao_estudantes()
	{
		$this->set('content_title', 'Visualizar alocação de estudantes');
		if(!empty($this->data))
		{
			$ano = $this->data['AnoLetivo']['ano'];
			if($ano != '' and $ano != ' ')
			{
				if($this->Sala->possuiSalas($ano))
				{
					$this->redirect('/salas/visualizar_alocacao_estudantes_direto/'.$ano);
				}
				else
				{
					$this->Session->setFlash('Este ano letivo não possui salas. Por favor cadastre para usar esta função.');
					$this->set('metodo_destino', 'visualizar_alocacao_estudantes');
					$this->render('ano_letivo');
				}
			}
			else
			{
				//obter processo seletivo.
				$this->Session->setFlash('Insira um ano válido');
				$this->set('metodo_destino', 'visualizar_alocacao_estudantes');
				$this->render('ano_letivo');
			}
		}
		else
		{
			//carrrega a página para obtenção de dados
			$this->set('metodo_destino', 'visualizar_alocacao_estudantes');
			$this->render('ano_letivo');
		}
	}
	function visualizar_alocacao_estudantes_direto($ano)
	{
		$this->set('content_title', 'Visualizar alocação de estudantes');
		//gerar as listas para serem exibidas
		//...
		$num_estudantes_alocados = 0;
		$salas = $this->Sala->getAllSalasPorAnoLetivo($ano);
		$num_salas = count($salas);
		$estudantes_sala = array();
		//para cada sala, coletar os estudantes alocados nela.
		foreach($salas as $sala)
		{
			//obter estundantes que estão na determinada sala
			$estudantes_sala[$sala['Sala']['sala_id']] = $this->Sala->Estudante->getAllEstudantesPorSala($sala['Sala']['sala_id']);
			$num_estudantes_alocados += count($estudantes_sala[$sala['Sala']['sala_id']]);
		}
		//contar também quantos alunos não foram alocados.
		$num_estudantes_nao_alocados = 0;
		$estudantes_nao_alocados = $this->Sala->Estudante->getEstudantesNaoAlocados($ano);
		if($estudantes_nao_alocados)
		if(count($estudantes_nao_alocados)>0)
		{
			$num_estudantes_nao_alocados = count($estudantes_nao_alocados);
		}
		$this->set('ano', $ano);
		$this->set('salas', $salas);
		$this->set('num_salas', $num_salas);
		$this->set('estudantes_sala', $estudantes_sala);
		$this->set('num_estudantes_nao_alocados', $num_estudantes_nao_alocados);
		$this->set('num_estudantes_alocados', $num_estudantes_alocados);
		$this->set('estudantes_nao_alocados', $estudantes_nao_alocados);
		$this->render('visualizar_alocacao_estudantes');
	}
}
?>
