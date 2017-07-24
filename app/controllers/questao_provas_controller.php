<?php 
/**
 * Classe correspondente ao Módulo Candidatos
 */
class QuestaoProvasController extends AppController {

	//var $scaffold;

	var $layout = 'tricolor_layout';
	var $pageTitle = 'candidatos';
	var $paginate;

	//var $helpers = array('Chart');

	function index()
	{

	}

	function inserir($prova_id)
	{
		$this->set('content_title', 'Inserir questão de prova'); 

		//pegar habilidades avaliadas
		App::import('Model', 'HabilidadeAvaliada');
		$this->HabilidadeAvaliada = new HabilidadeAvaliada();
		App::import('Model', 'Prova'); 
 		$this->Prova = new Prova(); 
		$habilidadesAvaliadas = $this->HabilidadeAvaliada->getHabilidades();
		$habilidades = array();
		$string = '';
		//após pegar do banco de dados, separar as habilidade para um array para inserir no form
		foreach($habilidadesAvaliadas as $hab)
		{
			//print_r($hab['HabilidadeAvaliada']);

			$string = $string."'".$hab['HabilidadeAvaliada']['habilidade_avaliada_id']."' => '".$hab['HabilidadeAvaliada']['habilidade']."',";
		}
		$string = substr($string, 0, -1);
		$string = '$habilidades = array('.$string.');';

		eval($string);

		//definir o número da próxima questão
		$numero_questoes = $this->QuestaoProva->numeroQuestoesProva($prova_id);

		$this->set('prova_id', $prova_id);
		$this->set('numero_questao', $numero_questoes+1);
		$this->set('habilidades', $habilidades);

		if (!empty($this->data))
		{
			if($this->data['QuestaoProva']['enunciado'] != '' and $this->data['QuestaoProva']['alternativa_correta'] != '')
			{
				if($this->data['QuestaoProva']['alternativa_correta'] != '')
				{
					$this->QuestaoProva = new QuestaoProva();
					if(!$this->QuestaoProva->save($this->data))
					{
						$this->Session->setFlash('Questão não cadastrada.');
					}
					else
					{
						unset($this->data);
						//$this->recalcula_notas_de_prova($this->Prova->obterAno($prova_id)); 
						$this->redirect('/questao_provas/inserir/'.$prova_id);	
					}
				}
			}
			else
			{
				$this->Session->setFlash('enunciado e alternativa correta são obrigatórios');
			}
		}
	}

	//este método exibe os formulários
	function alterar($prova_ano, $numero_questao)
	{
		$this->set('content_title', 'Alterar questão de prova');

		//pegar habilidades avaliadas
		App::import('Model', 'HabilidadeAvaliada');
		$this->HabilidadeAvaliada = new HabilidadeAvaliada();
		$habilidadesAvaliadas = $this->HabilidadeAvaliada->getHabilidades();
		$habilidades = array();
		$string = '';
		//após pegar do banco de dados, separar as habilidade para um array para inserir no form
		foreach($habilidadesAvaliadas as $hab)
		{
			//print_r($hab['HabilidadeAvaliada']);

			$string = $string."'".$hab['HabilidadeAvaliada']['habilidade_avaliada_id']."' => '".$hab['HabilidadeAvaliada']['habilidade']."',";
		}
		$string = substr($string, 0, -1);
		$string = '$habilidades = array('.$string.');';

		eval($string);

		//definir o número da próxima questão
		App::import('Model', 'Prova');
		$this->Prova = new Prova();
		$prova_id = $this->Prova->obterId($prova_ano);

		$this->set('prova_id', $prova_id);
		$this->set('prova_ano', $prova_ano);
		$this->set('numero_questao', $numero_questao);
		$this->set('habilidades', $habilidades);

		$this->data = $this->QuestaoProva->getQuestao($prova_id, $numero_questao);

	}

	function alterar_final($prova_ano, $numero_questao)
	{
		if (!empty($this->data))
		{
			if($this->data['QuestaoProva']['enunciado'] != '' and $this->data['QuestaoProva']['alternativa_correta'] != '')
			{
				if($this->data['QuestaoProva']['alternativa_correta'] != '')
				{
					$this->QuestaoProva = new QuestaoProva();
					App::import('Model', 'Prova');
					$this->Prova = new Prova();
					$prova_id = $this->Prova->obterId($prova_ano);
					$this->data['QuestaoProva']['questao_prova_id'] = $this->QuestaoProva->obterId($prova_id, $numero_questao);
					if(!$this->QuestaoProva->save($this->data))
					{
						$this->Session->setFlash('Questão não cadastrada.');
					}
					else
					{
						unset($this->data);
						$this->Session->setFlash('Questão alterada com sucesso.');
						//recalcula as notas dos candidatos que realizaram esta prova
						$this->recalcula_notas_de_prova($prova_ano);
						$this->redirect('/provas/visualizar/'.$prova_ano);	
					}
				}
			}
			else
			{
				$this->Session->setFlash('enunciado e alternativa correta são obrigatórios');
				$this->redirect('/questao_provas/alterar/'.$prova_ano.'/'.$numero_questao);
			}
		}

	}

