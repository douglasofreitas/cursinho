<h3>Total de candidatos da ultima chamada: <?php echo $paginator->counter(array('format' => '%count%')); ?></h3>
<br/>
<!--
<h3><?php //echo $paginator->counter(array('format' => 'Página %page% de %pages%')); ?></h3>
<br/>
<h3><?php //echo $paginator->counter(array('format' => 'Mostrando registros de %start% até %end%')); ?></h3>
<br/>
-->
<?php $paginator->options(array('url' => $ano)) ?>
<h3><?php echo $html->link('Exportar resultados', '/candidatos/montar_relatorio', array('style' => 'color: #0000CC;')); ?></h3><br/>
<hr/>
<h3><?php echo $paginator->counter(array('format' => 'Total de páginas: %pages%')); ?></h3>
<br/>
<?php 
	echo $paginator->prev(' << Anterior ', null, null, null);
	echo $paginator->numbers();
	echo $paginator->next(' Próximo >> ', null, null, null);
?>
<table class="listagem">
    <tr class="listagem_header">
    	<td style="width:60px; text-align:center;"><?php echo $paginator->sort('Ano', 'ano'); ?></td>
        <td style="width:60px; text-align:center;"><?php echo $paginator->sort('Número', 'numero_inscricao'); ?></td>
        <td style="width:380px"><?php echo $paginator->sort('Nome', 'nome'); ?></td>
        <td style="width:100px; text-align:center;">Inscrição</td>
        <td style="width:100px; text-align:center;"><?php echo $paginator->sort('Questionário', 'pontuacao_social'); ?></td>
        <td style="width:100px; text-align:center;"><?php echo $paginator->sort('Prova', 'nota_prova'); ?></td>
    </tr>
	<?php $numero_linha = 0; ?>

    <?php foreach ($candidatos as $candidato): ?>

    	<?php $numero_linha++ ?>

		<?php if ($numero_linha % 2 == 1): ?>
		<tr class="linha_impar">
		<?php else: ?>
		<tr class="linha_par">
		<?php endif; ?>
			<td style="text-align:center"><?php echo $candidato['Candidato']['ano'] ?></td>
	        <td style="text-align:center"><?php echo $candidato['Candidato']['numero_inscricao'] ?></td>
	        <td>
	        	<?php echo $candidato['Candidato']['nome'] ?>
	        </td>

			<td style="text-align:center">
	        	<?php echo $html->link('visualizar', "/candidatos/visualizar/" . $candidato['Candidato']['numero_inscricao']
	        		. "/" . $candidato['Candidato']['ano']); ?>
	        </td>

	        <?php if ($candidato['Candidato']['questionario_vazio'] == '1'): ?>
	        	<td class="questionario_em_branco" style="text-align:center;">
		        	<?php echo $html->link('em branco', '/questao_questionarios/preencher/'.$candidato['Candidato']['numero_inscricao'].'/'.$candidato['Candidato']['ano'].'/1'); ?>
	        	</td>
	        <?php else: ?>
	        	<td class="questionario_preenchido" style="text-align:center">
	        		<?php echo $html->link('visualizar', '/questao_questionarios/preencher/'.$candidato['Candidato']['numero_inscricao'].'/'.$candidato['Candidato']['ano'].'/1'); ?>
	        	</td>
	        <?php endif; ?>

	        <?php if ($candidato['Candidato']['nota_prova'] == null or $candidato['Candidato']['nota_prova'] == ''): ?>
				<td class="questionario_em_branco" style="text-align:center"><?php echo $html->link('em branco', '/candidatos/preencher_prova/'.$candidato['Candidato']['numero_inscricao'].'/'.$candidato['Candidato']['ano']);?></td>
			<?php else: ?>
				<td class="questionario_preenchido" style="text-align:center"><?php echo $html->link('visualizar', '/candidatos/visualizar_respostas_questao_prova/'.$candidato['Candidato']['numero_inscricao'].'/'.$candidato['Candidato']['ano']);?></td>
			<?php endif; ?>
   		</tr>
    <?php endforeach; ?>
</table>
<br/>
<?php 
	echo $paginator->prev(' << Anterior ', null, null, null);
	echo $paginator->numbers();
	echo $paginator->next(' Próximo >> ', null, null, null);
?>	