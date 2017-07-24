<?php 
class Candidato extends AppModel {
	var $name = 'Candidato';
	var $useTable = 'candidato';
	var $primaryKey = 'candidato_id';
	var $hasMany = array(
            'RespostaQuestaoProva' => array('className' => 'RespostaQuestaoProva',
                'foreignKey' => 'candidato_id'),
            'RespostaQuestaoQuestionario' => array('className' => 'RespostaQuestaoQuestionario',
                'foreignKey' => 'candidato_id'),
            'Estudante' => array('clasName' => 'Estudante',
                'foreignKey' => 'candidato_id'),
            'Fatura' => array('className' => 'Fatura',
                'foreignKey' => 'candidato_id', 'order' => 'Fatura.id DESC',)
        );
	var $belongsTo = array(
                'Cidade' => array('className' => 'Cidade','foreignKey' => 'cidade'),
                'Unidade' => array('className' => 'Unidade','foreignKey' => 'unidade_id')
        );
	var $hasOne = array();
	var $validate = array(
		'numero_inscricao' => array('rule' => 'notEmpty',
									'message' => 'Você deve informar o número de inscrição do candidato'),
		'ano'			   => array('rule' => 'notEmpty',
									'message' => 'Você deve informar o ano'),
		'cpf'			   => array('rule' => 'valida_cpf',
									'message' => 'O CPF informado não é válido'),
		'nome'			   => array('rule' => 'notEmpty',
									'message' => 'O nome é obrigatório'),
	);
	var $order = array("Candidato.numero_inscricao" => "asc", "Candidato.ano" => "asc");
    function beforeSave($options) {
		if (!empty($this->data['Candidato']['nota_prova'])) {
	    		$this->data['Candidato']['nota_prova'] = str_replace(',', '.', $this->data['Candidato']['nota_prova']);
		}
        return true;
    }
	function naoExiste($numero_inscricao, $ano)
	{
		$condicao = array('numero_inscricao' => $numero_inscricao,
						  'ano' => $ano);
		if ($this->find('count', array('conditions' => $condicao, 'recursive' => '0')) == 0)
			return true;
		else
			return false;
	}
    function cpfExiste($cpf, $ano)
    {
        $condicao = array('cpf' => $cpf,
            'ano' => $ano);
        if ($this->find('count', array('conditions' => $condicao, 'recursive' => '0')) == 0)
            return true;
        else
            return false;
    }
	function existe($numero_inscricao, $ano)
	{		
		$condicao = array('numero_inscricao' => $numero_inscricao,
						  'ano' => $ano);
		if ($this->find('count', array('conditions' => $condicao, 'recursive' => '0')) > 0)
			return true;
		else
			return false;
	}
	function obterId($numero_inscricao, $ano)
	{
		$condicao = array('Candidato.numero_inscricao' => $numero_inscricao, 'Candidato.ano' => $ano);
		$candidato = $this->find('first', array('conditions' => $condicao, 
												'recursive' => '0',
												'fields' => array('candidato_id')));
		if ($candidato)
			return $candidato['Candidato']['candidato_id'];
		else
			return false;
	}
	function obterNome($numero_inscricao, $ano)
	{
		$condicao = array('Candidato.numero_inscricao' => $numero_inscricao, 'Candidato.ano' => $ano);
		$candidato = $this->find('first', array('conditions' => $condicao, 
												'recursive' => '0',
												'fields' => array('nome')));
		if ($candidato)
			return $candidato['Candidato']['nome'];
		else
			return false;
	}
	function getCandidato($numero_inscricao, $ano)
	{
		$condicao = array('Candidato.numero_inscricao' => $numero_inscricao, 'Candidato.ano' => $ano);
		$candidato = $this->find('first', array('conditions' => $condicao, 
												'recursive' => '0'));
		if ($candidato)
			return $candidato;
		else
			return false;
	}
        function getCandidatoById($id)
	{
		$condicao = array('Candidato.candidato_id' => $id);
		$candidato = $this->find('first', array('conditions' => $condicao, 
												'recursive' => '0'));
		if ($candidato)
			return $candidato;
		else
			return false;
	}
	/*
	 * Pega todos os candidatos que realizaram a prova
	 */

