<?php 
class Estudante extends AppModel {

	var $name = 'Estudante';

	var $useTable = 'estudante';
	var $primaryKey = 'estudante_id';

	var $hasMany = array('Frequencia' => array('className' => 'Frequencia',
											   'foreignKey' => 'estudante_id'),

						 'Boletim' => array('className' => 'Boletim',
											'foreignKey' => 'estudante_id'),
                         'Fatura' => array('className' => 'Fatura',
                                           'foreignKey' => 'estudante_id'),
						 //'Mensalidade' => array('className' => 'Mensalidade',
						//						'foreignKey' => 'estudante_id'),

						 'Advertencia' => array('className' => 'Advertencia',
												'foreignKey' => 'estudante_id'),
                                                 'Evasao' => array('className' => 'Evasao',
										  'foreignKey' => 'estudante_id'),
                                                 'EstudanteTurma' => array('className' => 'EstudanteTurma',
										  'foreignKey' => 'estudante_id'));

	var $belongsTo = array(
                               'Turma' => array('className' => 'Turma',
										   'foreignKey' => 'turma_id'),

                               'Candidato' => array('className' => 'Candidato',
                                                                                    'foreignKey' => 'candidato_id'));

        function beforeSave($options) {
            //verifica se já há o estudante no banco de dados
            $condicao = array('Estudante.candidato_id' => $this->data['Estudante']['candidato_id'], 'Estudante.ano_letivo' => $this->data['Estudante']['ano_letivo']);
            if (!empty($this->data['Estudante']['data_inicio'])) {
                    $this->data['Estudante']['data_inicio'] = $this->dateFormatBeforeSave($this->data['CepFrete']['data_inicio']);
            }
            $estudante = $this->find('first', array('conditions' => $condicao,
                                                    'recursive' => '0'));
            if(!empty($this->data['Estudante']['estudante_id'])){
                return true;
            }else{
                if ($estudante){
                    return false;
                }else{
                    return true;
                }
            }


	}

    function mantem_consistencia(){

        //busca todos os estudantes
        $estudantes = $this->find('all', array('conditions' => array('Candidato.matriculado' => 0), 'fields' => array('Estudante.estudante_id') ) );

        if($estudantes){
            foreach($estudantes as $estud){
                $obj_estudante = new Estudante();
                $obj_estudante->create();
                //$obj_estudante->delete($estud['Estudante']['estudante_id']);
            }
        }
    }

        function obterId($numero_inscricao, $ano)
	{
		$condicao = array('Candidato.numero_inscricao' => $numero_inscricao, 'Estudante.ano_letivo' => $ano);

		$estudante = $this->find('first', array('conditions' => $condicao, 
												'recursive' => '0'));

		if ($estudante)
			return $estudante['Estudante']['estudante_id'];
		else
			return false;
	}

	function obterNome($estudante_id)
	{
		$condicao = array('Estudante.estudante_id' => $estudante_id);

		$estudante = $this->find('first', array('conditions' => $condicao, 
												'recursive' => '0'));

		if ($estudante)
			return $estudante['Candidato']['nome'];
		else
			return false;
	}

	function obterAno($estudante_id)
	{
		$condicao = array('Estudante.estudante_id' => $estudante_id);

		$estudante = $this->find('first', array('conditions' => $condicao,
												'recursive' => '0'));

		if ($estudante)
			return $estudante['Estudante']['ano_letivo'];
		else
			return false;
	}

	function obterNumeroInscricao($estudante_id)
	{
		$condicao = array('Estudante.estudante_id' => $estudante_id);

		$estudante = $this->find('first', array('conditions' => $condicao,
												'recursive' => '0'));

		if ($estudante)
			return $estudante['Candidato']['numero_inscricao'];
		else
			return false;
	}

	function obterIdPeloCandidatoId($candidato_id)
	{
		$condicao = array('Estudante.candidato_id' => $candidato_id);

		$estudante = $this->find('first', array('conditions' => $condicao,
												'recursive' => '0',
												'fields' => array('estudante_id')));

		if ($estudante)
			return $estudante['Estudante']['estudante_id'];
		else
			return false;
	}

    function removeEstudante($candidato_id){
        $estudante_id = $this->obterIdPeloCandidatoId($candidato_id);

        //verificar evasao e frequencia
        if($estudante_id){
            if(! $this->possuiEvasao($estudante_id)){
                if(! $this->possuiAdvertencia($estudante_id)){
                    if(! $this->possuiBoletim($estudante_id)){
                        $this->create();
                        $this->delete($estudante_id);
                        CakeLog::write('debug', 'Removendo estudante '.$estudante_id);
                    }
                }
            }
        }

    }

