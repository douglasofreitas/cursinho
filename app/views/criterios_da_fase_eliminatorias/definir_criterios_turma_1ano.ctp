<h3>Informe quais são os critérios de seleção de candidatos, definindo um valor mínimo e máximo para as pontuações social e econômica.</h3>
<br/>
<h3>Apenas os candidatos que possuirem pontuações dentro desse intervalo serão selecionados para a realização da prova.</h3>
<br/><br/>
<h3>Critérios de seleção para a turma de 1 ano</h3>
<hr/><br/>
<?php 
	echo $form->create('CriteriosDaFaseEliminatoria', array('action' => 'definir_criterios_turma_1ano/' . $fase_eliminatoria_id, 'class' => 'formulario'));

	echo $form->input('pontuacao_social_minima_um_ano', array('label' => 'Pontuação Social Mínima'));
	echo '<br/>';
	echo $form->input('pontuacao_social_maxima_um_ano', array('label' => 'Pontuação Social Máxima'));

	echo '<br/><br/>';

	echo $form->input('pontuacao_economica_minima_um_ano', array('label' => 'Pontuação Econômica Mínima'));
	echo '<br/>';
	echo $form->input('pontuacao_economica_maxima_um_ano', array('label' => 'Pontuação Econômica Máxima'));
	echo '<br/>';

	echo $form->end('Prosseguir');
?>