    function getByCPF($cpf)
    {
        $condicao = array('Candidato.cpf' => $cpf);
        $candidatos = $this->find('all', array('conditions' => $condicao,
            'recursive' => '0'));
        return $candidatos;
    }
	function getAllCandidatosPorProva($ano)
	{
		$condicao = array('Candidato.ano' => $ano, 'Candidato.nota_prova >=' => 0, 'Candidato.prova_especial' => 0);
		$candidatos = $this->find('all', array('conditions' => $condicao, 
												'recursive' => '0'));
		return $candidatos;
	}
	function getCandidatosAprovados($ano)
	{
		$condicao = array('Candidato.ano' => $ano, 'Candidato.fase_classificatoria_status' => '1');
		$candidatos = $this->find('all', array('conditions' => $condicao, 
												'recursive' => '0'));
		return $candidatos;
	}
	function getCandidatosIndigenas($ano, $turma)
	{
		$condicao = array(
			'RespostaQuestaoQuestionario.questao_questionario_id' => '29',
			'OR' => array(
				array('RespostaQuestaoQuestionario.resposta LIKE' => '%<campo><nome>qid29_chkOpcao_3</nome><tipo>checkBox</tipo><valor>1</valor></campo>%'),
				array('Candidato.cor' => 'indigena')
			),
			'Candidato.turma' => $turma,
			'Candidato.ano' => $ano,
			'Candidato.matriculado' => 0,
			'Candidato.fase_eliminatoria_status' => 1,
			'Candidato.fase_classificatoria_status' => 0);
		$resultado = $this->RespostaQuestaoQuestionario->find('all', array('conditions' => $condicao, 
												'recursive' => '0', 'order' => 'Candidato.nota_prova DESC'));
		if ($resultado)  
		{
			$candidatos = array();
			foreach ($resultado as $r)
			{
				//$candidatos[] = $r['Candidato'];
				$candidatos[] = $r;
			}
			return $candidatos;
		}
		else
			return null;
	}
	function getCandidatosIndigenasCount($ano, $turma)
	{
		$condicao = array(
			'RespostaQuestaoQuestionario.questao_questionario_id' => '29',
			'OR' => array(
				array('RespostaQuestaoQuestionario.resposta LIKE' => '%<campo><nome>qid29_chkOpcao_3</nome><tipo>checkBox</tipo><valor>1</valor></campo>%'),
				array('Candidato.cor' => 'indigena')
			),
			'Candidato.turma' => $turma,
			'Candidato.ano' => $ano,
			'Candidato.matriculado' => 0,
			'Candidato.fase_eliminatoria_status' => 1,
			'Candidato.fase_classificatoria_status' => 0);
		$count = $this->RespostaQuestaoQuestionario->find('count', array('conditions' => $condicao, 
												'recursive' => '0', 'order' => 'Candidato.nota_prova DESC'));
		return $count;
	}
	function getCandidatosAfrodescendentes($ano, $turma)
	{
		$condicao = array(
			'RespostaQuestaoQuestionario.questao_questionario_id' => '29',
			'OR' => array(
				array('RespostaQuestaoQuestionario.resposta LIKE' => '%<campo><nome>qid29_chkOpcao_1</nome><tipo>checkBox</tipo><valor>1</valor></campo>%'),
				array('Candidato.cor' => 'preto'),
				array('Candidato.cor' => 'pardo')
			),
			'Candidato.turma' => $turma,
			'Candidato.ano' => $ano,
			'Candidato.matriculado' => 0,
			'Candidato.fase_eliminatoria_status' => 1,
			'Candidato.fase_classificatoria_status' => 0);
		$resultado = $this->RespostaQuestaoQuestionario->find('all', array('conditions' => $condicao, 
												'recursive' => '0', 'order' => 'Candidato.nota_prova DESC'));
		if ($resultado)
		{
			$candidatos = array();
			foreach ($resultado as $r)
			{
				//$candidatos[] = $r['Candidato'];
				$candidatos[] = $r;
			}
			return $candidatos;
		}
		else
			return null;
	}
	function getCandidatosAfrodescendentesCount($ano, $turma)
	{
		$condicao = array(
			'RespostaQuestaoQuestionario.questao_questionario_id' => '29',
			'OR' => array(
				array('RespostaQuestaoQuestionario.resposta LIKE' => '%<campo><nome>qid29_chkOpcao_1</nome><tipo>checkBox</tipo><valor>1</valor></campo>%'),
				array('Candidato.cor' => 'preto'),
				array('Candidato.cor' => 'pardo')
			),
			'Candidato.turma' => $turma,
			'Candidato.ano' => $ano,
			'Candidato.matriculado' => 0,
			'Candidato.fase_eliminatoria_status' => 1,
			'Candidato.fase_classificatoria_status' => 0);
		$count = $this->RespostaQuestaoQuestionario->find('count', array('conditions' => $condicao, 
												'recursive' => '0', 'order' => 'Candidato.nota_prova DESC'));
		return $count;
	}
	function getCandidatosFabers($ano, $turma)
	{
		$condicao = array(
			'RespostaQuestaoQuestionario.questao_questionario_id' => '69',
			'Candidato.turma' => $turma,
			'Candidato.ano' => $ano,
			'Candidato.fase_classificatoria_status' => 0,
			'Candidato.fase_eliminatoria_status' => 1,
			'Candidato.matriculado' => 0,
			'RespostaQuestaoQuestionario.resposta REGEXP' => '.*<campo><nome>qid69_chk_2_[12]</nome><tipo>checkBox</tipo><valor>on</valor></campo>.*');
		$resultado = $this->RespostaQuestaoQuestionario->find('all', array('conditions' => $condicao, 
												'recursive' => '0', 'order' => 'Candidato.nota_prova DESC'));
		if ($resultado)
		{
			$candidatos = array();
			foreach ($resultado as $r)
			{
				//$candidatos[] = $r['Candidato'];
				$candidatos[] = $r;
			}
			return $candidatos;
		}
		else
			return null;
	}
	function getCandidatosFabersCount($ano, $turma)
	{
		$condicao = array(
			'RespostaQuestaoQuestionario.questao_questionario_id' => '69',
			'Candidato.turma' => $turma,
			'Candidato.ano' => $ano,
			'Candidato.fase_classificatoria_status' => 0,
			'Candidato.fase_eliminatoria_status' => 1,
			'Candidato.matriculado' => 0,
			'RespostaQuestaoQuestionario.resposta REGEXP' => '.*<campo><nome>qid69_chk_2_[12]</nome><tipo>checkBox</tipo><valor>on</valor></campo>.*');
		$count = $this->RespostaQuestaoQuestionario->find('count', array('conditions' => $condicao, 
												'recursive' => '0', 'order' => 'Candidato.nota_prova DESC'));
		return $count;
	}
	function getIdade($numero_inscricao, $ano)
	{
		$condicao = array(
			'RespostaQuestaoQuestionario.questao_questionario_id' => '2',
			'Candidato.numero_inscricao' => $numero_inscricao,
			'Candidato.ano' => $ano);
		$candidato = $this->RespostaQuestaoQuestionario->find('first', array('conditions' => $condicao));
		if ($candidato)
		{
			$resposta = $candidato['RespostaQuestaoQuestionario']['resposta'];
			if ($resposta = simplexml_load_string($resposta))
			{				
				if ($resposta->campos->campo->valor != '')
					return $resposta->campos->campo->valor;
				else
					return null;
			}
			else
				return null;
		}
		else
			return null;
	}

    function getAnoConclusaoEM($numero_inscricao, $ano)
    {
        $condicao = array(
            'RespostaQuestaoQuestionario.questao_questionario_id' => '6',
            'Candidato.numero_inscricao' => $numero_inscricao,
            'Candidato.ano' => $ano);
        $candidato = $this->RespostaQuestaoQuestionario->find('first', array('conditions' => $condicao));
        if ($candidato)
        {
            $resposta = $candidato['RespostaQuestaoQuestionario']['resposta'];
            if ($resposta = simplexml_load_string($resposta))
            {
                if ($resposta->campos->campo->valor != '')
                    return $resposta->campos->campo->valor;
                else
                    return null;
            }
            else
                return null;
        }
        else
            return null;
    }

	function getCor($numero_inscricao, $ano)
	{
		$condicao = array(
		'RespostaQuestaoQuestionario.questao_questionario_id' => '28',
		'Candidato.numero_inscricao' => $numero_inscricao,
		'Candidato.ano' => $ano);
		$candidato = $this->RespostaQuestaoQuestionario->find('first', array('conditions' => $condicao));
		if ($candidato)
		{
			$resposta = $candidato['RespostaQuestaoQuestionario']['resposta'];
			if ($resposta = simplexml_load_string($resposta))
			{				
				if ($resposta->campos->campo->valor != '')
				{
					$cor = '';
					switch ($resposta->campos->campo->valor)
					{
						case '1' : $cor = 'preto';
							break;
						case '2' : $cor = 'pardo';
							break;
						case '3' : $cor = 'amarelo';
							break;
						case '4' : $cor = 'branco';
							break;
						case '5' : $cor = 'indígena';
							break;
						default : 
							return null;
					}
					return $cor;
				}
				else
					return null;
			}
			else
				return null;
		}
		else
			return null;
	}


