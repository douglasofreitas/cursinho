<h3>Informações sobre sala.</h3>
<br />
<?php
	echo $form->create('Sala', array('url' => array('controller' => 'salas', 'action' =>$metodo_destino), 'class' => 'formulario'));

	echo $form->hidden('sala_id');
	echo $form->input('ano_letivo', array('label' => 'Ano letivo'));
	echo '<br/>';
	echo $form->input('numero', array('label' => 'Número da sala'));
	echo '<br/>';
	echo $form->input('quantidade_vagas', array('label' => 'Quantidade de vagas'));
	echo '<br/>';
	echo $form->input('unidade', array('label' => 'Unidade', 'options' => array(
	    'ufscar'=>'UFSCar',
	    'aracy'=>'Aracy'
	)));
	echo '<br/>';

	echo $form->end('Concluir');
?>
