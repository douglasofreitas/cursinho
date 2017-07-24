<h3>Visualizar gabarito da prova</h3>
<br/>
<h3>Número de inscrição: <?php echo $numero_inscricao;?></h3> 
<br/>
<h3>Ano: <?php echo $ano;?></h3>
<br/>
<br/>
<h3><?php echo $html->link('Editar respostas da prova', '/resposta_questao_provas/alterar/'.$numero_inscricao.'/'.$ano, array('style' => 'color:#0000CC'));?></h3>
<br/>
<br/>
<?php
	$i = 0;
	foreach($respostas as $resposta)
	{
		echo '<label class="formulario">Questão '.$resposta['QuestaoPrvoa']['numero_questao'].': </label> '.$resposta['RespostaQuestaoProva']['alternativa_marcada'];

		echo '<br/>';
		$i++;
	}

?>
