<h3>Insira o número de inscrição e no do estudante.</h3>
<br />
<?php
	echo $form->create('Candidato', array('url' => array('controller' => 'advertencias', 'action' =>$metodo_destino), 'class' => 'formulario'));

	echo $form->input('numero_inscricao', array('label' => 'Número de inscrição', 'size' => '10'));
	echo '<br/>';
	echo $form->input('ano', array('label' => 'Ano', 'size' => '10'));
	echo '<br/>';

	echo $form->end('Avançar');
?>
