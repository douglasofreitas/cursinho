<h3>Insira o ano para a geração do relatório.</h3>
<br />
<?php
	echo $form->create('Frequencia', array('url' => array('controller' => 'frequencias', 'action' =>$metodo_destino), 'class' => 'formulario'));

	echo '<label class="formulario">Ano:</label>';
	echo $form->year('inicio',date('Y')-20,date('Y'), date('Y'));
	echo '<br/>';
	echo $form->label('unidade', 'Unidade');
	echo $form->select('unidade_id', $unidades, null, null, false);
	echo '<br/>';

	echo '<br/><br/>';
	echo $form->end('Avançar');
?>
