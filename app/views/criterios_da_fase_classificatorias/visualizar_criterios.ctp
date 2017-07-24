
<h3>Ano do processo seletivo: <?php echo $ano_fase;?></h3>
<br/>
<br/>
<h3><?php echo $html->link('Alterar Critérios', '/criterios_da_fase_classificatorias/alterar_criterios/'.$ano_fase, array('style' => 'color:#0000CC')); ?></h3>
<br/>
<br/>
<h3>Turma de 1 ano</h3>
<hr/>
<br/>
<label class="formulario">Total de Vagas:</label>
<?php echo $dados['CriteriosDaFaseClassificatoria']['total_vagas_um_ano'] ?>
<br/>
<label class="formulario">Vagas para Indígenas:</label>
<?php echo $dados['CriteriosDaFaseClassificatoria']['total_vagas_indigenas_um_ano'] ?>
<br/>
<label class="formulario">Vagas para Afro-descendentes:</label>
<?php echo $dados['CriteriosDaFaseClassificatoria']['total_vagas_afro_um_ano'] ?>
<br/>
<label class="formulario">Vagas para Faber:</label>
<?php echo $dados['CriteriosDaFaseClassificatoria']['total_vagas_faber_um_ano'] ?>
<br/>
<br/>
<h3>Turma de 2 anos</h3>
<hr/>
<br/>
<label class="formulario">Total de Vagas:</label>
<?php echo $dados['CriteriosDaFaseClassificatoria']['total_vagas_dois_anos'] ?>
<br/>
<label class="formulario">Vagas para Indígenas:</label>
<?php echo $dados['CriteriosDaFaseClassificatoria']['total_vagas_indigenas_dois_anos'] ?>
<br/>
<label class="formulario">Vagas para Afro-descendentes:</label>
<?php echo $dados['CriteriosDaFaseClassificatoria']['total_vagas_afro_dois_anos'] ?>
<br/>
<label class="formulario">Vagas para Faber:</label>
<?php echo $dados['CriteriosDaFaseClassificatoria']['total_vagas_faber_dois_anos'] ?>
<br/>