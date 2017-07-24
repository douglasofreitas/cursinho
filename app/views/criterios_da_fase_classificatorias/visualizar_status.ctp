
<h3>Processo seletivo: <?php echo $ano_fase;?></h3>
<br/><br/>
<?php if(false):?>
    <?php if($has_fase_classificatoria):?>
        <h3>Já foi feita a fase classificatória deste processo seletivo. O que deseja fazer?</h3>
        <br/>
        <h3><?php echo $html->link('Visualizar os critérios da fase classificatoria', "/criterios_da_fase_classificatorias/visualizar_criterios/" . $ano_fase, array('style' => 'color: #0000CC;')); ?></h3>
        <br />
        <h3><?php echo $html->link('Visualizar a lista dos aprovados', "/criterios_da_fase_classificatorias/visualizar_aprovados/" . $ano_fase, array('style' => 'color: #0000CC;')); ?></h3>

    <?php else:?>
        <h3>Ainda não foi feita a fase classificatória. Deseja preencher os critérios e selecionar os aprovados?<br /></h3>
        <br />
        <h3><?php echo $html->link('Sim', '/criterios_da_fase_classificatorias/inserir_criterios/'.$ano_fase, array('style' => 'color: #0000CC;')); ?></h3><br />
        <h3><?php echo $html->link('Não', '/candidatos/index', array('style' => 'color: #0000CC;')); ?></h3>
    <?php endif;?>
    <br/><br/>
<?php endif;?>

<h3>Utilizar seleção manual, com a lista dos candidatos e selecionar os que passaram no processo.</h3>
<br/>
<ul>
	<li><h3><?php echo $html->link('Turma de 1 ano', '/criterios_da_fase_classificatorias/listar_candidatos/' . $ano_fase . '/1')?></h3></li>
	<li><h3><?php echo $html->link('Turma de 2 anos', '/criterios_da_fase_classificatorias/listar_candidatos/' . $ano_fase . '/2')?></h3></li>
</ul>
