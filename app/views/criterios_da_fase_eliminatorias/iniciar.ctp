<h3>Informe o ano do processo seletivo.</h3>
<?php 
	echo $form->create('CriteriosDaFaseEliminatoria', array('action' => 'iniciar', 'class' => 'formulario'));
	echo '<br/>';

	echo $form->input('ano_fase', array('label' => 'Ano'));
	echo '<br/>';

	echo $form->end('Prosseguir');
?>
