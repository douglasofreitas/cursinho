<?php
//a página de visualização  não será mais usada pois há o filtro.
//A página para selecionar o intervalo da pontuação economica e social foi trccada pelo modo manual.
if(false):
?>

    <h3>Escolha a turma cuja fase eliminatória deseja visualizar.</h3>
    <br/>
    <ul>
        <li><h3><?php echo $html->link('Turma de 1 ano', '/criterios_da_fase_eliminatorias/visualizar_status/' . $ano . '/1')?></h3></li>
        <li><h3><?php echo $html->link('Turma de 2 anos', '/criterios_da_fase_eliminatorias/visualizar_status/' . $ano . '/2')?></h3></li>
    </ul>
    <br/>

<?php
endif;
?>

<h3>Utilizar seleção manual, com a lista dos candidatos para marcar o status da fase.</h3>
<br/>
<ul>
	<li><h3><?php echo $html->link('Turma de 1 ano', '/criterios_da_fase_eliminatorias/listar_candidatos/' . $ano . '/1')?></h3></li>
	<li><h3><?php echo $html->link('Turma de 2 anos', '/criterios_da_fase_eliminatorias/listar_candidatos/' . $ano . '/2')?></h3></li>
</ul>
