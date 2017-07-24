<h3>Número de questões: <?php echo $numero_questoes?></h3>
<br/>
<h3>Para preencher a PROVA ESPECIAL, <?php echo $html->link('clique aqui', '/candidatos/inserir_nota_prova_especial/'.$numero_inscricao.'/'.$ano);?></h3> 
<br/>
<br/>
<?php
	echo $form->create('RespostaQuestaoProva', array('action' => 'inserir/'.$numero_inscricao.'/'.$ano, 'class' => 'formulario'));

	for($i = 1; $i <= $numero_questoes; $i++)
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

	echo $form->end('Salvar');
?>
