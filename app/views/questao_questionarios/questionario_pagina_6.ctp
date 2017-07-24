<script type="text/javascript">
	$(function() {


	});
</script>
<span style="float:right">
<?php
if(empty($_SESSION['Inscricao']['candidato_id']))
if($_SESSION['Auth']['User']['group_id'] == 3){
    $link_questionario = $html->url('/questao_questionarios/preencher_candidato/') . $candidato_id;
}else{
    $link_questionario = $html->url('/questao_questionarios/preencher/') . $numero_inscricao . '/' . $ano;


    echo $html->link(
        $html->image('icon/pdf.gif', array("alt" => "PDF completo", 'class' => 'tipHoverBottom', 'title' => 'PDF completo')),
        '/questao_questionarios/pdf_questionario/' .$numero_inscricao . '/' . $ano . '',
        array('escape' => false));
}

?>
</span>

<?php if(! empty($_SESSION['Inscricao']['candidato_id'])): ?>
    <p style="font-size: 20px;color: red;font-weight: bold;">PREENCHA TODO O QUESTINÁRIO PARA FINALIZAR SEU CADASTRO E PAGAR A INSCRIÇÃO</p>
<?php endif; ?>

<h3>Candidato: <?php echo $nome ?> </h3><br/>
<h3>Número de inscrição: <?php echo $numero_inscricao ?> </h3><br/>
<h3>Ano: <?php echo $ano ?> </h3><br/>
<hr/>
<br/>

<?php if(empty($_SESSION['Inscricao']['candidato_id'])): ?>

    <?php if($_SESSION['Auth']['User']['group_id'] == 3): ?>
        <h3>Página 2 de 2</h3>
    <?php else: ?>
        <h3>Página 6 de 7</h3>
    <?php endif; ?>

<?php else: ?>
    <h3>Página 2 de 2</h3>
<?php endif; ?>

<br/>
<div id="questionario">
	<form method="post" id="formulario" action=<?php echo '"' . $html->url('/questao_questionarios/salvar_respostas/') 
		. $numero_inscricao . '/' . $ano . '/6' . '"'; ?>>

        <?php if(empty($_SESSION['Inscricao']['candidato_id'])): ?>

            <h4 <?php if($_SESSION['Auth']['User']['group_id'] == 3) echo 'style="display:none"' ?> >Escolher página:
                <a style="" href=<?php echo '"' . $link_questionario . '/1' . '"'; ?>>1</a>
                <a> | </a>
                <a style="" href=<?php echo '"' . $link_questionario . '/2' . '"'; ?>>2</a>
                <a> | </a>
                <a style="" href=<?php echo '"' . $link_questionario . '/3' . '"'; ?>>3</a>
                <a> | </a>
                <a style="" href=<?php echo '"' . $link_questionario . '/4' . '"'; ?>>4</a>
                <a> | </a>
                <a style="" href=<?php echo '"' . $link_questionario . '/5' . '"'; ?>>5</a>
                <a> | </a>
                <a style="" href=<?php echo '"' . $link_questionario . '/6' . '"'; ?>>6</a>
                <a> | </a>
                <?php if($_SESSION['Auth']['User']['group_id'] != 3): ?>
                    <a style="" href=<?php echo '"' . $link_questionario . '/7' . '"'; ?>>7</a>
                <?php endif; ?>
            </h4>

        <?php endif; ?>

        <input type="submit" name="Anterior" value="Página Anterior" />
        <?php if($_SESSION['Auth']['User']['group_id'] != 3): ?>
            <?php if(empty($_SESSION['Inscricao']['candidato_id'])): ?>
                <input type="submit" name="Proximo" value="Próxima Página" />
            <?php endif; ?>
        <?php endif; ?>
        <?php if(empty($_SESSION['Inscricao']['candidato_id']) | $_SESSION['Auth']['User']['group_id'] != 3): ?>
            <input type="submit" name="Cancelar" value="Salvar e Sair" />
        <?php endif; ?>

	<p></p>

	<?php

		foreach ($questoes as $questao)
		{				
			//$questionario->preencher_questao($questao['Questao'], $questao['Resposta']);

			//echo html_entity_decode($questao['Questao']);

			$questaoHtml = $questionario->montar_questao($questao['Questao']);

			$questionario->preencher_questao($questaoHtml, $questao['Resposta']);
			echo $questaoHtml;
		}

	?>

	<input type="submit" name="Anterior" value="Página Anterior" />
    <?php if(empty($_SESSION['Inscricao']['candidato_id'])): ?>
        <input type="submit" name="Proximo" value="Próxima Página" />
    <?php endif; ?>
	<input type="submit" name="Cancelar" value="Salvar e Sair" />

	</form>

</div>
