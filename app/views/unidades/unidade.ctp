<?php 
echo $form->create('Unidade', array( 'url' => array('controller' => 'unidades', 'action' =>$metodo_destino), 'class' => 'formulario'));
echo $form->hidden('id');

echo $form->input('nome', array('label' => 'Nome da unidade'));
echo '<br/>';

echo '<label class="formulario">Ativo</label>';
echo $form->checkbox('ativo', array());
echo '<br/>';

echo $form->end('Salvar');
?>