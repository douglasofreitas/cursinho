<?php 
class QuestionarioHelper extends Helper
{
	// Converte a questão do formato xml para um formulário html
	function montar_questao($questao)
	{
		$xmlQuestao = simplexml_load_string($questao);
		$htmlQuestao = '';

		$numero = '';

		if ($xmlQuestao['numero'] != '')
			$numero = $xmlQuestao['numero'] . ' - ';

		//$htmlQuestao .= '<p class="questionario"> (' . $xmlQuestao['id'] . ')' . $numero . $xmlQuestao->texto . '</p><br/>' . "\n";
		$htmlQuestao .= '<p class="questionario">' . $numero . $xmlQuestao->texto . '</p><br/>' . "\n";
		switch ($xmlQuestao['tipo'])
		{
			case 'aberta' :
				$htmlQuestao .= '<input class="questionario_questao_aberta" type="text" id="qid'.$xmlQuestao['id'].'_txtCampo" name="qid'.$xmlQuestao['id'].'_txtCampo" 
					value="{qid'.$xmlQuestao['id'].'_txtCampo_txtValue}"/>' . "\n";

				break;

			case 'campo' :
				$htmlQuestao .= '<textarea class="questionario" id="qid'.$xmlQuestao['id'].'_txtCampo" name="qid'.$xmlQuestao['id'].'_txtCampo"
					rows="'.$xmlQuestao->rows.'" cols="'.$xmlQuestao->cols.'">{qid'.$xmlQuestao['id'].'_txtCampo_txtValue}</textarea>' . "\n";

				break;

			case 'radio' :
			case 'checkbox' :
				$contador = 1;

				foreach ($xmlQuestao->alternativas->opcao as $opcao)
				{
					if ($xmlQuestao['tipo'] == 'radio')
					{
						$htmlQuestao .= '<input class="questionario" id="qid'.$xmlQuestao['id'].'_rbtOpcao_'.$contador.'" name="qid'.$xmlQuestao['id'].'_rbtOpcao"
							type="radio" value="'.$contador.'" c="{qid'.$xmlQuestao['id'].'_rbtOpcao_'.$contador.'}" />' . "\n";

						$htmlQuestao .= '<label class="questionario" for="qid'.$xmlQuestao['id'].'_rbtOpcao_'.$contador.'">'.$opcao->texto.'</label>' . "\n";
					}
					else
					{
						$htmlQuestao .= '<input class="questionario" id="qid'.$xmlQuestao['id'].'_chkOpcao_'.$contador.'" name="qid'.$xmlQuestao['id'].'_chkOpcao_'.$contador.'"
							type="checkbox" value="1" c="{qid'.$xmlQuestao['id'].'_chkOpcao_'.$contador.'}" />' . "\n";
							//type="checkbox" value="'.$contador.'" c="{qid'.$xmlQuestao['id'].'_chkOpcao_'.$contador.'}" />' . "\n";

						$htmlQuestao .= '<label class="questionario" for="qid'.$xmlQuestao['id'].'_chkOpcao_'.$contador.'">'.$opcao->texto.'</label>' . "\n";
					}

					switch ($opcao->questao['tipo'])
					{
						case 'aberta' :
							$htmlQuestao .= '<label class="questionario" for="qid'.$xmlQuestao['id'].'_txtCampo'.$contador.'"> - '.$opcao->questao->texto.'</label>' . "\n";

							$htmlQuestao .= '<input class="questionario" type="text" id="qid'.$xmlQuestao['id'].'_txtCampo'.$contador.'" name="qid'.$xmlQuestao['id'].'_txtCampo'.$contador.'" 
								value="{qid'.$xmlQuestao['id'].'_txtCampo'.$contador.'_txtValue}"/>' . "\n";

							break;

						case 'campo' :
							//$htmlQuestao .= '<br/><label class="questionario" for="qid'.$xmlQuestao['id'].'_txtArea'.$contador.'"> - '.$opcao->questao->texto.'</label>' . "\n";
							$htmlQuestao .= '<p>'.$opcao->questao->texto.'</p>';

							$htmlQuestao .= '<textarea class="questionario" id="qid'.$xmlQuestao['id'].'_txtArea'.$contador.'" name="qid'.$xmlQuestao['id'].'_txtArea'.$contador.'"
								rows="'.$opcao->questao->rows.'" cols="'.$opcao->questao->cols.'">{qid'.$xmlQuestao['id'].'_txtArea'.$contador.'_txtValue}</textarea>' . "\n";

							break;
					}

					$htmlQuestao .= '<br/>' . "\n";
					$contador++;
				}

				break;

			case 'fixa' :				
				$htmlQuestao .= $xmlQuestao->codigo;

				break;

		}

		$htmlQuestao .= '<br/><br/>' . "\n";

		return html_entity_decode($htmlQuestao);
	}

	// Preenche os campos de uma questão com os dados da resposta
	function preencher_questao(&$questao, $xmlResposta)
	{
		$aspas = '"';

		if ($xmlResposta && $xmlResposta->campos->campo)
		{
			foreach ($xmlResposta->campos->campo as $campo)
			{	
				switch ($campo->tipo)
				{
					case 'textBox' :
						$questao = str_replace('{' . $campo->nome . '_txtValue}', $campo->valor, $questao);
						break;

					case 'checkBox' :
						$questao = str_replace ('c='  .$aspas . '{' . $campo->nome . '}' . $aspas, 'checked="true"', $questao);
						break;

					case 'radioButton' :
						$questao = str_replace('c=' . $aspas . '{' . $campo->nome . '_' . $campo->valor . '}' .$aspas, 'checked="true"', $questao);
						break;
				}
			}								
		}
		else
		{
			if (preg_match_all("/{.*}/", $questao, $matches))
			{
				foreach ($matches[0] as $match)
				{
					$questao = str_replace($match, '', $questao);
				}
			}
		}

		if (preg_match_all("/c=" . $aspas . ".*" . $aspas . "/", $questao, $matches))
		{
			foreach ($matches[0] as $match)
			{
				$questao = str_replace($match, '', $questao);
			}
		}
	}
}
?>
