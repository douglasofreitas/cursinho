<?php
echo $form->create('ProcessoSeletivo', array('action' => 'adicionar_processo_seletivo', 'class' => 'formulario'));
echo $form->hidden('processo_seletivo_id', array());

echo $form->input('ano', array('label' => 'Ano / Sigla'));
echo '<br/>';

echo $form->input('nome', array('label' => 'Nome'));
echo '<br/>';

echo $form->end('Avançar');
?>