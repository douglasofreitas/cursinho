<?php 
	echo $form->create('ProcessoSeletivo', array('action' => 'adicionar_processo_seletivo', 'class' => 'formulario'));

	echo $form->input('ano', array('label' => 'Ano do processo seletivo'));
	echo '<br/><br/>';

	echo $form->end('Inserir');
?>