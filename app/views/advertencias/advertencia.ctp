<h3>Informações sobre a advertência.</h3>
<br />
<strong> Nome: </strong><?php echo $estudante['Candidato']['nome']; ?>
<br /><br /><br />
<?php

	if($metodo_destino == 'editar_direto')
	{
		echo $form->create('Advertencia', array('url' => array('controller' => 'advertencias', 'action' =>$metodo_destino.'/'.$this->data['Advertencia']['advertencia_id']), 'class' => 'formulario'));
	}
	else
	{
		echo $form->create('Advertencia', array('url' => array('controller' => 'advertencias', 'action' =>$metodo_destino.'/'.$estudante_id), 'class' => 'formulario'));
	}

	echo $form->hidden('advertencia_id');
	echo $form->hidden('estudante_id');
	echo $form->input('data_advertencia', array('label' => 'Data da advertência'));
	echo '<br/>';
        echo '<label class="formulario">Modivo</label>';
	echo $form->textarea('motivo', array('rows' => '6', 'style' => 'width: 400px;'));
	echo '<br/>';

	echo $form->end('Concluir');
?>
