<?php

echo $form->create('User', array('url' => array('controller' => 'users', 'action' =>'login'), 'class' => 'formulario') );
echo $form->input('User.username', array('label' => 'Usuário (CPF)', 'size' => '20'));
echo '<br/>';
echo $form->input('User.password', array('label' => 'Senha', 'size' => '15'));
echo '<br/>';
?>

<label></label>
<?php echo $html->link(__('Esqueceu a senha?', true), array('action' => 'esqueceu_senha')); ?>
<br/>

<?php
echo $form->end('Login');

?>