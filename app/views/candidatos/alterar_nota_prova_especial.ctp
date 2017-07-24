<h3>Para a prova especial, digite a nota do candidato.</h3> 
<br /> 

<?php 

  echo $form->create('Candidato', array('action' => 'alterar_nota_prova_especial/'.$numero_inscricao.'/'.$ano, 'class' => 'formulario')); 

  echo $form->input('nota_prova', array('label' => 'Nota da Prova', 'size' => '10')); 
  echo '<br/>'; 

  echo $form->end('Alterar'); 
?> 