<h3>Informe o número de inscrição e o ano do candidato que deseja editar</h3>
<br />
<?php
	echo $form->create('Candidato', array('action' => 'editar', 'class' => 'formulario'));

	echo $form->input('numero_inscricao', array('label' => 'Número de inscrição'));
	echo '<br/>';

	echo $form->input('ano');
	echo '<br/>';

	echo $form->end('Editar');
?>
