<h3>Insira o número de inscrição e no do estudante.</h3>
<br />
<?php
	echo $form->create('Candidato', array('url' => array('controller' => 'boletims', 'action' =>$metodo_destino), 'class' => 'formulario'));

	echo $form->input('numero_inscricao', array('label' => 'Número de inscrição', 'size' => '10'));
	echo '<br/>';
	echo $form->input('ano', array('label' => 'Ano', 'size' => '10'));
	echo '<br/><br/>';

        echo 'OU <br/>';

        echo $form->input('estudante_id', array('label' => 'Código estudante', 'size' => '13'));
	echo '<br/>';

	echo $form->end('Avançar');
?>