    function getEscolaridadePai($numero_inscricao, $ano)
    {
        $condicao = array(
            'RespostaQuestaoQuestionario.questao_questionario_id' => '37',
            'Candidato.numero_inscricao' => $numero_inscricao,
            'Candidato.ano' => $ano);
        $candidato = $this->RespostaQuestaoQuestionario->find('first', array('conditions' => $condicao));
        if ($candidato)
        {
            $resposta = $candidato['RespostaQuestaoQuestionario']['resposta'];
            if ($resposta = simplexml_load_string($resposta))
            {
                if ($resposta->campos->campo->valor != '')
                {
                    $cor = '';
                    switch ($resposta->campos->campo->valor)
                    {
                        case '1' : $cor = 'Analfabeto / nao estudou';
                            break;
                        case '2' : $cor = 'Ensino fundamental incompleto';
                            break;
                        case '3' : $cor = 'Ensino fundamental completo';
                            break;
                        case '4' : $cor = 'Ensino medio incompleto';
                            break;
                        case '5' : $cor = 'Ensino medio completo';
                            break;
                        case '6' : $cor = 'Ensino superior (faculdade) incompleto';
                            break;
                        case '7' : $cor = 'Ensino superior (faculdade) completo';
                            break;
                        case '8' : $cor = 'Escolaridade desconhecida';
                            break;
                        default :
                            return null;
                    }
                    return $cor;
                }
                else
                    return null;
            }
            else
                return null;
        }
        else
            return null;
    }

    function getEscolaridadeMae($numero_inscricao, $ano)
    {
        $condicao = array(
            'RespostaQuestaoQuestionario.questao_questionario_id' => '38',
            'Candidato.numero_inscricao' => $numero_inscricao,
            'Candidato.ano' => $ano);
        $candidato = $this->RespostaQuestaoQuestionario->find('first', array('conditions' => $condicao));
        if ($candidato)
        {
            $resposta = $candidato['RespostaQuestaoQuestionario']['resposta'];
            if ($resposta = simplexml_load_string($resposta))
            {
                if ($resposta->campos->campo->valor != '')
                {
                    $cor = '';
                    switch ($resposta->campos->campo->valor)
                    {
                        case '1' : $cor = 'Analfabeto / nao estudou';
                            break;
                        case '2' : $cor = 'Ensino fundamental incompleto';
                            break;
                        case '3' : $cor = 'Ensino fundamental completo';
                            break;
                        case '4' : $cor = 'Ensino medio incompleto';
                            break;
                        case '5' : $cor = 'Ensino medio completo';
                            break;
                        case '6' : $cor = 'Ensino superior (faculdade) incompleto';
                            break;
                        case '7' : $cor = 'Ensino superior (faculdade) completo';
                            break;
                        case '8' : $cor = 'Escolaridade desconhecida';
                            break;
                        default :
                            return null;
                    }
                    return $cor;
                }
                else
                    return null;
            }
            else
                return null;
        }
        else
            return null;
    }


    function getTipoMoradia($numero_inscricao, $ano)
    {
        $condicao = array(
            'RespostaQuestaoQuestionario.questao_questionario_id' => '41',
            'Candidato.numero_inscricao' => $numero_inscricao,
            'Candidato.ano' => $ano);
        $candidato = $this->RespostaQuestaoQuestionario->find('first', array('conditions' => $condicao));
        if ($candidato)
        {
            $resposta = $candidato['RespostaQuestaoQuestionario']['resposta'];
            if ($resposta = simplexml_load_string($resposta))
            {
                if ($resposta->campos->campo->valor != '')
                {
                    $cor = '';
                    switch ($resposta->campos->campo->valor)
                    {
                        case '1' : $cor = 'Alugada';
                            break;
                        case '2' : $cor = 'Casa propria / pagando financiamento';
                            break;
                        case '3' : $cor = 'Casa propria / ja quitada';
                            break;
                        case '4' : $cor = 'Cedida';
                            break;
                        case '5' : $cor = 'Mora no local de trabalho';
                            break;
                        default :
                            return null;
                    }
                    return $cor;
                }
                else
                    return null;
            }
            else
                return null;
        }
        else
            return null;
    }

    function getNumComodos($numero_inscricao, $ano)
    {
        $condicao = array(
            'RespostaQuestaoQuestionario.questao_questionario_id' => '42',
            'Candidato.numero_inscricao' => $numero_inscricao,
            'Candidato.ano' => $ano);
        $candidato = $this->RespostaQuestaoQuestionario->find('first', array('conditions' => $condicao));
        if ($candidato)
        {
            $resposta = $candidato['RespostaQuestaoQuestionario']['resposta'];
            if ($resposta = simplexml_load_string($resposta))
            {
                if ($resposta->campos->campo->valor != '')
                {
                    $cor = '';
                    switch ($resposta->campos->campo->valor)
                    {
                        case '1' : $cor = '01 comodo';
                            break;
                        case '2' : $cor = '02 comodos';
                            break;
                        case '3' : $cor = '03 comodos';
                            break;
                        case '4' : $cor = '04 comodos';
                            break;
                        case '5' : $cor = '05 comodos';
                            break;
                        case '6' : $cor = '06 ou mais comodos';
                            break;
                        default :
                            return null;
                    }
                    return $cor;
                }
                else
                    return null;
            }
            else
                return null;
        }
        else
            return null;
    }

    function getNumBanheiros($numero_inscricao, $ano)
    {
        $condicao = array(
            'RespostaQuestaoQuestionario.questao_questionario_id' => '44',
            'Candidato.numero_inscricao' => $numero_inscricao,
            'Candidato.ano' => $ano);
        $candidato = $this->RespostaQuestaoQuestionario->find('first', array('conditions' => $condicao));
        if ($candidato)
        {
            $resposta = $candidato['RespostaQuestaoQuestionario']['resposta'];
            if ($resposta = simplexml_load_string($resposta))
            {
                if ($resposta->campos->campo->valor != '')
                {
                    $cor = '';
                    switch ($resposta->campos->campo->valor)
                    {
                        case '1' : $cor = '01 banheiro';
                            break;
                        case '2' : $cor = '02 banheiros';
                            break;
                        case '3' : $cor = '03 ou mais banheiros';
                            break;
                        default :
                            return null;
                    }
                    return $cor;
                }
                else
                    return null;
            }
            else
                return null;
        }
        else
            return null;
    }

