<h3>Informe o número de inscrição e o ano do candidato</h3>
<br />
<?php
	echo $form->create('CriteriosDaFaseEliminatoria', array('action' => 'adicionar_excecao_turma_2anos/'.$fase_eliminatoria_id , 'class' => 'formulario'));

	echo $form->input('numero_inscricao', array('label' => 'Número de inscrição'));
	echo '<br/>';

	echo $form->input('ano');
	echo '<br/>';

	echo $form->end('Adicionar');
?>
