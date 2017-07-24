<h3><?php echo $paginator->counter(array('format' => 'Total de páginas: %pages%')); ?></h3>
<br/>
<?php 
	$paginator->options = array('url' => array($ano, $turma));
	echo $paginator->prev(' << Anterior', null, null, null);
	echo ' '.$paginator->numbers().' ';
	echo $paginator->next('Próximo >> ', null, null, null);
?>
<br/><br/>
<?php echo $form->create('Candidato', array('url' => '/provas/listar_candidatos_action/')); ?>
<?php echo $form->button('Salvar', array('type' => 'submit')); ?>
<?php echo $form->hidden('url', array('value' => $this->params['url']['url'])) ?>
<br/><br/>
<table class="listagem">
    <tr class="listagem_header">
    	<td style="width:60px; text-align:center;"><?php echo $paginator->sort('Ano', 'ano'); ?></td>
        <td style="width:60px; text-align:center;"><?php echo $paginator->sort('Número', 'numero_inscricao'); ?></td>
        <td style="width:380px"><?php echo $paginator->sort('Nome', 'nome'); ?></td>

        <td style="width:100px; text-align:center;"><?php echo $paginator->sort('Nota da Prova', 'pontuacao_social'); ?></td>
        <td style="width:100px; text-align:center;"><?php echo $paginator->sort('Fase Eliminatória', 'fase_eliminatoria_status'); ?></td>
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
            <td>
                <?php echo $form->input('Candidato.'.$candidato['Candidato']['candidato_id'].'.nota_prova', array('label' => false, 'div' => false, 'style' => 'width: 50px;', 'value' => $candidato['Candidato']['nota_prova'])); ?>
	        </td>

		    <td style="text-align:center">
                        <?php //echo $form->input('Candidato.'.$candidato['Candidato']['candidato_id'].'.fase_eliminatoria_status', array('label' => '', 'default' => '1')) ?>
                        <?php
                                if ($candidato['Candidato']['fase_eliminatoria_status'] == '1')
                                        echo $form->checkbox('Candidato.'.$candidato['Candidato']['candidato_id'].'.fase_eliminatoria_status', array('checked' => true));
                                else
                                        echo $form->checkbox('Candidato.'.$candidato['Candidato']['candidato_id'].'.fase_eliminatoria_status', array('checked' => false));
                        ?>
	        </td>
   		</tr>
    <?php endforeach; ?>
</table>
<?php echo $form->end(); ?>
