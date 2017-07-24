<h3>Deseja realmente rematricular este estudante?</h3>
<br />
<?php
echo $form->create('Estudante', array('url' => array('controller' => 'estudantes', 
        'action' =>'rematricular/'.$candidato_id), 'class' => 'formulario'));
echo $form->hidden('candidato_id', array('value' => $candidato_id));
echo $form->end('Sim');
?>
<?php
echo $form->create('Estudante', array('url' => array('controller' => 'estudantes', 
        'action' =>'visualizar_ficha/'.$candidato_id), 'class' => 'formulario'));
echo $form->end('Não');
?>