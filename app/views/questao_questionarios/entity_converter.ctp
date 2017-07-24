<?php 
	echo $form->create('', array('action' => 'entity_converter', 'class' => 'formulario'));

	echo $form->textarea('xmlString', array('rows' => '20', 'cols' => '90'));
	echo '<br/><br/>';

	echo $form->end('Converter');
?>
