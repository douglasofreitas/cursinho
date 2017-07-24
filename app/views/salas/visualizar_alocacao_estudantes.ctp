<label class="formulario">Ano Letivo:</label>
<?php echo $ano ?>
<br/>
<label class="formulario">Número de salas:</label>
<?php echo $num_salas; ?>
<br/>
<br/>
<label class="formulario">Números de estudantes alocados:</label> <?php echo $num_estudantes_alocados;?>
<br/>
<?php if($num_estudantes_nao_alocados > 0):?>
<label class="formulario" style="color=red">Números de estudantes que NÃO estão alocados:</label> <?php echo $num_estudantes_nao_alocados;?>
<?php endif;?>
<br/>
<br/>
<script type="text/javascript">
	$(function() {

<?
// imprimindo as flags
foreach ($salas as $sala)
{
	echo 'var flag_'.$sala['Sala']['sala_id'].' = 0;';
}
?>

<?
// imprimindo funções
foreach ($salas as $sala)
{
?>	
		$('a#<?echo 'a_sala_'.$sala['Sala']['sala_id']?>').click(function() {
			if (<?echo 'flag_'.$sala['Sala']['sala_id']?> == 0) { 
				$('#<?echo 'sala_'.$sala['Sala']['sala_id']?>').slideDown('fast'); <?echo 'flag_'.$sala['Sala']['sala_id']?> = 1;
				$(this).html($(this).html().replace("+","-")); }
			else {
				$('#<?echo 'sala_'.$sala['Sala']['sala_id']?>').slideUp('fast'); <?echo 'flag_'.$sala['Sala']['sala_id']?> = 0;
				$(this).html($(this).html().replace("-","+")); }

		});
<?	
}
?>
	});
</script>
<style type="text/css">
	.selecionado {
		color: blue;
	}

<?
// imprimindo as condigões dos divs
foreach ($salas as $sala)
{
	echo '#sala_'.$sala['Sala']['sala_id'].' { display: none; }'."\n";
}
?>

</style>

<?
// imprimindo funções
foreach ($salas as $sala)
{
?>	
	<h3><a id="<?echo 'a_sala_'.$sala['Sala']['sala_id']?>">[+] Sala: <? echo $sala['Sala']['numero']?> &nbsp;&nbsp;&nbsp;&nbsp; Vagas totais: <? echo $sala['Sala']['quantidade_vagas']?>  &nbsp;&nbsp;&nbsp;&nbsp;  Número de estudantes na sala: <? echo count($estudantes_sala[$sala['Sala']['sala_id']])?></a></h3>
	<hr/>
	<div id="<?echo 'sala_'.$sala['Sala']['sala_id']?>" class="filtro">
		<table class="listagem">
		    <tr class="listagem_header">
		    	<td style="width:60px; text-align:center;">Ano</td>
		        <td style="width:100px; text-align:center;">Número de inscrição</td>
		        <td style="width:530px">Nome</td>
		    </tr>

			<?php $numero_linha = 0; ?>

			<?php if (is_array($estudantes_sala[$sala['Sala']['sala_id']])):?>

			    <?php foreach ($estudantes_sala[$sala['Sala']['sala_id']] as $estudante):?>

			    	<?php $numero_linha++ ?>

					<?php if ($numero_linha % 2 == 1): ?>
					<tr class="linha_impar">
					<?php else: ?>
					<tr class="linha_par">
					<?php endif; ?>

					<td style="text-align:center;"> <?= $estudante['Candidato']['ano']?> </td>
			        <td style="text-align:center;"><?= $estudante['Candidato']['numero_inscricao']?></td>
			        <td ><?php echo $html->link($estudante['Candidato']['nome'], '/estudantes/visualizar_ficha/'.$estudante['Candidato']['numero_inscricao'].'/'.$estudante['Candidato']['ano'])?> </td>

			   		</tr>
			    <?php endforeach; ?>

			<?php endif;?>

		</table>
	</div><br/>
<?	
}
?>
