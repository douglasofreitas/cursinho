<script type="text/javascript">
	$(function() {

	});
</script>
<span style="float:right">
<?php

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
<h3>Candidato: <?php echo $nome ?> </h3><br/>
<h3>Número de inscrição: <?php echo $numero_inscricao ?> </h3><br/>
<h3>Ano: <?php echo $ano ?> </h3><br/>
<hr/>
<br/>
<h3>Página 7 de 7</h3>
<br/>
<div id="questionario">
	<form method="post" id="formulario" action=<?php echo '"' . $html->url('/questao_questionarios/salvar_respostas/') 
		. $numero_inscricao . '/' . $ano . '/7' . '"'; ?>>

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

	<input type="submit" name="Anterior" value="Página Anterior" />
	<input type="submit" name="Cancelar" value="Salvar e Sair" />
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
	<input type="submit" name="Cancelar" value="Salvar e Sair" />

	</form>

</div>
