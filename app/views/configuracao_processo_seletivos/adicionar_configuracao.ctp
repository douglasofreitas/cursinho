<br/>
<h3>Informe o valor do salário mínimo atual</h3>
<br/>
<h3 style="color:red">ATENÇÃO! Uma vez definido esse valor ele não poderá mais ser alterado.</h3>
<br/>
<?php 
	echo $form->create('ConfiguracaoProcessoSeletivo', array('action' => 'adicionar_configuracao', 'class' => 'formulario'));

	echo $form->input('valor_salario_minimo', array('label' => 'Valor do salário mínimo atual'));
	echo '<br/><br/>';

	echo $form->hidden('processo_seletivo_id', array('value' => $processo_seletivo_id));

	echo $form->end('Inserir');
?>