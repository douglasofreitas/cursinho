<h3>Digite o ano letivo para a geração da lista.</h3>
<br />
<?php
	echo $form->create('Ano', array('url' => array('controller' => 'coordenadors', 'action' =>$metodo_destino), 'class' => 'formulario'));

	echo $form->input('ano', array('label' => 'Ano', 'size' => '10'));
	echo '<br/>';

	echo $form->end('Avançar');
?>