    function getPortadorNecessidade($numero_inscricao, $ano)
    {
        $condicao = array(
            'RespostaQuestaoQuestionario.questao_questionario_id' => '34',
            'Candidato.numero_inscricao' => $numero_inscricao,
            'Candidato.ano' => $ano);
        $candidato = $this->RespostaQuestaoQuestionario->find('first', array('conditions' => $condicao));
        if ($candidato)
        {
            $resposta = $candidato['RespostaQuestaoQuestionario']['resposta'];
            if ($resposta = simplexml_load_string($resposta))
            {
                if ($resposta->campos->campo->valor != '')
                {
                    $cor = '';
                    switch ($resposta->campos->campo->valor)
                    {
                        case '1' : $cor = 'Nao';
                            break;
                        case '2' : $cor = 'Sim';
                            break;
                        default :
                            return null;
                    }
                    return $cor;
                }
                else
                    return null;
            }
            else
                return null;
        }
        else
            return null;
    }

    function getOrientacaoSexual($numero_inscricao, $ano)
    {
        $condicao = array(
            'RespostaQuestaoQuestionario.questao_questionario_id' => '30',
            'Candidato.numero_inscricao' => $numero_inscricao,
            'Candidato.ano' => $ano);
        $candidato = $this->RespostaQuestaoQuestionario->find('first', array('conditions' => $condicao));
        if ($candidato)
        {
            $resposta = $candidato['RespostaQuestaoQuestionario']['resposta'];
            if ($resposta = simplexml_load_string($resposta))
            {
                if ($resposta->campos->campo->valor != '')
                {
                    $cor = '';
                    switch ($resposta->campos->campo->valor)
                    {
                        case '1' : $cor = 'heterosexual';
                            break;
                        case '2' : $cor = 'homossexual (gay ou lesbica)';
                            break;
                        case '3' : $cor = 'bissexual';
                            break;
                        case '6' : $cor = 'nao quero responder';
                            break;
                        default :
                            return null;
                    }
                    return $cor;
                }
                else
                    return null;
            }
            else
                return null;
        }
        else
            return null;
    }

    function getInternet($numero_inscricao, $ano)
    {
        $condicao = array(
            'RespostaQuestaoQuestionario.questao_questionario_id' => '56',
            'Candidato.numero_inscricao' => $numero_inscricao,
            'Candidato.ano' => $ano);
        $candidato = $this->RespostaQuestaoQuestionario->find('first', array('conditions' => $condicao));
        if ($candidato)
        {
            $resposta = $candidato['RespostaQuestaoQuestionario']['resposta'];
            if ($resposta = simplexml_load_string($resposta))
            {
                if ($resposta->campos->campo->valor != '')
                {
                    $cor = '';
                    switch ($resposta->campos->campo->valor)
                    {
                        case '1' : $cor = 'Sim';
                            break;
                        case '2' : $cor = 'Nao';
                            break;
                        default :
                            return null;
                    }
                    return $cor;
                }
                else
                    return null;
            }
            else
                return null;
        }
        else
            return null;
    }

    function getTvCabo($numero_inscricao, $ano)
    {
        $condicao = array(
            'RespostaQuestaoQuestionario.questao_questionario_id' => '57',
            'Candidato.numero_inscricao' => $numero_inscricao,
            'Candidato.ano' => $ano);
        $candidato = $this->RespostaQuestaoQuestionario->find('first', array('conditions' => $condicao));
        if ($candidato)
        {
            $resposta = $candidato['RespostaQuestaoQuestionario']['resposta'];
            if ($resposta = simplexml_load_string($resposta))
            {
                if ($resposta->campos->campo->valor != '')
                {
                    $cor = '';
                    switch ($resposta->campos->campo->valor)
                    {
                        case '1' : $cor = 'Sim';
                            break;
                        case '2' : $cor = 'Nao';
                            break;
                        default :
                            return null;
                    }
                    return $cor;
                }
                else
                    return null;
            }
            else
                return null;
        }
        else
            return null;
    }

    function getTelefone($numero_inscricao, $ano)
    {
        $condicao = array(
            'RespostaQuestaoQuestionario.questao_questionario_id' => '58',
            'Candidato.numero_inscricao' => $numero_inscricao,
            'Candidato.ano' => $ano);
        $candidato = $this->RespostaQuestaoQuestionario->find('first', array('conditions' => $condicao));
        if ($candidato)
        {
            $resposta = $candidato['RespostaQuestaoQuestionario']['resposta'];
            if ($resposta = simplexml_load_string($resposta))
            {
                if ($resposta->campos->campo->valor != '')
                {
                    $cor = '';
                    switch ($resposta->campos->campo->valor)
                    {
                        case '1' : $cor = 'Sim';
                            break;
                        case '2' : $cor = 'Nao';
                            break;
                        default :
                            return null;
                    }
                    return $cor;
                }
                else
                    return null;
            }
            else
                return null;
        }
        else
            return null;
    }

    function getConheceuCursinho($numero_inscricao, $ano)
    {
        $condicao = array(
            'RespostaQuestaoQuestionario.questao_questionario_id' => '71',
            'Candidato.numero_inscricao' => $numero_inscricao,
            'Candidato.ano' => $ano);
        $candidato = $this->RespostaQuestaoQuestionario->find('first', array('conditions' => $condicao));
        if ($candidato)
        {
            $resposta = $candidato['RespostaQuestaoQuestionario']['resposta'];
            if ($resposta = simplexml_load_string($resposta))
            {

                foreach ($resposta->campos->children() as $campo){

                    if ($campo->valor == 1)
                    {
                        switch ($campo->nome)
                        {
                            case 'qid71_chkOpcao_1' : $valor_resposta .= 'Televisao; ';
                                break;
                            case 'qid71_chkOpcao_2' : $valor_resposta .= 'Radio; ';
                                break;
                            case 'qid71_chkOpcao_3' : $valor_resposta .= 'Cartazes; ';
                                break;
                            case 'qid71_chkOpcao_4' : $valor_resposta .= 'Amigos; ';
                                break;
                            case 'qid71_chkOpcao_5' : $valor_resposta .= 'Na escola; ';
                                break;
                            case 'qid71_chkOpcao_6' : $valor_resposta .= 'Outros; ';
                                break;
                            default :
                                break;
                        }
                    }
                }
                return $valor_resposta;
            }
            else
                return null;
        }
        else
            return null;
    }

