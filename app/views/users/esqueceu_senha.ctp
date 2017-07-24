<p>Informe seu CPF e e-mail para que possa criar uma nova senha.</p>

<?php
echo $form->create('User', array('url' => array('controller' => 'users', 'action' =>'esqueceu_senha'), 'class' => 'formulario') );
echo $form->input('User.cpf', array('label' => 'CPF', 'size' => '20'));
echo '<br/>';
echo $form->input('User.email', array('label' => 'E-mail', 'size' => '50'));
echo '<br/>';

echo $form->end('Trocar senha');
?>