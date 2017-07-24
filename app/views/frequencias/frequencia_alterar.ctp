<label class="formulario">Dia:</label>
<?php echo $data['day'].'/'.$data['month'].'/'.$data['year']; ?>
<br/>
<label class="formulario">Número de turmas:</label>
<?php echo $count; ?>
<br/>
<br/>

<script type="text/javascript">

	$(function() {

<?php
// imprimindo as flags
foreach ($turmas as $turma)
{
	echo 'var flag_'.$turma['Turma']['id'].' = 0;';
}
?>

<?php
// imprimindo funções
foreach ($turmas as $turma)
{
?>	
		$('a#<?php echo 'a_turma_'.$turma['Turma']['id']?>').click(function() {
			if (<?php echo 'flag_'.$turma['Turma']['id']?> == 0) { 
				$('#<?php echo 'turma_'.$turma['Turma']['id']?>').slideDown('fast'); <?php echo 'flag_'.$turma['Turma']['id']?> = 1;
				$(this).html($(this).html().replace("+","-")); }
			else {
				$('#<?php echo 'turma_'.$turma['Turma']['id']?>').slideUp('fast'); <?php echo 'flag_'.$turma['Turma']['id']?> = 0;
				$(this).html($(this).html().replace("-","+")); }

		});
<?php	
}
?>

	});

</script>

<style type="text/css">

	.selecionado {
		color: blue;
	}

        <?php
        // imprimindo as condigões dos divs
        foreach ($turmas as $turma)
        {
                echo '#turma_'.$turma['Turma']['id'].' { display: none; }'."\n";
        }
        ?>

</style>

<?php
// imprimindo funções

echo $form->create('Frequencia', array('action' => 'alterar_direto', 'class' => 'formulario2'));
echo $form->hidden('day', array('value' => $data['day']));
echo $form->hidden('month', array('value' => $data['month']));
echo $form->hidden('year', array('value' => $data['year']));
$num_estudante = 0;

foreach ($turmas as $turma)
{
?>	
	<h3><a id="<?php echo 'a_turma_'.$turma['Turma']['id']?>">[+] Turma: <?php echo $turma['Turma']['nome']?> &nbsp;&nbsp;&nbsp;&nbsp; Vagas totais: <?php echo $turma['Turma']['vagas']?>  &nbsp;&nbsp;&nbsp;&nbsp;  Número de estudantes na turma: <?php echo count($estudantes[$turma['Turma']['id']])?></a></h3>
	<hr/>
	<div id="<?php echo 'turma_'.$turma['Turma']['id']?>" class="filtro">
		<table class="listagem">
		    <tr class="listagem_header">
		    	<td style="width:60px; text-align:center;">Ano</td>
		        <td style="width:100px; text-align:center;">Número de inscrição</td>
		        <td style="width:460px">Nome</td>
		        <td style="width:200px">Presença</td>
		        <td style="width:160px">Justificativa</td>
		    </tr>

			<?php $numero_linha = 0; ?>

			<?php if (is_array($estudantes[$turma['Turma']['id']])):?>

			    <?php foreach ($estudantes[$turma['Turma']['id']] as $estudante):?>

			    	<?php $numero_linha++ ?>

					<?php if ($numero_linha % 2 == 1): ?>
					<tr class="linha_impar">
					<?php else: ?>
					<tr class="linha_par">
					<?php endif; ?>

					<td style="text-align:center;"> <?php echo $estudante['Candidato']['ano']?> </td>
			        <td style="text-align:center;"><?php echo $estudante['Candidato']['numero_inscricao']?></td>
			        <td ><?php echo $html->link($estudante['Candidato']['nome'], '/estudantes/visualizar_ficha/'.$estudante['Candidato']['numero_inscricao'].'/'.$estudante['Candidato']['ano'])?> </td>
					<td >
						<?php echo $form->hidden('estudante_id'.$num_estudante, array('value' => $estudante['Estudante']['estudante_id']));?>
						<?php echo $form->hidden('frequencia_id'.$num_estudante, array('value' => $frequencias[$turma['Turma']['id']][$estudante['Estudante']['estudante_id']][0]['frequencia']['frequencia_id']));?>

						<?php echo $form->radio('presente'.$num_estudante, array('Sim' => 'Presente<br>', 'Nao' => 'Falta<br>', 'Justificado' => 'Justificado'), array('legend' => false, 'default' => $frequencias[$turma['Turma']['id']][$estudante['Estudante']['estudante_id']][0]['frequencia']['presente']));?>

					</td>
					<td><?php echo $form->text('observacao'.$num_estudante, array('value' => $frequencias[$turma['Turma']['id']][$estudante['Estudante']['estudante_id']][0]['frequencia']['observacao']));?></td>
					<?php $num_estudante++;?>
			   		</tr>
			    <?php endforeach; ?>

			<?php endif;?>

		</table>

	</div><br/>
<?php	
}
echo $form->hidden('num_estudantes', array('value' => $num_estudante));
echo $form->end('Salvar Frequência');
?>

