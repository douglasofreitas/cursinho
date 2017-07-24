<?php 
/**
 * Classe correspondente ao Módulo Candidatos
 */
class RespostaQuestaoProvasController extends AppController {

	//var $scaffold;

	var $layout = 'tricolor_layout';
	var $pageTitle = 'candidatos';
	var $paginate;

	//var $helpers = array('Chart');
	function confirmar_preenchimento($pode_aprovar_candidato, $numero_inscricao, $ano)
	{
		App::import('Model', 'Candidato');
		$this->Candidato = new Candidato();
		$candidato_id = $this->Candidato->obterId($numero_inscricao, $ano);

		//verifica se pode aprovar o candidato na fase eliminatória
		if($pode_aprovar_candidato == '1')
		{
			//aprovar candicato
			if($this->Candidato->setCandidatoEliminatoria($numero_inscricao, $ano, 1))
			{
				//$this->Session->setFlash('Respostas cadastradas com sucesso.');
			}
			else
			{
				$this->Session->setFlash('Falha ao permitir a aprovação do candidato na fase eliminatória.');
			}
		}

		//verifica se o candidato ja preencheu a prova
		if($this->Candidato->fezProva($candidato_id))
		{
			//ja fez prova, então eu devo alterar o gabarito.
			$this->redirect('/resposta_questao_provas/alterar/'.$numero_inscricao.'/'.$ano);
		}
		else
		{
			$this->redirect('/resposta_questao_provas/inserir/'.$numero_inscricao.'/'.$ano);
		}
	}
	function inserir($numero_inscricao, $ano)
	{
		App::import('Model', 'Cantidato');
		$this->Candidato = new Candidato();
		$candidato_id = $this->Candidato->obterId($numero_inscricao, $ano);

		if (!empty($this->data['RespostaQuestaoProva']))
		{
			//obem o numero de questoes
			App::import('Model', 'QuestaoProva');
			$this->QuestaoProva = new QuestaoProva();
			App::import('Model', 'Prova');
			$this->Prova = new Prova();
			$numero_questoes = $this->QuestaoProva->numeroQuestoesProva($this->Prova->obterId($ano));
			//$questoes_prova = $this->QuestaoProva->obterId($numero_inscricao, $ano);

			//inclui cada resposta informada
			$resposta_form = array();
			$resposta_form['RespostaQuestaoProva']['candidato_id'] = $candidato_id;
			$resposta_form['RespostaQuestaoProva']['questao_prova_id'] = '';
			$resposta_form['RespostaQuestaoProva']['alternativa_marcada'] = '';

			$msg_erro = '';

			$pode_adicionar = false;
			$erro = false;
			$vetor_resposta = array();

			for ($resposta_id = 1; $resposta_id <= $numero_questoes; $resposta_id++) {

				if(!$erro)
				{

					$resposta_form['RespostaQuestaoProva']['questao_prova_id'] = $this->QuestaoProva->obterId($this->Prova->obterId($ano), $resposta_id);

					$pode_adicionar = false;

					foreach ($this->data['RespostaQuestaoProva'] as $campo => $valor)
					{
						if (preg_match("/^alternativa_marcada" . $resposta_id . "/", $campo))
						{
							$resposta_form['RespostaQuestaoProva']['alternativa_marcada'] = $valor;
							$pode_adicionar = true;
						}									
					}
					//armazena a resposta, mesmo sendo nula, para o calculo da nota
					$vetor_resposta[$resposta_id] = $resposta_form['RespostaQuestaoProva']['alternativa_marcada'];

					if($pode_adicionar)
					{

						//adiciona na tabela
						$this->RespostaQuestaoProva->create();

						if (!$this->RespostaQuestaoProva->save($resposta_form))
						{
							$erro = true;
							$msg_erro .=  ' '.$resposta_id.' ';
						}
					}
				}				
			}

			//calcular a nota do candidato
			$questoes = $this->QuestaoProva->getAllQuestoesProva($this->Prova->obterId($ano));
			$nota_prova = 0;
			foreach($questoes as $questao)
			{
				if($questao['QuestaoProva']['anulada'] == 0)
					if($questao['QuestaoProva']['alternativa_correta'] == $vetor_resposta[$questao['QuestaoProva']['numero_questao']])
						$nota_prova++;
			}

			//salvar no candidato a nota da prova
			if(!$erro)
			{
				App::import('Model', 'Candidato');
				$this->Candidato = new Candidato();

				if(!$this->Candidato->setNotaProva($numero_inscricao, $ano, $nota_prova))
					$erro = true;
			}

			if($erro)
			{
				$this->Session->setFlash('Houve um erro durante a inclusão das respostas de número: '.$msg_erro);
			}
			else
			{
				$this->Session->setFlash('Respostas cadastradas com sucesso.');
			}

			//$this->render('sucesso');
			$this->redirect('/provas/preencher');

		}
		else
		{
			if($this->Candidato->fezProvaEspecial($candidato_id))
			{
				//prova especial
				$this->redirect('/candidatos/inserir_nota_prova_especial/'.$numero_inscricao.'/'.$ano);
			}
			else
			{
				//prova tradicional
				$this->set('content_title', 'Preencher Prova');

				//pega o numero de questões da prova para formar o formulário
				App::import('Model', 'QuestaoProva');
				$this->QuestaoProva = new QuestaoProva();
				App::import('Model', 'Prova');
				$this->Prova = new Prova();
				$numero_questoes = $this->QuestaoProva->numeroQuestoesProva($this->Prova->obterId($ano));

				$this->set('numero_inscricao', $numero_inscricao);
				$this->set('ano', $ano);
				$this->set('numero_questoes', $numero_questoes);
				//formulario ainda não preenchido
			}
		}
	}

