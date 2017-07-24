<h3>Informe o processo seletivo para selecionar os estudantes e o mês de referência para gerar as mensalidades.</h3>
<br />
<?php
	echo $form->create('Estudante', array('action' => 'gerar_mensalidade_grupo_index', 'class' => 'formulario'));

	echo $form->input('ano_letivo', array('label' => 'Processo Seletivo'));
	echo '<br/>';
	
	echo $form->input('mes', array('label' => 'Mês de referência', 'value' => date('m')));
	echo '<br/>';

    echo $form->label('rematriculado', 'Apenas rematriculados');
    echo $form->checkbox('rematriculado');
    echo '<br/>';

	echo $form->end('Prosseguir');
?>
