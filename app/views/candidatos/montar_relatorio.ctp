<h3>Selecione abaixo quais campos você deseja que sejam incluídos.</h3>
<br/><br/>
<?php 
	echo $form->create('Candidato', array('action' => 'montar_relatorio/montar', 'class' => 'formulario'));

?>

	<h3>Campos da ficha de inscrição</h3>
	<hr/>

<?php

	echo $form->label('numero_inscricao', 'Número de inscrição');
	echo $form->checkbox('numero_inscricao', array('value' => 'Numero de inscricao'));
	//echo '<br/>';

	echo $form->label('ano', 'Ano');
	echo $form->checkbox('ano', array('value' => 'Ano'));
	echo '<br/>';

	echo $form->label('nome', 'Nome');
	echo $form->checkbox('nome', array('value' => 'Nome'));
	//echo '<br/>';

	echo $form->label('email', 'E-mail');
	echo $form->checkbox('email', array('value' => 'Email'));
	echo '<br/>';

    echo $form->label('rg', 'RG');
    echo $form->checkbox('rg', array('value' => 'RG'));

    echo $form->label('orgao_emissor_rg', 'Orgão emissor do RG');
	echo $form->checkbox('orgao_emissor_rg', array('value' => 'Orgao emissor RG'));
	echo '<br/>';

	echo $form->label('cpf', 'CPF');
	echo $form->checkbox('cpf', array('value' => 'CPF'));
	echo '<br/>';

	echo $form->label('endereco', 'Endereço');
	echo $form->checkbox('endereco', array('value' => 'Endereco'));
	//echo '<br/>';

	echo $form->label('bairro', 'Bairro');
	echo $form->checkbox('bairro', array('value' => 'Bairro'));
	echo '<br/>';

	echo $form->label('estado', 'Estado');
	echo $form->checkbox('estado', array('value' => 'Estado'));
	//echo '<br/>';

	echo $form->label('cidade', 'Cidade');
	echo $form->checkbox('cidade', array('value' => 'Cidade'));
	echo '<br/>';

	echo $form->label('cep', 'CEP');
	echo $form->checkbox('cep', array('value' => 'CEP'));
	//echo '<br/>';

	echo $form->label('telefone_residencial', 'Telefone residencial');
	echo $form->checkbox('telefone_residencial', array('value' => 'Telefone residencial'));
	echo '<br/>';

	echo $form->label('telefone_outro', 'Telefone outro');
	echo $form->checkbox('telefone_outro', array('value' => 'Telefone outro'));
	//echo '<br/>';
	echo $form->label('ano_conclusao_ensino_medio', 'Ano de conclusão do ensino médio');
	echo $form->checkbox('ano_conclusao_ensino_medio', array('value' => 'Ano conclusao do ensino medio'));
	echo '<br/>';

	//echo $form->label('taxa_inscricao', 'Taxa inscrição');
	//echo $form->checkbox('taxa_inscricao', array('value' => 'Taxa inscricao'));
	//echo '<br/>';

        echo $form->label('unidade_id', 'Unidade');
	echo $form->checkbox('unidade_id', array('value' => 'Unidade'));

	echo $form->label('data_preenchimento_questionario', 'Data para preenchimento do questionário');
	echo $form->checkbox('data_preenchimento_questionario', array('value' => 'Data preechimento do questionario'));
	echo '<br/>';

        echo $form->label('turma', 'Turma');
	echo $form->checkbox('turma', array('value' => 'Turma'));

echo $form->label('taxa_inscricao', 'Taxa de inscrição');
echo $form->checkbox('taxa_inscricao', array('value' => 'Taxa de inscricao'));
	echo '<br/>';
?>

	<br/><br/>
	<h3>Campos do questionário</h3>
	<hr/>