	function alterar($numero_inscricao, $ano)
	{
		//obem o id do candidato
		App::import('Model', 'Cantidato');
		$this->Candidato = new Candidato();
		$candidato_id = $this->Candidato->obterId($numero_inscricao, $ano);

		if (!empty($this->data['RespostaQuestaoProva']))
		{
			//obem o numero de questoes
			App::import('Model', 'QuestaoProva');
			$this->QuestaoProva = new QuestaoProva();
			App::import('Model', 'Prova');
			$this->Prova = new Prova();
			$numero_questoes = $this->QuestaoProva->numeroQuestoesProva($this->Prova->obterId($ano));
			//$questoes_prova = $this->QuestaoProva->obterId($numero_inscricao, $ano);

			//inclui cada resposta informada
			$resposta_form = array();
			$resposta_form['candidato_id'] = $candidato_id;
			$resposta_form['questao_prova_id'] = '';
			$resposta_form['alternativa_marcada'] = '';
			$resposta_form['resposta_questao_prova_id'] = '';

			$pode_adicionar = false;
			$erro = false;
			$vetor_resposta = array();

			for ($resposta_id = 1; $resposta_id <= $numero_questoes; $resposta_id++) {

				if(!$erro)
				{

					$resposta_form['questao_prova_id'] = $this->QuestaoProva->obterId($this->Prova->obterId($ano), $resposta_id);

					$pode_adicionar = false;

					foreach ($this->data['RespostaQuestaoProva'] as $campo => $valor)
					{
						if (preg_match("/^alternativa_marcada" . $resposta_id . "/", $campo))
						{
							$resposta_form['alternativa_marcada'] = $valor;
							$resposta_form['resposta_questao_prova_id'] = $this->RespostaQuestaoProva->obterId($candidato_id, $resposta_form['questao_prova_id']);
							$pode_adicionar = true;
						}									
					}
					//armazena a resposta, mesmo sendo nula, para o calculo da nota
					$vetor_resposta[$resposta_id] = $resposta_form['alternativa_marcada'];

					if($pode_adicionar)
					{

						//adiciona na tabela
						$this->RespostaQuestaoProva->create();

						$this->RespostaQuestaoProva->set($resposta_form);

						if (!$this->RespostaQuestaoProva->save())
							$erro = true;

					}
				}				
			}

			//calcular a nota do candidato
			$questoes = $this->QuestaoProva->getAllQuestoesProva($this->Prova->obterId($ano));
			$nota_prova = 0;
			foreach($questoes as $questao)
			{
				if($questao['QuestaoProva']['anulada'] == 0)
					if($questao['QuestaoProva']['alternativa_correta'] == $vetor_resposta[$questao['QuestaoProva']['numero_questao']])
						$nota_prova++;
			}

			//salvar no candidato a nota da prova
			if(!$erro)
			{
				if(!$this->Candidato->setNotaProva($numero_inscricao, $ano, $nota_prova))
					$erro = true;
			}

			if($erro)
			{
				$this->Session->setFlash('Houve um erro durante a inclusão das respostas.');
			}
			else
			{
				$this->Session->setFlash('Respostas alteradas com sucesso.');
			}

			//$this->render('sucesso');
			$this->redirect('/provas/preencher');

		}
		else
		{
			if($this->Candidato->fezProvaEspecial($candidato_id))
			{
				//prova especial
				$this->redirect('/candidatos/alterar_nota_prova_especial/'.$numero_inscricao.'/'.$ano);
			}
			else
			{
				$this->set('content_title', 'Alterar as respostas da prova');

				App::import('Model', 'QuestaoProva');
				$this->QuestaoProva = new QuestaoProva();
				App::import('Model', 'Prova');
				$this->Prova = new Prova();

				//pega o numero de questões da prova para formar o formulário
				$numero_questoes = $this->QuestaoProva->numeroQuestoesProva($this->Prova->obterId($ano));

				//pegar as respostas do candidato
				$this->RespostaQuestaoProva->create();
				$respostas = $this->RespostaQuestaoProva->getRespostasCandidato($this->Candidato->obterId($numero_inscricao, $ano));

				$this->set('numero_inscricao', $numero_inscricao);
				$this->set('ano', $ano);
				$this->set('numero_questoes', $numero_questoes);
				$this->set('respostas', $respostas);
				//formulario ainda não preenchido
			}	
		}
	}

