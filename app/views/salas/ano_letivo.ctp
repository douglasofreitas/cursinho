<h3>Insira o ano do processo seletivo.</h3>
<br />
<?php
	echo $form->create('AnoLetivo', array('url' => array('controller' => 'salas', 'action' =>$metodo_destino), 'class' => 'formulario'));

	echo $form->input('ano', array('label' => 'Ano', 'size' => '10'));
	echo '<br/>';

	echo $form->end('Avançar');
?>
