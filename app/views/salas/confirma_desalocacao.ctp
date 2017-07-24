<h3>Confirma remoção dos estudantes do ano <?php echo $ano?>?</h3>
<br />
<?php
	echo $html->link('Sim', '/salas/confirma_desalocacao/'.$ano.'/sim');
	echo '<br /><br />';
	echo $html->link('Não', '/salas/confirma_desalocacao/'.$ano.'/nao');

?>