	function visualizar($numero_inscricao, $ano)
	{
		$this->set('content_title', 'Visualizar respostas da prova');

		App::import('Model', 'Candidato');
		$this->Candidato = new Candidato();
		App::import('Model', 'QuestaoProva');
		$this->QuestaoProva = new QuestaoProva();
		App::import('Model', 'Prova');
		$this->Prova = new Prova();

		$candidato_id = $this->Candidato->obterId($numero_inscricao, $ano);
		if($this->Candidato->fezProvaEspecial($candidato_id))
		{
			//prova especial, somente pega a nota do candidato

			$dados = $this->Candidato->getCandidato($numero_inscricao, $ano);
			$this->set('nota_prova', $dados['Candidato']['nota_prova']);
			$this->set('numero_inscricao', $numero_inscricao);
			$this->set('ano', $ano);
			$this->render('visualizar_prova_especial');
		}
		else
		{
			$prova_id = $this->Prova->obterId($ano);

			$respostas = $this->RespostaQuestaoProva->getRespostasCandidato($candidato_id);
			$num_questoes = $this->QuestaoProva->numeroQuestoesProva($prova_id);

			$this->set('respostas', $respostas);
			$this->set('num_questoes', $num_questoes);
			$this->set('numero_inscricao', $numero_inscricao);
			$this->set('ano', $ano);
		}
	}
	function beforeFilter() {
		parent::beforeFilter(); 
		$this->set('moduloAtual', 'candidatos');
	}
}
?>