	function possuiEvasao($estudante_id)
	{
		$condicao = array('Evasao.estudante_id' => $estudante_id);

		$estudante = $this->Evasao->find('first', array('conditions' => $condicao, 
												'recursive' => '-1'));

		if ($estudante)
			return true;
		else
			return false;
	}

	function possuiAdvertencia($estudante_id)
	{
		$condicao = array('Advertencia.estudante_id' => $estudante_id);

		$estudante = $this->Advertencia->find('first', array('conditions' => $condicao, 
												'recursive' => '-1'));

		if ($estudante)
			return true;
		else
			return false;
	}

	function possuiBoletim($estudante_id)
	{
		$condicao = array('Boletim.estudante_id' => $estudante_id);

		$estudante = $this->Boletim->find('first', array('conditions' => $condicao, 
												'recursive' => '-1'));

		if ($estudante)
			return true;
		else
			return false;
	}

	function getEstudante($estudante_id)
	{
		$condicao = array('Estudante.estudante_id' => $estudante_id);

		$estudante = $this->find('first', array('conditions' => $condicao, 
												'recursive' => '1'));

		if ($estudante)
			return $estudante;
		else
			return false;
	}

	function getEstudanteIds($candidatos_id){
		$condicao = array('Estudante.candidato_id' => $candidatos_id);

		$estudantes = $this->find('all', array('conditions' => $condicao, 
												'recursive' => '0'));

		if ($estudantes)
			return $estudantes;
		else
			return false;
	}
	
	function getAllEstudantesPorNotaProva($ano)
	{		
		$condicao = array('Estudante.evasao' => '0',
			'OR' => array('Estudante.ano_letivo' => $ano, 
							'AND' => array('Estudante.ano_letivo' => $ano - 1,
								'Candidato.turma' => '2')));

		$estudantes = $this->find('all', array('conditions' => $condicao,
											   'order' => array('Candidato.nota_prova' => 'desc',
																'Candidato.numero_inscricao' => 'asc')));

		if ($estudantes)
			return $estudantes;
		else 
			return null;
	}

	function getAllEstudantesPorNotaProvaUnidade($ano, $unidade)
	{		
		$condicao = array('Estudante.evasao' => '0', 'Candidato.unidade' => $unidade,
			'OR' => array('Estudante.ano_letivo' => $ano, 
							'AND' => array('Estudante.ano_letivo' => $ano - 1,
								'Candidato.turma' => '2')));

		$estudantes = $this->find('all', array('conditions' => $condicao,
											   'order' => array('Candidato.nota_prova' => 'desc',
																'Candidato.numero_inscricao' => 'asc')));

		if ($estudantes)
			return $estudantes;
		else 
			return null;
	}
	
	

	/*
	 * Precisa ser feito!
	 */
	function getAllEstudantesPorIdade($ano)
	{
		// Obtemos o candidato_id de todos os estudantes que n�o possuem evas�o
		$estudantes = $this->getAllEstudantesAtuais($ano);

		// Colocamos em um array apenas o candidato_id dos estudantes
		foreach ($estudantes as $estudante)
		{
			$ids[] = $estudante['Estudante']['candidato_id'];
		}	

		// Buscamos apenas pelos candidatos que s�o estudantes
		$condicao = array(
						'OR' => array('Estudante.ano_letivo' => $ano, 
							'AND' => array('Estudante.ano_letivo' => $ano - 1,
								'Candidato.turma' => '2')),
						'RespostaQuestaoQuestionario.questao_questionario_id' => '2',
						'RespostaQuestaoQuestionario.candidato_id' => $ids);

		$resultado = $this->Candidato->RespostaQuestaoQuestionario->find('all', array('conditions' => $condicao,
																					  'recursive' => '0',
																					  'fields' => array('candidato_id', 'resposta')));

		if ($resultado)
		{
			$estudantes = array();

			$contador = 0;

			// Para tupla obtida da tabela RespostaQuestaoQuestionario, pegamos a idade do candidato e o id do estudante
			foreach ($resultado as $r)
			{

				$resposta = simplexml_load_string($r['RespostaQuestaoQuestionario']['resposta']);

				$estudantes[$contador]['idade'] = (int)$resposta->campos->campo->valor;
				$estudantes[$contador]['estudante_id'] = $this->ObterIdPeloCandidatoId($r['RespostaQuestaoQuestionario']['candidato_id']);

				$contador++;
			}

			// Colocamos a idade e o id para fazermos uma ordena��o
			foreach ($estudantes as $key => $row)
			{
				$idade[$key] = $row['idade'];
				$estudante_id[$key] = $row['estudante_id'];
			}

			// Ordena o array primeiramente pela idade em ordem decrescente e depois pelo id em ordem crescente
			array_multisort($idade, SORT_DESC, $estudante_id, SORT_ASC, $estudantes);

			//obter os estudantes.
			$estudantes_final = array();
			foreach ($estudantes as $estudante)
			{
				$condicao = array('Estudante.estudante_id' => $estudante['estudante_id']);

				$estudantes_final[] =  $this->find('first', array('conditions' => $condicao, 
												'recursive' => '0'));
			}
		}

		if ($estudantes_final)
			return $estudantes_final;
		else 
			return null;
	}

