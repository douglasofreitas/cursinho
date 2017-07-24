<h3>Insira a data referênte a frequência a ser registrada.</h3>
<br />
<?php
	echo $form->create('Frequencia', array('url' => array('controller' => 'frequencias', 'action' =>$metodo_destino), 'class' => 'formulario'));

	echo $form->input('data_frequencia', array('label' => 'Data'));
	echo '<br/>';
	echo $form->label('unidade', 'Unidade');
	echo $form->select('unidade_id', $unidades, null, null, false);
	echo '<br/>';
	echo '<br/>';

	echo $form->end('Avançar');
?>
