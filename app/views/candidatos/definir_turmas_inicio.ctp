<h3>Informe o ano do processo seletivo.</h3>
<?php 
	echo $form->create('Candidato', array('action' => 'definir_turmas_inicio', 'class' => 'formulario'));
	echo '<br/>';

	echo $form->input('ano_fase', array('label' => 'Ano'));
	echo '<br/>';

	echo $form->end('Prosseguir');
?>
