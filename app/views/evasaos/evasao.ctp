<h3>Informações sobre a evasão.</h3>
<br />
<strong> Nome: </strong><?php echo $estudante['Candidato']['nome']; ?>
<br /><br /><br />
<?php
	echo $form->create('Evasao', array('url' => array('controller' => 'evasaos', 'action' =>$metodo_destino.'/'.$estudante_id), 'class' => 'formulario'));

	echo $form->hidden('evasao_id');
	echo $form->input('data', array('label' => 'Data da evasão'));
	echo '<br/>';
	echo '<label class="formulario">Modivo</label>';
	echo $form->textarea('motivo', array('rows' => '6', 'style' => 'width: 400px;'));
	echo '<br/>';

	echo $form->end('Concluir');
?>
