<h3>Alterar Prova <?echo $prova_ano;?></h3>
<br/>
Número de questoes existentes: <?php echo $num_questoes; ?>
<br />
<?php echo $form->create('QuestaoProva', array('action' => 'alterar/'.$prova_id, 'class' => 'formulario'));?>
<?php $numero_questao = 0; ?>
<?php if ($num_questoes > 0): ?>
	<?php $numero_questao ++; ?> 
	<!-- Exibir as questoes existentes-->
	<br />

	<?php foreach ($questoes as $questao): ?>
		<questao> Questão <?echo $questao['QuestaoProva']['numero_questao']?>:<br/>
			<label for=<?echo '"enunciado'.$questao['QuestaoProva']['numero_questao'].'"';?>>Enunciado</label><textarea name=<?echo '"data[enunciado'.$questao['QuestaoProva']['numero_questao'].']"';?> label="Enunciado" cols="30" rows="3" id=<?echo '"enunciado'.$questao['QuestaoProva']['numero_questao'].'"';?> ><?php echo $questao['QuestaoProva']['habilidade_avaliada'];?></textarea><br/>
			<label for=<?echo '"alternativa_correta'.$questao['QuestaoProva']['numero_questao'].'"';?>>Alterantiva Correta</label><select name=<?echo '"data[alternativa_correta'.$questao['QuestaoProva']['numero_questao'].']"';?> id="alternativa_correta'+i+'">
				<option value=""  <?if($questao['QuestaoProva']['alternativa_correta'] == "") echo 'selected="selected"';?>></option>
				<option value="1" <?if($questao['QuestaoProva']['alternativa_correta'] == 1) echo 'selected="selected"';?>>A</option>
				<option value="2" <?if($questao['QuestaoProva']['alternativa_correta'] == 2) echo 'selected="selected"';?>>B</option>
				<option value="3" <?if($questao['QuestaoProva']['alternativa_correta'] == 3) echo 'selected="selected"';?>>C</option>
				<option value="4" <?if($questao['QuestaoProva']['alternativa_correta'] == 4) echo 'selected="selected"';?>>D</option>
				<option value="5" <?if($questao['QuestaoProva']['alternativa_correta'] == 5) echo 'selected="selected"';?>>E</option>
			</select><br/>
			<div class="input text"><label for=<?echo '"habilidade_avaliada'.$questao['QuestaoProva']['numero_questao'].'"';?>>Habilidade Avaliada</label><input name=<?echo '"data[habilidade_avaliada'.$questao['QuestaoProva']['numero_questao'].']"';?> type="text" id=<?echo '"habilidade_avaliada'.$questao['QuestaoProva']['numero_questao'].'"';?> value=<?php echo '"'.$questao['QuestaoProva']['habilidade_avaliada'].'"';?>/></div><br/>
			<br/> 
		</questao>
    <?php endforeach; ?>
	<br />

<?php else: ?>

<?php endif; ?>
<!--Aqui é onde pode ser adicionada novas questões-->
<questoes id="nav">
</questoes>
<botao_inserir id="nav">
<input type="submit" value="Alterar Questões"/>
</botao_inserir>
</form>
<br />
<a id="add_questao_alterar" href="#">Adicionar questão</a>
<br />
<a id="remove_questao" href="#">Remover questão</a>

