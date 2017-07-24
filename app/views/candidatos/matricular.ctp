<h3>Total de candidatos aprovados: <?php echo $paginator->counter(array('format' => '%count%')); ?></h3>
<br/>
<h3>Abaixo estão listados todos os candidatos que foram aprovados na fase classificatória.</h3>
<br/>
<h3>Para matricular candidatos, marque a caixa da coluna Matriculado e em seguida clique no botão Matricular</h3>
<br/>
<?php echo $form->create('Candidato', array('action' => 'matricular/' . $ano)); ?>
<?php echo $form->button('Matricular', array('type' => 'submit')); ?>
<br/><br/>
<table class="listagem">
    <tr class="listagem_header">
		<td style="width:60px;text-align:center;background-color:white;"></td>
    	<td style="width:60px; text-align:center;"><?php echo $paginator->sort('Ano', 'ano'); ?></td>
        <td style="width:60px; text-align:center;"><?php echo $paginator->sort('Número', 'numero_inscricao'); ?></td>
        <td style="width:380px"><?php echo $paginator->sort('Nome', 'nome'); ?></td>
    </tr>
	<?php $numero_linha = 0; ?>

    <?php foreach ($candidatos as $candidato): ?>

    	<?php $numero_linha++ ?>

		<?php if ($numero_linha % 2 == 1): ?>
		<tr class="linha_impar">
		<?php else: ?>
		<tr class="linha_par">
		<?php endif; ?>
			<td style="text-align:center">
	        	<?php
	        		echo $form->label('matriculado', '');

	        		if ($candidato['Candidato']['matriculado'] == '1')
						echo $form->checkbox('matriculado'.$numero_linha, array('checked' => true));
					else
						echo $form->checkbox('matriculado'.$numero_linha, array('checked' => false));
				?>
	        </td>
			<td style="text-align:center"><?php echo $candidato['Candidato']['ano'] ?></td>
	        <td style="text-align:center"><?php echo $candidato['Candidato']['numero_inscricao'] ?></td>
	        <td>
	        	<?php echo $candidato['Candidato']['nome'] ?>
	        </td>
   		</tr>
    <?php endforeach; ?>
</table>
<?php echo $form->end() ?>
