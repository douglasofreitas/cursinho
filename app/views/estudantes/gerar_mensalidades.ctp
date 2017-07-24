<?php 
	echo $form->create('Estudante', array('url' => array('controller' => 'estudantes', 'action' => 'gerar_mensalidades/'.$estudante_id), 'class' => 'formulario'));
	echo $form->hidden('estudante_id');




    //echo $form->input('nome', array('label' => 'Estudante', 'value' => $estudante['Candidato']['nome'], 'disabled'));
echo $form->label(utf8_encode('Estudante'));
echo $estudante['Candidato']['nome'];
    echo '<br/>';


	echo $form->label(utf8_encode('Dia do pagamento'));
	echo $form->select('dia', $dias, 1, null, false);
	echo '<br/>';
	echo $form->input('mes_inicio', array('label' => 'Mês início (numero)', 'value' => date('m'), 'style' => 'width:50px' ));
	echo '<br/>';
    echo $form->input('mes_fim', array('label' => 'Mês final (numero)', 'value' => date('m'), 'style' => 'width:50px' ));
    echo '<br/>';
	echo $form->input('valor', array('label' => 'Valor das mensalidades'));
	echo '<br/>';

	echo $form->end('Gerar');
?>
