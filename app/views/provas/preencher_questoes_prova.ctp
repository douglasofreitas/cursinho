<h3>Informe o ano do processo seletivo para inserir as notas de todos os candidatos</h3>
<?php
echo $form->create('Prova', array('action' => 'preencher_lista', 'class' => 'formulario'));
echo '<br/>';

echo $form->input('ano_fase', array('label' => 'Ano'));
echo '<br/>';

$options=array('1'=>'Turma de 1 ano','2'=>'Turma de 2 anos');
$attributes=array('legend'=>false, 'value' => '1', 'separator' => '<br/>', 'style' => 'float:initial');
echo $form->radio('turma',$options,$attributes);
echo '<br/>';

echo $form->end('Avançar');
?>

<br/> <br/><br/> <br/>

<h3>Para o caso de preencher o gabarito ou nota de um candidato específico, informe o número de inscrição e o ano abaixo</h3>
<br/>
<?php
	echo $form->create('Prova', array('action' => 'preencher', 'class' => 'formulario'));

	echo $form->input('candidato_numero_inscricao', array('label' => 'Número de inscrição'));
	echo '<br/>';

	echo $form->input('candidato_ano', array('label' => 'Ano'));
	echo '<br/>';

	echo $form->end('Avançar');
?>