<?php

	echo $form->label('sexo', 'Sexo');
	echo $form->checkbox('sexo', array('value' => 'Sexo'));
	//echo '<br/>';

	echo $form->label('cor', 'Cor');
	echo $form->checkbox('cor', array('value' => 'Cor'));
	echo '<br/>';

	echo $form->label('etnia', 'Etnia');
	echo $form->checkbox('etnia', array('value' => 'Etnia'));
	//echo '<br/>';

	echo $form->label('idade', 'Idade');
	echo $form->checkbox('idade', array('value' => 'Idade'));
	echo '<br/>';

	echo $form->label('estado_civil', 'Estado Civil');
	echo $form->checkbox('estado_civil', array('value' => 'Estado civil'));
	//echo '<br/>';

	echo $form->label('filhos', 'Filhos');
	echo $form->checkbox('filhos', array('value' => 'Filhos'));
	echo '<br/>';

	echo $form->label('trabalho', 'Trabalho');
	echo $form->checkbox('trabalho', array('value' => 'Trabalho'));
	//echo '<br/>';

	echo $form->label('renda_bruta', 'Renda Bruta');
	echo $form->checkbox('renda_bruta', array('value' => 'Renda bruta'));
	echo '<br/>';

    echo $form->label('conclusao_medio', 'Idade de conclusão do E.M.');
    echo $form->checkbox('conclusao_medio', array('value' => 'Idade de conclusão E.M.'));

echo $form->label('escolaridade_pai', 'Escolaridade do pai');
echo $form->checkbox('escolaridade_pai', array('value' => 'Escolaridade do pai'));
echo '<br/>';

echo $form->label('tipo_moradia', 'Tipo de moradia');
echo $form->checkbox('tipo_moradia', array('value' => 'Tipo de moradia'));

echo $form->label('escolaridade_mae', 'Escolaridade da mãe');
echo $form->checkbox('escolaridade_mae', array('value' => 'Escolaridade da mae'));
echo '<br/>';

echo $form->label('num_comodos', 'Número de cômodos');
echo $form->checkbox('num_comodos', array('value' => 'Numero de comodos'));

echo $form->label('num_banheiros', 'Número de banheiros');
echo $form->checkbox('num_banheiros', array('value' => 'Numero de banheiros'));
echo '<br/>';

echo $form->label('tem_internet', 'Tem internet');
echo $form->checkbox('tem_internet', array('value' => 'Tem internet'));

echo $form->label('tem_tv_cabo', 'Tem tv a cabo');
echo $form->checkbox('tem_tv_cabo', array('value' => 'Tem tv a cabo'));
echo '<br/>';

echo $form->label('tem_telefone', 'Tem telefone');
echo $form->checkbox('tem_telefone', array('value' => 'Tem telefone'));

echo $form->label('conheceu_cursinho', 'Como conheceu o cursinho');
echo $form->checkbox('conheceu_cursinho', array('value' => 'Como conheceu o cursinho'));
echo '<br/>';

echo $form->label('portador_necessidade', 'Portador de necessiadade esp.');
echo $form->checkbox('portador_necessidade', array('value' => 'Portador de necessidade esp.'));

echo $form->label('orientacao_sexual', 'Orientação sexual');
echo $form->checkbox('orientacao_sexual', array('value' => 'Orientacao sexual'));
echo '<br/>';



?>
	<br/><br/>
	<h3>Pontuação e nota</h3>
	<hr/>
<?php

	echo $form->label('pontuacao_social', 'Pontuação Social');
	echo $form->checkbox('pontuacao_social', array('value' => 'Pontuacao Social'));
	//echo '<br/>';

	echo $form->label('pontuacao_economica', 'Pontuação Econômica');
	echo $form->checkbox('pontuacao_economica', array('value' => 'Pontuacao Economica'));
	echo '<br/>';

	echo $form->label('nota_prova', 'Nota de prova');
	echo $form->checkbox('nota_prova', array('value' => 'Nota de prova'));	
	echo '<br/><br/>';

	echo $form->end('Montar Relatório');
?>
