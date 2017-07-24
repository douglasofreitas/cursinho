Ao salvar os critérios de seleção dos aprovados, o sistema irá fazer uma nova lista de aprovados, removendo todos os atuais aprovados.
<br/><br/><br/>
<?php
	echo $form->create('CriteriosDaFaseClassificatoria', array('action' => 'inserir_criterios/'.$ano_fase, 'class' => 'formulario'));

	echo '<h3>Turma de 1 ano</h3>';
	echo '<hr/>';
	echo '<br/>';
	echo '<br/>';
	echo $form->input('total_vagas_um_ano', array('label' => 'Total de vagas'));
	echo '<br/>';
	echo $form->input('total_vagas_indigenas_um_ano', array('label' => 'Vagas para Indígenas'));
	echo '<br/>';
	echo $form->input('total_vagas_afro_um_ano', array('label' => 'Vagas para Afro-descendentes'));
	echo '<br/>';
	echo $form->input('total_vagas_faber_um_ano', array('label' => 'Vagas para Faber'));
	echo '<br/>';
	echo '<br/>';
	echo '<br/>';

	echo '<h3>Turma de 2 anos</h3>';
	echo '<hr/>';
	echo '<br/>';
	echo '<br/>';
	echo $form->input('total_vagas_dois_anos', array('label' => 'Total de vagas'));
	echo '<br/>';
	echo $form->input('total_vagas_indigenas_dois_anos', array('label' => 'Vagas para Indígenas'));
	echo '<br/>';
	echo $form->input('total_vagas_afro_dois_anos', array('label' => 'Vagas para Afro-descendentes'));
	echo '<br/>';
	echo $form->input('total_vagas_faber_dois_anos', array('label' => 'Vagas para Faber'));
	echo '<br/>';	
	echo '<br/>';
	echo '<br/>';

	echo $form->end('Salvar critérios');
?>
