<h3>Insira a o período que deseja visualizar as frequências.</h3>
<br />
<?php
	echo $form->create('Frequencia', array('url' => array('controller' => 'frequencias', 'action' =>$metodo_destino), 'class' => 'formulario'));

	echo '<label class="formulario">Data inicial:</label>';
	echo $form->day('inicio', date('d'));
	echo $form->month('inicio', date('m'));
	echo $form->year('inicio',date('Y')-20,date('Y'), date('Y'));
	echo '<br/>';

	echo '<label class="formulario">Data final:</label>';
	echo $form->day('fim', date('d'));
	echo $form->month('fim', date('m'));
	echo $form->year('fim',date('Y')-20,date('Y'), date('Y'));
	echo '<br/>';

	echo $form->label('unidade', 'Unidade');
	echo $form->select('unidade_id', $unidades, null, null, false);
	echo '<br/>';

	echo '<br/><br/>';
	echo $form->end('Avançar');
?>
