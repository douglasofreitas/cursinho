<h3>Informações sobre a Nota.</h3>
<br />
Aqui pode ser inserido as notas de provas ou de atividade realizadas
<br />
<br />
<?php

	if($metodo_destino == 'editar_direto')
	{
		echo $form->create('Boletim', array('url' => array('controller' => 'boletims', 'action' =>$metodo_destino.'/'.$this->data['Boletim']['boletim_id']), 'class' => 'formulario'));
	}
	else
	{
		echo $form->create('Boletim', array('url' => array('controller' => 'boletims', 'action' =>$metodo_destino.'/'.$estudante_id), 'class' => 'formulario'));
	}

	echo $form->hidden('boletim_id');
	echo $form->hidden('estudante_id');
	echo $form->input('nome_atividade', array('label' => 'Nome da atividade'));
	echo '<br/>';
	echo $form->input('descricao', array('label' => 'Descrição'));
	echo '<br/>';
	echo $form->input('data_atividade', array('label' => 'Data da atividade'));
	echo '<br/>';
	echo $form->input('nota', array('label' => 'Nota nesta atividade'));
	echo '<br/>';

	echo $form->end('Concluir');
?>
