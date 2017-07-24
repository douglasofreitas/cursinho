<?php 
class RespostaQuestaoQuestionario extends AppModel {
	var $name = 'RespostaQuestaoQuestionario';
	var $useTable = 'resposta_questao_questionario';
	var $primaryKey = 'resposta_questao_questionario_id';
	var $belongsTo = array('Candidato' => array('className' => 'Candidato',
												'foreignKey' => 'candidato_id'),
						   'QuestaoQuestionario' => array('className' => 'QuestaoQuestionario',
												  		  'foreignKey' => 'questao_questionario_id'));
	function obterId($questao_id, $candidato_id)
	{
		$condicao = array('RespostaQuestaoQuestionario.candidato_id' => $candidato_id,
			'RespostaQuestaoQuestionario.questao_questionario_id' => $questao_id);
		$campos = array('resposta_questao_questionario_id');
		if ($this->find('count', array('conditions' => $condicao, 'recursive' => '0')) > 0)
		{
			$id = $this->find('first', array('conditions' => $condicao, 'fields' => $campos, 'recursive' => '0'));
			return $id['RespostaQuestaoQuestionario']['resposta_questao_questionario_id'];
		}
		else
		{
			return false;
		}
	}
	function obterResposta($questao_id, $candidato_id)
	{
		$condicao = array('RespostaQuestaoQuestionario.candidato_id' => $candidato_id, 
			'RespostaQuestaoQuestionario.questao_questionario_id' => $questao_id);
		$campos = array('resposta_questao_questionario_id', 'resposta');
		if ($this->find('count', array('conditions' => $condicao, 'recursive' => '0')) > 0)
		{
			$resposta = $this->find('first', array('conditions' => $condicao, 'fields' => $campos, 'recursive' => '0'));
			//return $resposta;
			return $resposta['RespostaQuestaoQuestionario']['resposta'];
		}
		else
		{
			return false;
		}
	}
	// Essa função retorno os candidatos que possuem a resposta para a questão $questao_id igual
	//   ao valor em $resposta, restringindo o espaço de busca apenas aos candidatos ids que estão
	//   no array $ids_restricao
	function obterCandidatoPelaResposta($questao_id, $resposta, $ids_restricao = null)
	{	
		if (!empty($ids_restricao) && $ids_restricao != '')
		{
			// O trecho de código abaixo cria string no formato usado pela clásula IN com os ids
			//   contidos no array $ids_restricao. Ex: (1,2,5,7)
			$conjunto = '(' . $ids_restricao[0];
			for ($i = 1; $i < count($ids_restricao); $i++)
			{
				$conjunto .= ',' . $ids_restricao[$i];
			}
			$conjunto .= ')';
		}
		else
		{
			$conjunto = '';
		}
		// Gera uma string com formato xml que corresponde com os valores contidos em $resposta
		//print_r($questao_id);
		$respostaXml = $this->compor_resposta_xml_filtro($resposta);
		// Verifica se a string gerada não está vazia
		if ($respostaXml != '')
		{
			// Monta uma query SQL para gerar a consulta utilizando a string gerada
			$query = 'SELECT candidato_id FROM resposta_questao_questionario AS RespostaQuestaoQuestionario WHERE 
				questao_questionario_id = ' . $questao_id . ' AND resposta LIKE \'' . $respostaXml . '\'';
			// Se a lista que contém os ids dos candidatos não está vazia, adiciona um AND na query
			// Essa lista está no formato utilizado pela cláusula IN. Ex: ... IN (1, 2, 3)
			if ($conjunto != '')
				$query .= ' AND candidato_id IN ' . $conjunto;
			// Realiza a consulta e guarda o resultado
			$resultado = $this->query($query);
			// Verifica se algum resultado foi retornado
			if ($resultado)
			{
				$conjuntoArray = array();
				// Para cada linha do resultado obtido, adiciona o id do candidato no array $conjuntoArray
				foreach ($resultado as $r)
				{
					array_push($conjuntoArray, $r['RespostaQuestaoQuestionario']['candidato_id']);
				}
			}
			else
			{
				$conjuntoArray = '-99';
			}
			return $conjuntoArray;
		}
		else
		{
			return null;
		}
	}
    function compor_resposta_xml($dados)
    {
    	$xmlResposta = '<?xml version="1.0" encoding="utf-8"?>';
    	$xmlResposta .= '<resposta><campos>';
		foreach ($dados as $key => $valor)
		{
			$xmlResposta .= '<campo><nome>' . $key . '</nome>';
			$tipoCampo = substr($key, strpos($key, "_") + 1, 3);
			switch ($tipoCampo)
			{
				case 'txt' :
					$xmlResposta .= '<tipo>textBox</tipo>';
					break;
				case 'rbt' :
					$xmlResposta .= '<tipo>radioButton</tipo>';
					break;
				case 'chk' :
					$xmlResposta .= '<tipo>checkBox</tipo>';
					break;
				case 'hdn' :
					$xmlResposta .= '<tipo>hidden</tipo>';
					break;
				default :
					$xmlResposta .= '<tipo>tipoDesconhecido</tipo>';
					break;
			}
			$xmlResposta .= '<valor>' . $valor . '</valor></campo>';
		}
		$xmlResposta .= '</campos></resposta>';
		return $xmlResposta;
    }
    function compor_resposta_xml_filtro($dados)
    {
    	$xmlResposta = '';
    	if (!empty($dados))
    	{
			foreach ($dados as $key => $valor)
			{
				if ($valor != '')
				{
					$xmlResposta .= '%<campo><nome>' . $key . '</nome>';
					$tipoCampo = substr($key, strpos($key, "_") + 1, 3);
					switch ($tipoCampo)
					{
						case 'txt' :
							$xmlResposta .= '<tipo>textBox</tipo>';
							break;
						case 'rbt' :
							$xmlResposta .= '<tipo>radioButton</tipo>';
							break;
						case 'chk' :
							$xmlResposta .= '<tipo>checkBox</tipo>';
							break;
						case 'hdn' :
							$xmlResposta .= '<tipo>hidden</tipo>';
							break;
						default :
							$xmlResposta .= '<tipo>tipoDesconhecido</tipo>';
							break;
					}
					$xmlResposta .= '<valor>' . $valor . '</valor></campo>%';
				}
				else
					$xmlResposta = '';
			}
    	}
		return $xmlResposta;
    }
	function calcular_pontuacao_questao($dados, $xmlPontuacao)
    {
    	$pontuacao_social = 0;
	    $pontuacao_economica = 0;
	    $contador_pontuacao_social = 0;
	    $contador_pontuacao_economica = 0;
		if ($xmlPontuacao)
		{

			$xmlPontuacao = simplexml_load_string($xmlPontuacao);
			foreach ($dados as $key => $valor)
			{
				if ($xmlPontuacao->campo)
				{
					foreach ($xmlPontuacao->campo as $c)
					{
						if ($c->pontuacao_social && $c->pontuacao_social->valores && $c->pontuacao_social->valores->valor)
						{							
							//if ($c['nome'] == $key)
                                                        $questao_45 = strpos($c['nome'], 'd45');
							if (preg_match("/^" . $c['nome'] . "/", $key, $matches))
							{								
								switch ($c['tipo'])
								{
									case 'radioButton' :
										foreach ($c->pontuacao_social->valores->valor as $v)
										{
											if ($v['alternativa'] == $valor)
											{
												$pontuacao_social += (float)$v;
												$contador_pontuacao_social++;
											}
										}
										break;
									case 'checkBox' :
										foreach ($c->pontuacao_social->valores->valor as $v)
										{											
											if ($valor == 'on' || $valor == '1')
											{												
												$pontuacao_social += (float)$v;
												$contador_pontuacao_social++;
											}
										}
										break;
									case 'textBox' :
										if ($c->pontuacao_social->valores->valor == 'campo')
										{
											if ($valor != '')
											{
												$pontuacao_social += (float)str_replace(',', '.', $valor);
												$contador_pontuacao_social++;
											}
										}
										else
										{   
											if ($valor != '')
											{
                                                                                                if($questao_45){
                                                                                                    $pontuacao_social += (float)$c->pontuacao_social->valores->valor * (float)str_replace(',', '.', $valor);
                                                                                                    $contador_pontuacao_social++;
                                                                                                }else{
                                                                                                    $pontuacao_social += (float)$c->pontuacao_social->valores->valor;
                                                                                                    $contador_pontuacao_social++;
                                                                                                }
											}
										}
										break;
								}
							}
						}
						if ($c->pontuacao_economica && $c->pontuacao_economica->valores && $c->pontuacao_economica->valores->valor)
						{
							//if ($c['nome'] == $key)
							if (preg_match("/^" . $c['nome'] . "/", $key, $matches))
							{
								switch ($c['tipo'])
								{
									case 'radioButton' :
										foreach ($c->pontuacao_economica->valores->valor as $v)
										{
											if ($v['alternativa'] == $valor)
											{
												$pontuacao_economica += (float)$v;
												$contador_pontuacao_economica++;
											}
										}
										break;
									case 'checkBox' :
										foreach ($c->pontuacao_economica->valores->valor as $v)
										{
											if ($valor == 'on' || $valor == '1')
											{
												$pontuacao_economica += (float)$v;
												$contador_pontuacao_economica++;
											}
										}
										break;
									case 'textBox' :
										if ($c->pontuacao_economica->valores->valor == 'campo')
										{
											if ($valor != '')
											{
												// Retira o separador de milhar do valor
												if (substr($valor, 1, 1) == '.')
													$valor = substr_replace($valor, '', 1, 1);
												$pontuacao_economica += (float)str_replace(',', '.', $valor);
												if ($c['contador'] == 'false')
												{
													;
												}
												else
												{
													$contador_pontuacao_economica++;
												}
											}
										}
										else
										{
											if ($valor != '')
											{
												$pontuacao_economica += (float)$c->pontuacao_economica->valores->valor;
												if ($c['contador'] == 'false')
												{
													;
												}
												else
												{
													$contador_pontuacao_economica++;
												}
											}
										}
										break;
								}
							}
						}
					}
				}
			}
			if ($xmlPontuacao->pontuacao_social_final)
			{
				$valor = 0;
				switch ($xmlPontuacao->pontuacao_social_final)
				{
					case 'contador' : 
						$valor = $contador_pontuacao_social;
						break;
					default :
						$valor = $xmlPontuacao->pontuacao_social_final;
				}
				switch ($xmlPontuacao->pontuacao_social_final['operacao'])
				{
					case 'multiplicacao' : 
						$pontuacao_social *= (float)$valor;
						break;
					case 'divisao' : 
						$pontuacao_social /= (float)$valor;
						break;
					case 'adicao' : 
						$pontuacao_social += (float)$valor;
						break;
					case 'subtracao' : 
						$pontuacao_social -= (float)$valor;
						break;
				}
			}
			if ($xmlPontuacao->pontuacao_economica_final)
			{
				$valor = 0;
				switch ($xmlPontuacao->pontuacao_economica_final)
				{
					case 'contador' : 
						$valor = $contador_pontuacao_economica;
						break;
					default :
						$valor = $xmlPontuacao->pontuacao_economica_final;
				}
				switch ($xmlPontuacao->pontuacao_economica_final['operacao'])
				{
					case 'multiplicacao' : 
						$pontuacao_economica *= (float)$valor;
						break;
					case 'divisao' : 
						$pontuacao_economica /= (float)$valor;
						break;
					case 'adicao' : 
						$pontuacao_economica += (float)$valor;
						break;
					case 'subtracao' : 
						$pontuacao_economica -= (float)$valor;
						break;
				}
			}
		}
	    $pontuacao_social_xml = '<pontuacao_social>';
	    $pontuacao_social_xml .= '<valor>' . $pontuacao_social . '</valor>';
	    $pontuacao_social_xml .= '<campos_pontuados>' . $contador_pontuacao_social . '</campos_pontuados>';
	    $pontuacao_social_xml .= '</pontuacao_social>';
   	    $pontuacao_economica_xml = '<pontuacao_economica>';
   	    $pontuacao_economica_xml .= '<valor>' . $pontuacao_economica . '</valor>';
   	    $pontuacao_economica_xml .= '<campos_pontuados>' . $contador_pontuacao_economica . '</campos_pontuados>'; 
	    $pontuacao_economica_xml .= '</pontuacao_economica>';
		$result['pontuacao_social'] = $pontuacao_social_xml;
		$result['pontuacao_economica'] = $pontuacao_economica_xml;
		return $result;
    }
}
?>