
<h3>Digite o ano do processo seletivo.</h3>
<br/>
<?php
	echo $form->create('CriteriosDaFaseClassificatoria', array('action' => $metodo_destino, 'class' => 'formulario'));

	echo $form->input('ano_fase', array('label' => 'Ano'));
	echo '<br/>';

	echo $form->end('Avançar');
?>