	function getAllEstudantesPorIdadeUnidade($ano, $unidade)
	{
		// Obtemos o candidato_id de todos os estudantes que n�o possuem evas�o
		$estudantes = $this->getAllEstudantesAtuais($ano);

		// Colocamos em um array apenas o candidato_id dos estudantes
		foreach ($estudantes as $estudante)
		{
			$ids[] = $estudante['Estudante']['candidato_id'];
		}	

		// Buscamos apenas pelos candidatos que s�o estudantes
		$condicao = array(
						'OR' => array('Estudante.ano_letivo' => $ano, 
							'AND' => array('Estudante.ano_letivo' => $ano - 1,
								'Candidato.turma' => '2')),
						'RespostaQuestaoQuestionario.questao_questionario_id' => '2', 'Candidato.unidade' => $unidade,
						'RespostaQuestaoQuestionario.candidato_id' => $ids);

		$resultado = $this->Candidato->RespostaQuestaoQuestionario->find('all', array('conditions' => $condicao,
																					  'recursive' => '0',
																					  'fields' => array('candidato_id', 'resposta')));

		if ($resultado)
		{
			$estudantes = array();

			$contador = 0;

			// Para tupla obtida da tabela RespostaQuestaoQuestionario, pegamos a idade do candidato e o id do estudante
			foreach ($resultado as $r)
			{

				$resposta = simplexml_load_string($r['RespostaQuestaoQuestionario']['resposta']);

				$estudantes[$contador]['idade'] = (int)$resposta->campos->campo->valor;
				$estudantes[$contador]['estudante_id'] = $this->ObterIdPeloCandidatoId($r['RespostaQuestaoQuestionario']['candidato_id']);

				$contador++;
			}

			// Colocamos a idade e o id para fazermos uma ordena��o
			foreach ($estudantes as $key => $row)
			{
				$idade[$key] = $row['idade'];
				$estudante_id[$key] = $row['estudante_id'];
			}

			// Ordena o array primeiramente pela idade em ordem decrescente e depois pelo id em ordem crescente
			array_multisort($idade, SORT_DESC, $estudante_id, SORT_ASC, $estudantes);

			//obter os estudantes.
			$estudantes_final = array();
			foreach ($estudantes as $estudante)
			{
				$condicao = array('Estudante.estudante_id' => $estudante['estudante_id']);

				$estudantes_final[] =  $this->find('first', array('conditions' => $condicao, 
												'recursive' => '0'));
			}
		}

		if ($estudantes_final)
			return $estudantes_final;
		else 
			return null;
	}

	function getAllEstudantesPorSala($sala_id)
	{
		$condicao = array('Estudante.sala_id' => $sala_id);

		$estudantes = $this->find('all', array('conditions' => $condicao,
											   'recursive' => '1', 'order' => 'Candidato.numero_inscricao DESC'));

		if ($estudantes)
			return $estudantes;
		else
			return null;
	}

        function getAllEstudantesPorTurma($turma_id)
	{
		$condicao = array('Estudante.turma_id' => $turma_id);

		$estudantes = $this->find('all', array('conditions' => $condicao,
											   'recursive' => '1', 'order' => 'Candidato.numero_inscricao DESC'));

		if ($estudantes)
			return $estudantes;
		else
			return null;
	}

	function getAllEstudantesAtuais($ano_letivo)
	{
		$condicao = array(
			'OR' => array('Estudante.ano_letivo' => $ano_letivo, 
				'AND' => array('Estudante.ano_letivo' => $ano_letivo - 1,
					'Candidato.turma' => '2')),
			'Estudante.evasao' => 0);

		$estudantes = $this->find('all', array('conditions' => $condicao,
											   'recursive' => '0', 'order' => 'Candidato.numero_inscricao DESC'));

		if ($estudantes)
			return $estudantes;
		else
			return null;
	}

