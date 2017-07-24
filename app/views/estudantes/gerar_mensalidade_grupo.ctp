<script type="text/javascript">
    function marcar_todos(){
        $('input[type="checkbox"').attr('checked', true);
    }
    function desmarcar_todos(){
        $('input[type="checkbox"').attr('checked', false);
    }

</script>

<h3>
Na lista abaixo estão listados somente os estudantes que não possuem mensalidade do mês de referência.
</h3>
<br/>

<?php 
	echo $form->create('Estudante', array('url' => array('controller' => 'estudantes', 'action' => 'gerar_mensalidade_grupo/'.$ano_letivo.'/'.$mes), 'class' => 'formulario'));

	echo $form->input('Fatura.dia', array('label' => 'Dia de vencimento', 'value' => 1));
	echo '<br/>';
	
    echo '<label for="EstudanteMes">Mês</label>';
	echo $mes;
    echo '<br/>';
	
	echo $form->input('Fatura.valor', array('label' => 'Valor das mensalidades'));
	echo '<br/>';

	echo '<br/>';
	echo 'Total de estudantes do ano letivo: '.count($estudantes);
	
	//echo '<pre>';
	//print_r($estudantes_fatura);
	//echo '</pre>';
	
	?>
<br/>
Entre parenteses estão o número de parcelas com o vencimento no mês selecionado.
<br/><br/>
<a style="cursor: pointer" onclick="marcar_todos()">Marcar todos</a><br/>
<a style="cursor: pointer" onclick="desmarcar_todos()">Desmarcar todos</a>
<br/><br/>
	<table class="listagem">
		<tr class="listagem_header">
			<td style="width:111px;text-align:center;background-color:white;"></td>
			<td style="width:60px; text-align:center;"><?php echo $paginator->sort('Ano', 'ano'); ?></td>
			<td style="width:60px; text-align:center;"><?php echo $paginator->sort('Número', 'numero_inscricao'); ?></td>
			<td style="width:380px"><?php echo $paginator->sort('Nome', 'nome'); ?></td>
		</tr>
		<?php $numero_linha = 0; ?>

		<?php foreach ($estudantes as $estudante): ?>

				<?php $numero_linha++ ?>
				
				<?php if ($numero_linha % 2 == 1): ?>
					<tr class="linha_impar">
				<?php else: ?>
					<tr class="linha_par">
				<?php endif; ?>
					<td style="text-align:center">
						<?php
							//echo $form->label('mensalidade', '');
							echo $form->checkbox('mensalidade.'.$estudante['Estudante']['estudante_id'], array('checked' => false));
                            if( ! empty($estudantes_fatura[$estudante['Estudante']['estudante_id']])  ){
                                echo ' ('.$estudantes_fatura[$estudante['Estudante']['estudante_id']].' fatura(s) )';
                            }
						?>
					</td>
					<td style="text-align:center"><?php echo $estudante['Candidato']['ano'] ?> </td>
					<td style="text-align:center"><?php echo $estudante['Candidato']['numero_inscricao'] ?></td>
					<td>
						<?php echo $estudante['Candidato']['nome'] ?>
                        <span style="float: right">
                            <?php
                            echo $html->link('Ver mensalidades', '/estudantes/visualizar_mensalidades/'.$estudante['Estudante']['estudante_id'], array('style' => 'color:black'))
                            ?>
                        </span>
					</td>
				</tr>

		<?php endforeach; ?>
	</table>
	
	<?php

	echo $form->end('Registrar');
?>
