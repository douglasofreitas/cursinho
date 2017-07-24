<h3>Indique o ano da prova e insira as questões.</h3>
<br />
<?php
	echo $form->create('Prova', array('action' => 'inserir', 'class' => 'formulario'));

	echo $form->input('ano', array('label' => 'Ano da prova', 'size' => '10'));
	echo '<br/>';

	echo $form->end('Cadastrar');
?>
