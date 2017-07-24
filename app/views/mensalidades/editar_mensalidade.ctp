
<?php
echo $form->create('Mensalidade', array('action' => 'editar_mensalidade/'.$mensalidade['Mensalidade']['recibo_mensalidade_id'], 'class' => 'formulario'));
echo $form->hidden('recibo_mensalidade_id', array('value' =>$mensalidade['Mensalidade']['recibo_mensalidade_id']));
echo $form->hidden('estudante_id', array('value' =>$mensalidade['Mensalidade']['estudante_id']));
echo $form->input('valor', array('label' => 'Valor da mensalidade', 'size' => '30', 'value' =>$mensalidade['Mensalidade']['valor']));
echo '(formato ex: 25.10)<br/>';
echo $form->end('Alterar');
?>