<h3>Indique o ano da prova e insira as questões.</h3>
<br />
<?php
	echo $form->create('QuestaoProva', array('action' => 'inserir'.'/'.$prova_id, 'class' => 'formulario'));

	echo '<label>Questão: </label> '.$numero_questao;
	echo '<br/>';

	echo $form->hidden('prova_id', array('value' => $prova_id));
	echo $form->hidden('numero_questao', array('value' => $numero_questao));
	echo $form->input('enunciado', array('label' => 'Enunciado', 'rows' => '4', 'type' => 'text'));
	echo '<br/>';
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

	echo $form->end('Cadastrar');
	echo '<br/><br/>';
	echo $form->create('Prova', array('action' => 'listar_todas'));
	echo $form->end('Cancelar');

?>
