
<h3>Vagas remanescentes do processo seletivo do ano <?php echo $ano;?></h3>
<br/><br/>
<h3>Número total de vagas da turma de 1 ano: <?php echo $numero_vagas_1_ano?> </h3>
<br/>
<h3>Número de vagas remanescentes da <span style="color:#FF0000;">turma de 1 ano</span>: <span style="color:#FF0000;font-size:30px;"><?php echo $vagas_restantes_1_ano?></span> </h3>
<br/>
<h3>Número total de vagas da turma de 2 anos: <?php echo $numero_vagas_2_anos?> </h3>
<br/>
<h3>Número de vagas remanescentes da <span style="color:#FF0000;">turma de 2 anos</span>: <span style="color:#FF0000;font-size:30px;"> <?php echo $vagas_restantes_2_anos?></span> </h3>
<br/>
<br/>
<h3>Deseja realmente fazer a próxima chamada?</h3>
<br/>
<h3><?php echo $html->link('Sim', '/criterios_da_fase_classificatorias/executar_proxima_chamada/' . $ano, array('style' => 'color: #0000CC;')); ?></h3>
<br/>
<h3><?php echo $html->link('Não', '/candidatos/', array('style' => 'color: #0000CC;')); ?></h3>
