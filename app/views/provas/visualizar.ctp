<!-- <h3>Prova <?php //echo $prova_ano; ?></h3>
<br />
-->
<?php
	/*echo $form->create('Prova', array('action' => 'upload/' . $prova_id . '/' . $prova_ano, 'class' => 'formulario'));

	echo $form->input('arquivo', array('label' => 'Arquivo', 'type' => 'file'));
	echo '<br/>';

	if ($prova_arquivo != '')
		echo '<h3>' . $prova_arquivo . '</h3>';
	else
		echo '<h3>Nenhum arquivo</h3>';

	echo '<br/>';
	echo $form->end('Fazer Upload');*/
?>
<!-- Este form deve ser retirado, só usei para ficar formatado om itens na tela -->
<?php echo $form->create('QuestaoProva', array('class' => 'formulario'));?>
<?php if ($num_questoes > 0): ?>
	<h3><?php echo $html->link('Adicionar questão', '/questao_provas/inserir/'.$prova_id, array('style' => 'color:#0000CC'));?></h3>
	<br />
	<!-- Exibir as questoes existentes-->
	<br />
	<h3>Número de questões: <?php echo $num_questoes; ?></h3>
	<br />
	<br />

	<table>

	<?php foreach ($questoes as $questao): ?>
		<tr>

		<td width="600px">
    	<h3><?php echo 'Questão '.$questao['QuestaoProva']['numero_questao'].'';?></h3>
    	<hr/>
    	<br />
    	<label>Enunciado:</label> <div align="justify"><?php echo $questao['QuestaoProva']['enunciado'];?></div>
		<br />
		<label>Alternativa Correta:</label> <?php echo $questao['QuestaoProva']['alternativa_correta'];?>
		<br />
		<label>Habilidade avaliada:</label> <?php echo $questao['HabilidadeAvaliada']['habilidade'];?>
		<br />
		<label>Anulada:</label> 
		<?php if($questao['QuestaoProva']['anulada'] == 0):?>
			NAO
		<?php else:?>
			SIM
		<?php endif;?>
		<br />
		<br />
		</td>

		<td width="100px">
		<?php echo $html->link('Editar', '/questao_provas/alterar/'.$prova_ano.'/'.$questao['QuestaoProva']['numero_questao'], array('style' => 'color:#0000CC'));?>
		<br />
		<br />
		<?php echo $html->link('Remover', '/questao_provas/remover/'.$prova_ano.'/'.$questao['QuestaoProva']['numero_questao'], array('style' => 'color:#0000CC'));?>
		</td>
		</tr>
    <?php endforeach; ?>
    </table>
	<br />
<?php else: ?>
<h3>Não há questões cadastradas nesta prova ainda</h3>
<?php endif; ?>
<br />
<h3><?php echo $html->link('Adicionar questão', '/questao_provas/inserir/'.$prova_id, array('style' => 'color:#0000CC'));?></h3>
</form>