	function getSexo($numero_inscricao, $ano)
	{
		$condicao = array(
		'RespostaQuestaoQuestionario.questao_questionario_id' => '27',
		'Candidato.numero_inscricao' => $numero_inscricao,
		'Candidato.ano' => $ano);
		$candidato = $this->RespostaQuestaoQuestionario->find('first', array('conditions' => $condicao));
		if ($candidato)
		{
			$resposta = $candidato['RespostaQuestaoQuestionario']['resposta'];
			if ($resposta = simplexml_load_string($resposta))
			{				
				if ($resposta->campos->campo->valor != '')
				{
					$sexo = '';
					switch ($resposta->campos->campo->valor)
					{
						case '1' : $sexo = 'feminino';
							break;
						case '2' : $sexo = 'masculino';
							break;
						default : 
							return null;
					}
					return $sexo;
				}
				else
					return null;
			}
			else
				return null;
		}
		else
			return null;
	}
	function getEtnia($numero_inscricao, $ano)
	{
		$condicao = array(
		'RespostaQuestaoQuestionario.questao_questionario_id' => '29',
		'Candidato.numero_inscricao' => $numero_inscricao,
		'Candidato.ano' => $ano);
		$candidato = $this->RespostaQuestaoQuestionario->find('first', array('conditions' => $condicao));
		if ($candidato)
		{
			$resposta = $candidato['RespostaQuestaoQuestionario']['resposta'];
			if ($resposta = simplexml_load_string($resposta))
			{
				$etnia = '';
				for ($i = 0; $i < count($resposta->campos->campo); $i++)
				{
					if ($i > 0)
						$etnia .= ' e ';
					switch ($resposta->campos->campo[$i]->nome)
					{
						case 'qid29_chkOpcao_1' : 
							$etnia .= 'afrodescendente';
							break;
						case 'qid29_chkOpcao_2' : 
							$etnia .= 'branco';
							break;
						case 'qid29_chkOpcao_3' : 
							$etnia .= 'indigena';
							break;
						case 'qid29_chkOpcao_4' : 
							$etnia .= 'oriental';
							break;
						case 'qid29_chkOpcao_5' : 
							$etnia = 'nao sabe';
							break;
					}
				}
				return $etnia;
			}
			else
				return null;
		}
		else
			return null;
	}
	function getTrabalho($numero_inscricao, $ano)
	{
		$condicao = array(
		'RespostaQuestaoQuestionario.questao_questionario_id' => '24',
		'Candidato.numero_inscricao' => $numero_inscricao,
		'Candidato.ano' => $ano);
		$candidato = $this->RespostaQuestaoQuestionario->find('first', array('conditions' => $condicao));
		if ($candidato)
		{
			$resposta = $candidato['RespostaQuestaoQuestionario']['resposta'];
			if ($resposta = simplexml_load_string($resposta))
			{								
				//foreach ($resposta->campos->campo as $campo)
				{
					//if ($campo->nome == 'qid24_rbtOpcao' && $campo->valor != '')
					if ($resposta->campos->campo[1]->valor != '')
					{						
						$trabalho = '';
						switch($resposta->campos->campo[1]->valor)
						{
							case '1' : $trabalho = 'nunca trabalhou';
								break;
							case '2' : $trabalho = 'desempregado';
								break;
							case '3' : $trabalho = 'empregado';
								break;
							default :
								return null;
						}
						return $trabalho;
					}
					else
						return null;
				}
			}
			else
				return null;
		}
		else
			return null;
	}
	function getEstadoCivil($numero_inscricao, $ano)
	{
		$condicao = array(
		'RespostaQuestaoQuestionario.questao_questionario_id' => '31',
		'Candidato.numero_inscricao' => $numero_inscricao,
		'Candidato.ano' => $ano);
		$candidato = $this->RespostaQuestaoQuestionario->find('first', array('conditions' => $condicao));
		if ($candidato)
		{
			$resposta = $candidato['RespostaQuestaoQuestionario']['resposta'];
			if ($resposta = simplexml_load_string($resposta))
			{				
				if ($resposta->campos->campo->valor != '')
				{
					$estado_civil = '';
					switch ($resposta->campos->campo->valor)
					{
						case '1' : $estado_civil = 'solteiro';
							break;
						case '2' : $estado_civil = 'viuvo';
							break;
						case '3' :
						case '4' : $estado_civil = 'separado';
							break;
						case '5' : $estado_civil = 'casado';
							break;
						default : 
							return null;
					}
					return $estado_civil;
				}
				else
					return null;
			}
			else
				return null;
		}
		else
			return null;
	}
	function getNumeroFilhos($numero_inscricao, $ano)
	{
		$condicao = array(
		'RespostaQuestaoQuestionario.questao_questionario_id' => '32',
		'Candidato.numero_inscricao' => $numero_inscricao,
		'Candidato.ano' => $ano);
		$candidato = $this->RespostaQuestaoQuestionario->find('first', array('conditions' => $condicao));
		if ($candidato)
		{
			$resposta = $candidato['RespostaQuestaoQuestionario']['resposta'];
			if ($resposta = simplexml_load_string($resposta))
			{
				if ($resposta->campos->campo[0]->nome == "qid32_txtCampo2")
				{
					if ($resposta->campos->campo[0]->valor != '')
						return $resposta->campos->campo[0]->valor;
					else
						return 'nao tem';
				}
				else if ($resposta->campos->campo[1]->nome == "qid32_txtCampo2")
				{
					if ($resposta->campos->campo[1]->valor != '')
						return $resposta->campos->campo[1]->valor;
					else
						return 'nao tem';
				}
				/* Essa forma de fazer só funciona a partir do PHP 5.3.0, nas versões anteriores não há a função SimpleXml::Count */
				/*if ($resposta->campos->campo->count() > 1)
				{
					if ($resposta->campos->campo[1]->valor != '')
						return $resposta->campos->campo[1]->valor;
					else
						return 'nao tem';
				}
				// Se essa situação ocorrer, significa que o campo para escrever o número de filhos foi preenchido
				//	mas o checkbox correspondente não foi marcado
				else if ($resposta->campos->campo[0]->nome == "qid32_txtCampo2")
				{
					if ($resposta->campos->campo[0]->valor != '')
						return $resposta->campos->campo[0]->valor;
					else
						return 'nao tem';
				}*/
			}
			else
				return null;
		}
		else
			return null;
	}
	function getRendaBruta($numero_inscricao, $ano)
	{
		$condicao = array(
		'RespostaQuestaoQuestionario.questao_questionario_id' => '67',
		'Candidato.numero_inscricao' => $numero_inscricao,
		'Candidato.ano' => $ano);
		$candidato = $this->RespostaQuestaoQuestionario->find('first', array('conditions' => $condicao));
		if ($candidato)
		{
			$pontuacao_economica = $candidato['RespostaQuestaoQuestionario']['pontuacao_economica'];
			if ($pontuacao_economica = simplexml_load_string($pontuacao_economica))
			{
				$renda_bruta = $pontuacao_economica->valor;
				$numero_pessoas = $pontuacao_economica->campos_pontuados + 1;
				if ($renda_bruta <= 0)
					return 'nao definido';
				if ($numero_pessoas > 1)
					return ($renda_bruta / $numero_pessoas) . ' / ' . $numero_pessoas . ' pessoas';
				else if ($numero_pessoas == 1)
					return ($renda_bruta / $numero_pessoas) . ' / 1 pessoa';
				else
					return '';
			}
			else
				return 'falha xml';
		}
		else
			return 'falha candidato';
	}
	function situacaoQuestionario($numero_inscricao, $ano)
	{
		$condicao = array(
		'RespostaQuestaoQuestionario.candidato_id' => obterId($numero_inscricao, $ano));
		$numero_respostas = $this->RespostaQuestaoQuestionario->find('count', array('conditions' => $condicao));
		if ($numero_respostas > 0)
			return true;
		else
			return false;
	}
	function getCandidatosPorNotaProva($ano, $turma)
	{
		// retorna a lista de candidatos por ordem do nota de prova
		//$condicao = array('Candidato.ano' => $ano_turma, 'Candidato.nota_prova >=' => 0, 'Candidato.matriculado' => 0);
		$condicao = array('Candidato.ano' => $ano, 'Candidato.turma' => $turma, 'Candidato.matriculado' => 0, 'Candidato.fase_classificatoria_status' => 0, 'Candidato.fase_eliminatoria_status' => 1);
		$candidatos = $this->find('all', array('conditions' => $condicao,
												'order' => 'Candidato.nota_prova DESC', 
												'recursive' => '0', 'order' => 'Candidato.nota_prova DESC'));
		return $candidatos;
	}
	function getCandidatosPorNotaProvaCount($ano, $turma)
	{
		// retorna a lista de candidatos por ordem do nota de prova
		//$condicao = array('Candidato.ano' => $ano_turma, 'Candidato.nota_prova >=' => 0, 'Candidato.matriculado' => 0);
		$condicao = array('Candidato.ano' => $ano, 'Candidato.turma' => $turma, 'Candidato.matriculado' => 0, 'Candidato.fase_classificatoria_status' => 0, 'Candidato.fase_eliminatoria_status' => 1);
		$count = $this->find('count', array('conditions' => $condicao,
												'order' => 'Candidato.nota_prova DESC', 
												'recursive' => '0', 'order' => 'Candidato.nota_prova DESC'));
		return $count;
	}
	function getCandidatoPorNotaNaoAprovado($ano, $turma)
	{
		// retorna a lista de candidatos por ordem do nota de prova
		$condicao = array('Candidato.ano' => $ano, 'Candidato.turma' => $turma, 'Candidato.nota_prova >=' => 0, 'Candidato.matriculado' => 0, 'Candidato.fase_classificatoria_status' => 0);
		$candidatos = $this->find('all', array('conditions' => $condicao,
												'order' => 'Candidato.nota_prova DESC', 
												'recursive' => '0'));
		return $candidatos;
	}
	function getCandidatoPorNotaNaoAprovadoCount($ano, $turma)
	{
		// retorna a lista de candidatos por ordem do nota de prova
		$condicao = array('Candidato.ano' => $ano, 'Candidato.turma' => $turma, 'Candidato.nota_prova >=' => 0, 'Candidato.matriculado' => 0, 'Candidato.fase_classificatoria_status' => 0);
		$count = $this->find('count', array('conditions' => $condicao,
												'order' => 'Candidato.nota_prova DESC', 
												'recursive' => '0'));
		return $count;
	}
	function fezProva($candidato_id)
	{
		$condicao = array('Candidato.candidato_id' => $candidato_id);
		$candidato = $this->find('first', array('conditions' => $condicao, 
												'recursive' => '0'));
		if ($candidato['Candidato']['nota_prova'] == null | $candidato['Candidato']['nota_prova'] == '')
			return false;
		else
			return true;
	}
	function fezProvaEspecial($candidato_id)
	{
		$condicao = array('Candidato.candidato_id' => $candidato_id);
		$candidato = $this->find('first', array('conditions' => $condicao, 
												'recursive' => '0'));
		if ($candidato['Candidato']['prova_especial'] == 1)
			return true;
		else
			return false;
	}
	function getCountCandidatosMatriculados($ano, $turma)
	{
		$condicao = array('Candidato.ano' => $ano, 'Candidato.matriculado' => 1, 'Candidato.turma' => $turma);
		$candidatos = $this->find('count', array('conditions' => $condicao, 
												'recursive' => '0'));
		return $candidatos;
	}
	/*
	 * Conta quantos candidatos estão são da ultima chamada e não são
	 * da primeira chamada. 
	 */
	function fezSomentePrmeiraChamada($ano)
	{
		$condicao = array('Candidato.ano' => $ano, 'Candidato.ultima_chamada' => 1, 'Candidato.primeira_chamada' => 1);
		$candidatos = $this->find('count', array('conditions' => $condicao, 
												'recursive' => '0'));
                return true;
		if($candidatos>0)
			return true;
		else
			return false;
	}
	/*
	 * $ano 	= ano do processo seletivo (string)
	 * $turma 	= turma de 1 ou 2 anos (string)
	 */
	function getCandidatoAprovadoNaoMatriculado($ano, $turma)
	{
		$condicao = array('Candidato.ano' => $ano, 'Candidato.fase_classificatoria_status' => 1, 'Candidato.matriculado' => 0, 'Candidato.turma' => $turma);
		$candidatos = $this->find('all', array('conditions' => $condicao, 
												'recursive' => '0'));
		return $candidatos;
	}
	/*
	 * $ano 	= ano do processo seletivo (string)
	 * $turma 	= turma de 1 ou 2 anos (string)
	 */
	function getCandidatoAprovadoNaoMatriculadoCount($ano, $turma)
	{
		$condicao = array('Candidato.ano' => $ano, 'Candidato.fase_classificatoria_status' => 1, 'Candidato.matriculado' => 0, 'Candidato.turma' => $turma);
		$num_candidatos = $this->find('count', array('conditions' => $condicao, 
												'recursive' => '0'));
		return $num_candidatos;
	}
	/*
	 * $ano 	= ano do processo seletivo (string)
	 * $turma 	= turma de 1 ou 2 anos (string)
	 */
	function getCandidatoAprovadoMatriculadoCount($ano, $turma)
	{
		$condicao = array('Candidato.ano' => $ano, 'Candidato.fase_classificatoria_status' => 1, 'Candidato.matriculado' => 1, 'Candidato.turma' => $turma);
		$num_candidatos = $this->find('count', array('conditions' => $condicao, 
												'recursive' => '0'));
		return $num_candidatos;
	}
	function getCandidatosUltimaChamada($ano)
	{
		$condicao = array('Candidato.ano' => $ano, 'Candidato.ultima_chamada' => 1);
		$candidatos = $this->find('all', array('conditions' => $condicao, 
												'recursive' => '0'));
		return $candidatos;
	}
	function setCandidatosUltimaChamada($numero_inscricao, $ano, $valor)
	{
		$this->Candidato = new Candidato();
		$condicao = array('numero_inscricao' => $numero_inscricao, 'ano' => $ano);
		$candidato = $this->Candidato->find('first', array('conditions' => $condicao, 'recursive' => '0', 'fields' => 'ultima_chamada'));
		$candidato['Candidato']['ultima_chamada'] = $valor;
		$this->Candidato->id = $candidato['Candidato']['candidato_id'];
		if($this->Candidato->save($candidato))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function getFaseEliminatoriaStatus($numero_inscricao, $ano)
	{
		$condicao = array('numero_inscricao' => $numero_inscricao, 'ano' => $ano);
		$candidato = $this->find('first', array('conditions' => $condicao, 
												'recursive' => '0',
												'fields' => array('fase_eliminatoria_status')));
		return $candidato['Candidato']['fase_eliminatoria_status'];
	}
	function setCandidatoEliminatoria($numero_inscricao, $ano, $valor)
	{
		$this->Candidato = new Candidato();
		$condicao = array('numero_inscricao' => $numero_inscricao, 'ano' => $ano);
		$candidato = $this->Candidato->find('first', array('conditions' => $condicao, 'recursive' => '0', 'fields' => 'fase_eliminatoria_status'));
		$candidato['Candidato']['fase_eliminatoria_status'] = $valor;
		$this->Candidato->id = $candidato['Candidato']['candidato_id'];
		if($this->Candidato->save($candidato))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function getFaseClassificatoriaStatus($numero_inscricao, $ano)
	{
		$condicao = array('numero_inscricao' => $numero_inscricao, 'ano' => $ano);
		$candidato = $this->find('first', array('conditions' => $condicao, 
												'recursive' => '0',
												'fields' => array('fase_classificatoria_status')));
		return $candidato['Candidato']['fase_classificatoria_status'];
	}
	function setCandidatoClassificatoria($numero_inscricao, $ano, $valor)
	{
		$this->Candidato = new Candidato();
		$condicao = array('numero_inscricao' => $numero_inscricao, 'ano' => $ano);
		$candidato = $this->Candidato->find('first', array('conditions' => $condicao, 'recursive' => '0', 'fields' => 'fase_classificatoria_status'));
		$candidato['Candidato']['fase_classificatoria_status'] = $valor;
		$this->Candidato->id = $candidato['Candidato']['candidato_id'];
		if($this->Candidato->save($candidato))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function getCandidatosPrimeraChamada($ano_fase)
	{
		$condicao = array('Candidato.primeira_chamada' => 1, 'Candidato.ano' => $ano_fase);
		$candidato = $this->find('all', array('conditions' => $condicao, 
												'recursive' => '0'));
		return $candidato;
	}
	function getPrimeiraChamadaStatus($numero_inscricao, $ano)
	{
		$condicao = array('numero_inscricao' => $numero_inscricao, 'ano' => $ano);
		$candidato = $this->find('first', array('conditions' => $condicao, 
												'recursive' => '0',
												'fields' => array('primeira_chamada')));
		return $candidato['Candidato']['primeira_chamada'];
	}
	function setPrimeiraChamada($numero_inscricao, $ano, $valor)
	{
		$this->Candidato = new Candidato();
		$condicao = array('numero_inscricao' => $numero_inscricao, 'ano' => $ano);
		$candidato = $this->Candidato->find('first', array('conditions' => $condicao, 'recursive' => '0', 'fields' => 'primeira_chamada'));
		$candidato['Candidato']['primeira_chamada'] = $valor;
		$this->Candidato->id = $candidato['Candidato']['candidato_id'];
		if($this->Candidato->save($candidato))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function setDataPreenchimentoProva($numero_inscricao, $ano, $data)
	{
		$this->id = $this->obterId($numero_inscricao, $ano);
		$dados = $this->read();
		$dados_update = array();
		$dados_update['Candidato']['candidato_id'] = $dados['Candidato']['candidato_id'];
		$dados_update['Candidato']['data_preenchimento_prova'] = $data;
		$this->set($dados_update);
		if($this->save())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function setNotaProva($numero_inscricao, $ano, $nota)
	{
		$this->Candidato = new Candidato();
		$this->Candidato->id = $this->Candidato->obterId($numero_inscricao, $ano);
		$dados_total = $this->Candidato->read();
		$dados = array();
		$dados['Candidato']['candidato_id'] = $dados_total['Candidato']['candidato_id'];
		$dados['Candidato']['nota_prova'] = $nota;
		$this->Candidato = new Candidato();
		if($this->Candidato->save($dados))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function valida_cpf($check)
	{	
		if($_SESSION['valida_cpf'] == false){
			$_SESSION['valida_cpf'] = true;
			return true;
		}
		$cpf = $check['cpf'];
		if ($cpf == '')
			return true;
		$simbolos = array('.', '-');
		// pontos e traços são removidos restando apenas os números
		$cpf = str_replace($simbolos, '', $cpf);
		if (!is_numeric($cpf))
		{
		  $status = false;		
		}
		else
		{
                        if($cpf == '39974185865') 
                            return true;
                        if($cpf == '11575118807') 
                            return true;
                        if($cpf == '36619845850') 
                            return true;
                        if($cpf == '38847025827') 
                            return true;
                        if($cpf == '36529233830') 
                            return true;
                        if($cpf == '39874441858') 
                            return true;
                        if($cpf == '42050417895') 
                            return true;
                        if($cpf == '31705871822') 
                            return true;
                        if($cpf == '45567062304') 
                            return true;
			if( ($cpf == '11111111111') || ($cpf == '22222222222') ||
		   		($cpf == '33333333333') || ($cpf == '44444444444') ||
		   		($cpf == '55555555555') || ($cpf == '66666666666') ||
		   		($cpf == '77777777777') || ($cpf == '88888888888') ||
		   		($cpf == '99999999999') || ($cpf == '00000000000') )
		   	{
		   		$status = false;
			}
			else
			{
			   	$dv_informado = substr($cpf, 9,2);
			   	for($i=0; $i<=8; $i++)
			   	{
			    	$digito[$i] = substr($cpf, $i,1);
			   	}
			   	$posicao = 10;
			   	$soma = 0;
			   	for($i=0; $i<=8; $i++)
			   	{
			    	$soma = $soma + $digito[$i] * $posicao;
			    	$posicao = $posicao - 1;
			   	}
			   	$digito[9] = $soma % 11;
			   	if($digito[9] < 2)
			   	{
			    	$digito[9] = 0;
			   	}
			   	else
			   	{
			    	$digito[9] = 11 - $digito[9];
			   	}
			   	$posicao = 11;
			   	$soma = 0;
			   	for ($i=0; $i<=9; $i++)
			  	{
			   		$soma = $soma + $digito[$i] * $posicao;
			    	$posicao = $posicao - 1;
			   	}
			   	$digito[10] = $soma % 11;
			   	if ($digito[10] < 2)
			   	{
			   		$digito[10] = 0; 
			   	}
			   	else
			   	{
			    	$digito[10] = 11 - $digito[10];
			   	}
			  	$dv = $digito[9] * 10 + $digito[10];
			  	if ($dv != $dv_informado)
			  	{
			   		$status = false;
			  	}
			  	else
			   		$status = true;
		  	}
		}
		return $status;
	}
    function geraNumeroInscricao($ano_letivo)
    {
            $ult_numero = $this->find('first', array('conditions' => array('Candidato.ano' => $ano_letivo), 'fields' => 'MAX(Candidato.numero_inscricao) AS numero_inscricao', 'recursive' => '0'));
            if(!empty($ult_numero[0]['numero_inscricao']))
                return intval($ult_numero[0]['numero_inscricao'])+1;
            else 
                return 1;
    }
    function countCandidatos($ano_letivo, $turma)
    {
        $request = $this->find('count', array('conditions' => array('Candidato.ano' => $ano_letivo, 'Candidato.turma' => $turma), 'recursive' => '-1'));
        return $request;
    }
    function countCandidatosProva($ano_letivo, $turma)
    {
        $request = $this->find('count', array('conditions' => array('Candidato.ano' => $ano_letivo, 'Candidato.turma' => $turma, 'NOT' => array('Candidato.nota_prova' => null)), 'recursive' => '0'));
        return $request;
    }
    function countCandidatosQuestionario($ano_letivo, $turma)
    {
        $request = $this->find('count', array('conditions' => array('Candidato.ano' => $ano_letivo, 'Candidato.turma' => $turma, 'Candidato.pontuacao_social >' => 0), 'recursive' => '-1'));
        return $request;
    }
    function countCandidatosFaseEliminatoria($ano_letivo, $turma)
    {
        $request = $this->find('count', array('conditions' => array('Candidato.ano' => $ano_letivo, 'Candidato.turma' => $turma, 'Candidato.fase_eliminatoria_status' => 1), 'recursive' => '-1'));
        return $request;
    }
    function countCandidatosAprovados($ano_letivo, $turma)
    {
        $request = $this->find('count', array('conditions' => array('Candidato.ano' => $ano_letivo, 'Candidato.turma' => $turma, 'Candidato.fase_classificatoria_status' => 1), 'recursive' => '-1'));
        return $request;
    }
    function countCandidatosMatriculados($ano_letivo, $turma)
    {
        $request = $this->Estudante->find('count', array('conditions' => array('Candidato.ano' => $ano_letivo, 'Candidato.turma' => $turma), 'recursive' => 1));
        return $request;
    }

    function salvar_email($cpf, $email){
        //para cada cpf, salvar o email na fica do candidato.
        $candidatos = $this->find('all', array('conditions' => array('Candidato.cpf' => $cpf), 'recursive' => '0'));
        foreach($candidatos as $c ){
            $this->create();
            $c['Candidato']['email'] = $email;
            if(!$this->save($c)){
                return false;
            }
        }
        return true;
    }

    function baixa_taxa_inscricao ($candidato_id){
        $candidatos = $this->find('all', array('conditions' => array('Candidato.candidato_id' => $candidato_id), 'recursive' => '-1'));
        foreach($candidatos as $c ){
            $this->create();
            $c['Candidato']['taxa_inscricao'] = 1;
            if(!$this->save($c)){
                return false;
            }
        }
        return true;
    }

    function liberar_candidato ($candidato_id){
        $candidatos = $this->find('all', array('conditions' => array('Candidato.candidato_id' => $candidato_id), 'recursive' => '-1'));
        foreach($candidatos as $c ){
            $this->create();
            $c['Candidato']['lista_espera'] = 0;
            if(!$this->save($c)){
                return false;
            }
        }
        return true;
    }

    function ativar_inscricao($candidato_id){
        $candidatos = $this->find('all', array('conditions' => array('Candidato.candidato_id' => $candidato_id), 'recursive' => '-1'));
        foreach($candidatos as $c ){
            $this->create();
            $c['Candidato']['lista_espera'] = 1;
            $c['Candidato']['cancelado'] = 0;
            $c['Candidato']['reativado'] = 1;
            if(!$this->save($c)){
                return false;
            }
        }
        return true;
    }

    function cancelar_inscricao($candidato_id){
        $candidatos = $this->find('all', array('conditions' => array('Candidato.candidato_id' => $candidato_id), 'recursive' => '-1'));
        foreach($candidatos as $c ){
            $this->create();
            if($c['Candidato']['cancelado'] == 0)
                $c['Candidato']['cancelado'] = 1;
            else{
                $c['Candidato']['cancelado'] = 0;
                $c['Candidato']['reativado'] = 1;
                $c['Candidato']['lista_espera'] = 1;
            }
            if(!$this->save($c)){
                return false;
            }
        }
        return true;
    }


}
?>
