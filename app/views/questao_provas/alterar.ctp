
<br />
<?php
	echo $form->create('QuestaoProva', array('action' => 'alterar_final'.'/'.$prova_ano.'/'.$numero_questao, 'class' => 'formulario'));

	echo '<label>Questão: </label> '.$numero_questao;
	echo '<br/>';

	echo $form->hidden('prova_id', array('value' => $prova_id));
	echo $form->hidden('numero_questao', array('value' => $numero_questao));
	echo $form->input('enunciado', array('label' => 'Enunciado', 'rows' => '4', 'type' => 'text'));
	echo $form->input('alternativa_correta', array('label' => 'Alternativa Correta', 'options' => array(
	    'A'=>'A',
	    'B'=>'B',
	    'C'=>'C',
	    'D'=>'D',
	    'E'=>'E'
	)));

	echo '<br/>';
	echo $form->input('habilidade_avaliada_id', array('label' => 'Habilidade Avaliada', 'options' => $habilidades));
	echo '<br/>';
	echo $form->input('anulada', array('label' => 'Anulada', 'options' => array(
	    0=>'NAO',
	    1=>'SIM'
	)));
	echo '<br/>';
	echo $form->end('Alterar');

	echo $form->create('Prova', array('action' => 'listar_todas'));
	echo $form->end('Cancelar');

?>