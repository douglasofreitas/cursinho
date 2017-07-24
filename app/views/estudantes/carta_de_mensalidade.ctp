<h3>Confirmar dados que serão inseridos na carta.</h3>
<br />

<?php

	echo $form->create('CartaMensalidade', array('url' => array('controller' => 'estudantes', 'action' =>'carta_de_mensalidade/'.$estudante_id), 'class' => 'formulario'));

	echo $form->input('nome', array('label' => 'Nome', 'value' => $nome));
	echo '<br/>';
	echo $form->input('ano', array('label' => 'Ano', 'value' => $ano));
	echo '<br/>';
	echo $form->input('turma', array('label' => 'Turma', 'value' => $codigo_turma));
	echo '<br/>';
	echo $form->input('numero_mensalidades', array('label' => 'Número de mensalidades', 'value' => count($estudante['Mensalidade'])));
	echo '<br/>';
	echo $form->input('valor_mensalidade', array('label' => 'Valor da mensalidade', 'value' => $estudante['Estudante']['valor_mensalidade']));
	echo '<br/>';
	echo $form->input('intervalo', array('label' => 'Intervalo dos meses de pagamento', 'value' => $intervalo));
	echo '<br/>';

	echo $form->end('Concluir');
?>
