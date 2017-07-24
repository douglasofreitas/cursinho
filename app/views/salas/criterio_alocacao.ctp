<h3>Escolha qual o critério a ser usado .</h3>
<br />
<?php
	echo $html->link('Alocar por nota de prova', '/salas/definir_criterio_alocacao/'.$ano.'/nota');
	echo '<br /><br />';
	echo $html->link('Alocar por idade', '/salas/definir_criterio_alocacao/'.$ano.'/idade');

?>
