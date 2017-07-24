<h3>Informe o ano do processo seletivo</h3>
<br />
<?php
	echo $form->create('Candidato', array('action' => 'matricular_selecionar_ano', 'class' => 'formulario'));

	echo $form->input('ano');
	echo '<br/>';

	echo $form->end('Prosseguir');
?>
