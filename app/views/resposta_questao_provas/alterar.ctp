<h3>Número de questões: <?php echo $numero_questoes?></h3>
<br/>
<br/>
<?php
	echo $form->create('RespostaQuestaoProva', array('action' => 'alterar/'.$numero_inscricao.'/'.$ano, 'class' => 'formulario'));

	$mostra_else = true;

	for($i = 1; $i <= $numero_questoes; $i++)
	{
		$mostra_else = true;

		foreach($respostas as $resposta)
		{
			if(isset($resposta['QuestaoProva']['numero_questao']))
				if($resposta['QuestaoProva']['numero_questao'] == $i)
				{
					echo $form->input('alternativa_marcada'.$i, array('label' => 'Questão '.$i.':', 'options' => array(
					    ''=>'',
						'A'=>'A',
					    'B'=>'B',
					    'C'=>'C',
					    'D'=>'D',
					    'E'=>'E'
					), 'selected' => $resposta['RespostaQuestaoProva']['alternativa_marcada']));

					echo '<br/>';
					$mostra_else = false;
				}	
		}

		if($mostra_else)
		{
			echo $form->input('alternativa_marcada'.$i, array('label' => 'Questão '.$i.':', 'options' => array(
			    ''=>'',
				'A'=>'A',
			    'B'=>'B',
			    'C'=>'C',
			    'D'=>'D',
			    'E'=>'E'
			), 'selected' => ''));

			echo '<br/>';
		}

	}

	echo $form->end('Alterar');
?>
