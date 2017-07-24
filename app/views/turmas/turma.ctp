<?php 
	echo $form->create('Turma', array( 'url' => array('controller' => 'turmas', 'action' =>$metodo_destino), 'class' => 'formulario'));

        echo $form->hidden('id');

        echo $form->input('unidade_id', array('label' => 'Unidade', 'options' => $select_unidades));
        echo '<br/>';
        echo $form->input('ano_letivo', array('label' => 'Ano do processo seletivo'));
	echo '<br/>';
        echo $form->input('nome', array('label' => 'Nome da turma'));
	echo '<br/>';
	echo $form->input('vagas', array('label' => 'Número de vagas'));
	echo '<br/>';

	echo $form->end('Salvar');
?>