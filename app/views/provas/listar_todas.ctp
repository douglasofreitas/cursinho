<h3>Total de provas cadastradas: <?php echo $paginator->counter(array('format' => '%count%')); ?></h3>
<br/>

<?php echo $paginator->numbers(); ?>
<?php 
	//echo $paginator->prev('<< Anterior', null, null, array('class' => 'disabled'));
	//echo $paginator->next('Próximo >>', null, null, array('class' => 'disabled'));
	echo $paginator->prev('<< Anterior', null, null, null);
	echo $paginator->next('Próximo >>', null, null, null);
?>
<table class="listagem">
    <tr class="listagem_header">
        <td style="width:60px; text-align:center;"><?php echo $paginator->sort('Ano', 'ano'); ?></td>
        <td style="width:380px">Nome</td>
        <td style="width:100px">Nº Questões</td>
        <td style="width:100px; text-align:center;">Visualizar</td>
    </tr>
	<?php $numero_linha = 0; ?>

    <?php foreach ($provas as $prova): ?>

    	<?php 
   		$num_questoes = 0;

   		foreach($prova['QuestaoProva'] as $questao)
   		{
   			$num_questoes++;
   		}

   		?>

    	<?php $numero_linha++ ?>

		<?php if ($numero_linha % 2 == 1): ?>
		<tr class="linha_impar">
		<?php else: ?>
		<tr class="linha_par">
		<?php endif; ?>
	        <td style="text-align:center"><?php echo $prova['Prova']['ano'] ?></td>
	        <td>
	        	<?php echo 'Prova de Conhecimentos Gerais' ?>
	        </td>
	        <td style="text-align:center"><?php echo $num_questoes; ?></td>
			<td style="text-align:center">
	        	<?php 
					echo $html->link(
					    $html->image('icon/find.png', array("alt" => "Visualizar", 'class' => 'tipHoverBottom', 'title' => 'Visualizar')),
					    "/provas/visualizar/" . $prova['Prova']['ano'],
					    array('escape' => false)
					); 
	        	?>
	        </td>
   		</tr>
    <?php endforeach; ?>
</table>