	function getEstudantesNaoAlocados($ano)
	{
		$condicao = array(
			'OR' => array('Estudante.ano_letivo' => $ano, 
							'AND' => array('Estudante.ano_letivo' => $ano - 1,
								'Candidato.turma' => '2')),
			'OR' => array(
				array('Estudante.turma_id' => 0),
				array('Estudante.turma_id' => null),
				array('Estudante.turma_id' => '')
			));

		$estudantes = $this->find('all', array('conditions' => $condicao,
											   'recursive' => '0', 'order' => 'Candidato.numero_inscricao DESC'));

		if ($estudantes)
			return $estudantes;
		else
			return null;
	}

	function listar_todos_sem_evasao($fields = null)
	{		
		$condicao = array('Estudante.evasao' => '0');

		$estudantes = $this->find('all', array('conditions' => $condicao,
											   'recursive' => '0',
											   'fields' => $fields));

		if ($estudantes)
			return $estudantes;
		else
			return null;
	}

	function listar_todos_sem_evasao_por_ano($ano)
	{
		$condicao = array('Estudante.ano_letivo' => $ano, 'Estudante.evasao' => '0');

		$estudantes = $this->find('all', array('conditions' => $condicao, 
												'recursive' => '0'));

		return $estudantes;
	}

	function getSala($estudante_id)
	{
		$condicao = array('Estudante.estudante_id' => $estudante_id);

		$estudante = $this->find('first', array('conditions' => $condicao, 
												'recursive' => '0'));

		if ($estudante)
			return $estudante['Sala'];
		else
			return false;
	}

        function getTurma($estudante_id)
	{
		$condicao = array('Estudante.estudante_id' => $estudante_id);

		$estudante = $this->find('first', array('conditions' => $condicao, 
												'recursive' => '0'));

		if ($estudante['Turma']['id'])
			return $estudante['Turma'];
		else
			return false;
	}

	function possuiEstudantesAlocados($ano)
	{
		$condicao = array(
			'OR' => array('Estudante.ano_letivo' => $ano,
							'AND' => array('Estudante.ano_letivo' => $ano - 1,
								'Candidato.turma' => '2')),
			'Estudante.evasao' => 0,
			'Estudante.turma_id > ' => 0);

		$estudantes = $this->find('all', array('conditions' => $condicao,
											   'recursive' => '0', 'order' => 'Candidato.numero_inscricao DESC'));

		if ($estudantes)
			return true;
		else
			return false;
	}

	function getEstudantesAlocados($ano)
	{
		$condicao = array(
			'OR' => array('Estudante.ano_letivo' => $ano, 
							'AND' => array('Estudante.ano_letivo' => $ano - 1,
								'Candidato.turma' => '2')),
			'Estudante.evasao' => 0,
			'Estudante.turma_id > ' => 0);

		$estudantes = $this->find('all', array('conditions' => $condicao,
											   'recursive' => '0', 'order' => 'Candidato.numero_inscricao DESC'));

		if ($estudantes)
			return $estudantes;
		else
			return null;
	}

        function salvar_valor_mensalidade($estudante_id, $valor_mensalidade){
            $estudante = $this->find('first', array('conditions' => array('Estudante.estudante_id' => $estudante_id),
                                                    'recursive' => '-1'));
            $estudante['Estudante']['valor_mensalidade'] = $valor_mensalidade;
            $this->save($estudante );
        }

        function grava_turma($estudante_id, $turma_id, $ano){

            //verifica se já tem associado uma turma no ano desejado
            $estudante_turma = $this->EstudanteTurma->find('first', array('conditions' => array('EstudanteTurma.estudante_id' => $estudante_id, 'EstudanteTurma.ano' => $ano)));
            if(!empty($estudante_turma)){
                //atualiza a turma
                $estudante_turma['EstudanteTurma']['turma_id'] = $turma_id;
            }else{
                //cria uma nova
                $estudante_turma = array();
                $estudante_turma['EstudanteTurma']['estudante_id'] = $estudante_id;
                $estudante_turma['EstudanteTurma']['turma_id'] = $turma_id;
                $estudante_turma['EstudanteTurma']['ano'] = $ano;
            }
            $this->EstudanteTurma->save($estudante_turma);
        }
		
	function getAnosLetivos(){
		$anos_letivos = $this->find('all', array('fields' => array('DISTINCT Estudante.ano_letivo'), 'recursive' => -1));
		
		return $anos_letivos;
	}
}
?>