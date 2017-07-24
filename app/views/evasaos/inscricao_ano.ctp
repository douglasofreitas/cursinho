<h3>Insira o número de inscrição e no do estudante.</h3>
<br />
<?php
	echo $form->create('Estudante', array('url' => array('controller' => 'evasaos', 'action' =>$metodo_destino), 'class' => 'formulario'));

	echo $form->input('estudante_id', array('label' => 'Código do estudante', 'size' => '10', 'type' => 'text'));
	echo '<br/>';
	//echo $form->input('ano', array('label' => 'Ano', 'size' => '10'));
	//echo '<br/>';

	echo $form->end('Avançar');
?>
