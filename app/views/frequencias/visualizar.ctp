<h3>A frequência(%) contida na tabela é referente ás datas apresentadas na mesma. 
Para saber a frequência geral dos estudantes, vá em relatórios ou selecione todo o 
período letivo do ano desejado.</h3>
<br/>
<br/>

<label class="formulario">Data Inicial:</label>
<?php echo $data_inicio['day'].'/'.$data_inicio['month'].'/'.$data_inicio['year']; ?>
<br/>
<label class="formulario">Data Final:</label>
<?php echo $data_fim['day'].'/'.$data_fim['month'].'/'.$data_fim['year']; ?>
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
        foreach ($turmas as $turma)
        {
                echo '#turma_'.$turma['Turma']['id'].' { display: none; }'."\n";
        }
        ?>

</style>

<?php
foreach ($turmas as $turma)
{
?>	
	<h3><a id="<?php echo 'a_turma_'.$turma['Turma']['id']?>">[+] Turma: <?php echo $turma['Turma']['nome']?> &nbsp;&nbsp;&nbsp;&nbsp; Vagas totais: <?php echo $turma['Turma']['vagas']?>  &nbsp;&nbsp;&nbsp;&nbsp;  Número de estudantes na turma: <?php echo count($estudantes[$turma['Turma']['id']])?></a></h3>
	<hr/>
	<div id="<?php echo 'turma_'.$turma['Turma']['id']?>" class="filtro">
		<table class="listagem" width="auto">
		    <tr class="listagem_header">
		    	<td width="80px" style="text-align:center;">Ano</td>
		        <td width="100px" style="text-align:center;">Número de inscrição</td>
		        <td width="230px" style="text-align:center;">Nome</td>
		        <td width="80px" style="text-align:center;">Freq(%)</td>
				<?php 
		        //adicionar os dias letivos.
		        foreach($dias_letivos as $dia){
		        	echo '<td style="width:90px; text-align:center;">'.$dia[0]['data'].'</td>';
		        }

		        ?>
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

					<td style="text-align:center;"><?php echo $estudante['Candidato']['ano']?> </td>
			        <td style="text-align:center;"><?php echo $estudante['Candidato']['numero_inscricao']?></td>
			        <td style="text-align:center;"><?php echo $html->link($estudante['Candidato']['nome'], '/estudantes/visualizar_ficha/'.$estudante['Candidato']['numero_inscricao'].'/'.$estudante['Candidato']['ano'])?></td>

			        	<?php
			        	//fazer a contagem das frequências para calcular a %
			        	$dias_totais = 0;
			        	$presencas = 0;
			        	foreach ($frequencias[$turma['Turma']['id']][$estudante['Estudante']['estudante_id']] as $frequencia)
			        	{
			        		if($frequencia['frequencia']['presente'] == 'Nao'){
			        			$dias_totais++;
			        		} else if($frequencia['frequencia']['presente'] == 'Sim'){
			        			$presencas++;
			        			$dias_totais++;
			        		} else if($frequencia['frequencia']['presente'] == 'Justificado'){
			        			$presencas++;
			        			$dias_totais++;
			        		}
			        	}
			        	$freq = $presencas*100/$dias_totais;
			        	$freq = round($freq * 100)/100;
			        	if($freq >= 75)
			        		echo '<td style="text-align:center;color:green;"><b>'.$freq.'</b></td>';
			        	else
			        		echo '<td style="text-align:center;color:red;"><b>'.$freq.'</b></td>';
			        	?>

			        <?php
					foreach($dias_letivos as $dia){
						//exibir o status da frequência
				        //buscar pela presença no dia especificado
				        foreach ($frequencias[$turma['Turma']['id']][$estudante['Estudante']['estudante_id']] as $frequencia)
				        {
					        if($dia[0]['data'] == $frequencia[0]['data']){
					        	if($frequencia['frequencia']['presente'] == 'Sim'){
					        		echo '<td style="text-align:center;color:green;">Sim</td>';
					        	} else if($frequencia['frequencia']['presente'] == 'Nao'){
					        		echo '<td style="text-align:center;color:red;">Não</td>';
					        	} else {
					        		//justificado
					        		echo '<td style="text-align:center;color:green;">Jutificado</td>';
					        	}

				        	}	
				        }
			        }
			        ?>

			   		</tr>
			    <?php endforeach; ?>

			<?php endif;?>

		</table>

	</div><br/>
<?php	
}

?>