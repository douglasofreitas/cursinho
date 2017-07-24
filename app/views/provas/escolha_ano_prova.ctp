<h3>Escolha o ano da Prova:</h3>
<br/>
<?php
	echo $form->create('Prova', array('action' => $tipo_relatorio, 'class' => 'formulario'));

	echo $form->input('ano', array('label' => 'Ano'));
	echo '<br/>';

	echo $form->end('Gerar Relatório');
?>