	function remover($prova_ano, $numero_questao)
	{
		App::import('Model', 'Prova');
		$this->Prova = new Prova();
		App::import('Model', 'RespostaQuestaoProva');
		$this->RespostaQuestaoProva = new RespostaQuestaoProva();
		$prova_id = $this->Prova->obterId($prova_ano);

		$this->QuestaoProva->create();
		$questoes = $this->QuestaoProva->getAllQuestoesProva($prova_id);

		$sucesso_na_delacao = false;

		foreach($questoes as $questao)
		{
			if($questao['QuestaoProva']['numero_questao'] == $numero_questao)
			{
				//questao a ser eliminada

				$questao_prova_id = $this->QuestaoProva->obterId($prova_id, $numero_questao); 

				$this->QuestaoProva->create();
				$this->QuestaoProva->set('questao_prova_id', $questao_prova_id);

				if(!$this->RespostaQuestaoProva->existeResposta($questao_prova_id))
				{
					if($this->QuestaoProva->delete())
					{
						$this->recalcula_notas_de_prova($prova_ano);
						$sucesso_na_delacao = true;
						$this->Session->setFlash('Questão '.$numero_questao.' removida com sucesso.');
					}
				}
				else
				{
					$this->Session->setFlash('Não pode ser removida, pois há alunos que responderam esta questão e haverá perda de histórico.');
				}
			}
			else
			{
				if($questao['QuestaoProva']['numero_questao'] > $numero_questao)
				{
					if($sucesso_na_delacao)
					{
						//questao a ter o seu numero decrementado
						$questao_prova_id = $this->QuestaoProva->obterId($prova_id, $questao['QuestaoProva']['numero_questao']); 

						$this->QuestaoProva->create();
						$this->QuestaoProva->questao_prova_id = $questao_prova_id;

						$dados = $this->QuestaoProva->getQuestao($prova_id, $questao['QuestaoProva']['numero_questao']);

						$dados['QuestaoProva']['numero_questao']--;

						$this->QuestaoProva->save($dados);
					}
				}
			}
		}
		$this->redirect('/provas/visualizar/'.$prova_ano);	
	}

	function recalcula_notas_de_prova($prova_ano)
	{
		App::import('Model', 'Prova');
		$this->Prova = new Prova();
		App::import('Model', 'Candidato');
		$this->Candidato = new Candidato();
		App::import('Model', 'RespostaQuestaoProva');
		$this->RespostaQuestaoProva = new RespostaQuestaoProva();

		$candidatos = $this->Candidato->getAllCandidatosPorProva($prova_ano);

		foreach($candidatos as $candidato)
		{
			//para cada candidato, atualizar a nota de prova
			$candidato_id = $this->Candidato->obterId($candidato['Candidato']['numero_inscricao'], $candidato['Candidato']['ano']); 
			if($this->Candidato->fezProvaEspecial($candidato_id))
			{
				//prova especial, não faz o calculo
			}
			else
			{
				$questoes_prova = $this->QuestaoProva->getAllQuestoesProva($this->Prova->obterId($prova_ano));
				$respostas_questoes_prova = $this->RespostaQuestaoProva->getRespostasCandidato($candidato['Candidato']['candidato_id']); 
				$nota_prova = 0;
				foreach($questoes_prova as $questao)
				{
					if($questao['QuestaoProva']['anulada'] == 0)
					{
						$questao_prova_atual = $questao['QuestaoProva']['questao_prova_id'];

						//procurar a resposta correspondente a questao atual
						foreach($respostas_questoes_prova  as $resposta)
						{
							if($questao_prova_atual == $resposta['RespostaQuestaoProva']['questao_prova_id'])
							{
								if($questao['QuestaoProva']['alternativa_correta'] == $resposta['RespostaQuestaoProva']['alternativa_marcada'])
								{
									$nota_prova++;
								}
							}
						}
					}
				}	
				//agora a nota é atualizada no candidato
				$candidato['Candidato']['nota_prova'] = $nota_prova;
				$this->Candidato->save($candidato);
			}
		}	
	}
	function beforeFilter() {
		parent::beforeFilter(); 
		$this->set('moduloAtual', 'candidatos');
	}
}
?>