<h3>Candidatos aprovados</h3>
<hr/>
<h3><?php echo $html->link('Visualizar os candidatos que passaram nesta fase', 
	'visualizar_resultados_turma_1ano/' . $criterios['CriteriosDaFaseEliminatoria']['criterios_da_fase_eliminatoria_id'],
	array('style' => 'color: #0000CC')) ?></h3>
<br/><br/>
<h3>Adicionar exceção</h3>
<hr/>
<h3><?php echo $html->link('Adicionar candidato que não satisfaz aos critérios',
	'adicionar_excecao_turma_1ano/' . $criterios['CriteriosDaFaseEliminatoria']['criterios_da_fase_eliminatoria_id'],
	array('style' => 'color: #0000CC')) ?></h3>
<br/><br/>
<h3>Critérios utilizados</h3>
<hr/><br/>
<label class="formulario">Pontuação social mínima</label>
<?php echo $criterios['CriteriosDaFaseEliminatoria']['pontuacao_social_minima_um_ano'] ?>
<br/>
<label class="formulario">Pontuação social máxima</label>
<?php echo $criterios['CriteriosDaFaseEliminatoria']['pontuacao_social_maxima_um_ano'] ?>
<br/><br/>
<label class="formulario">Pontuação econômica mínima</label>
<?php echo $criterios['CriteriosDaFaseEliminatoria']['pontuacao_economica_minima_um_ano'] ?>
<br/>
<label class="formulario">Pontuação econômica máxima</label>
<?php echo $criterios['CriteriosDaFaseEliminatoria']['pontuacao_economica_maxima_um_ano'] ?>
<br/><br/>
<?php 
	echo $form->create('CriteriosDaFaseEliminatoria', array('action' => 'definir_criterios_turma_1ano/' 
		. $criterios['CriteriosDaFaseEliminatoria']['criterios_da_fase_eliminatoria_id'], 'class' => 'formulario'));

	echo $form->end('Alterar os critérios');

?>
	<br/><br/>
	<h3 style="color: red">ATENÇÃO! A fase eliminatória da turma de 1 ano foi realizada. 
		Se você alterar os critérios utilizados todos os candidatos que haviam passado serão marcados como candidatos que não passaram.</h3>
