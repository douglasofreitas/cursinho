<?php 
	echo $form->create('Fatura', array('url' => array('controller' => 'estudantes', 'action' => 'registrar_pagamento_mensalidade/'.$fatura['Fatura']['id']), 'class' => 'formulario'));

	echo $form->input('data_pagamento', array('label' => 'Data do pagamento', 'value' => date('d/m/Y')));
	echo '<br/>';

	echo $form->label('isento', 'Estudante isento');
	echo $form->checkbox('isento');
	echo '<br/>';

	echo $form->end('Registrar');
?>
