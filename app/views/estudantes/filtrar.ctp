<script type="text/javascript">
	$(function() {
		var flag0 = 1;
		var flag1 = 0;
		var flag2 = 0;
		var flag3 = 0;
		var flag4 = 0;
		var flag5 = 0;
		var flag6 = 0;
		var flag7 = 0;
		var flag8 = 0;
		var flag9 = 0;
		var flag10 = 0;
		var flag11 = 0;
		var flag12 = 0;
		var flag13 = 0;
		var flag14 = 0;
		var flag15 = 0;
		$('#EstudanteEstado').change(function()
				{
				    // selected value   
				    var selected = $(this).val();   

				    // set loading image   
				    //ajax_loading_image('.ajax_loading_image');

				    if (selected != '')
				    {
				    	$('#cidades').html('<label><img src=' + <?php echo "'" . $html->url('/img/ajax_loading.gif') . "'" ?> + '/></label>');

					    // ajax   
					    $.ajax({   
					        type: "POST",   
					        url: <?php echo "'" . $html->url('/cidades/ajax_obtem_cidades') . "'" ?>, 
					        data: "ajax=true&estado_id="+selected,   
					        success: function(msg){   
					            //console.log(msg);   
					            $('#cidades').html(msg);
					            // remove loading image   
					            //ajax_remove_loading_image('.ajax_loading_image');
					        }
					    });
				    }
				});
		$('a').mouseover(function() {
			$(this).addClass("selecionado");
		});
		$('a').mouseout(function() {
			$(this).removeClass("selecionado");
		});
		$('a#a_ficha_inscricao').click(function() {
			if (flag0 == 0) { 
				$('#ficha_inscricao').slideDown('fast'); flag0 = 1;
				$(this).html($(this).html().replace("+","-")); }
			else {
				$('#ficha_inscricao').slideUp('fast'); flag0 = 0; 
				$(this).html($(this).html().replace("-","+")); }
		});
		$('a#a_pontuacao').click(function() {
			if (flag1 == 0) { 
				$('#pontuacao').slideDown('fast'); flag1 = 1;
				$(this).html($(this).html().replace("+","-")); }
			else {
				$('#pontuacao').slideUp('fast'); flag1 = 0;
				$(this).html($(this).html().replace("-","+")); }

		});
		$('a#a_questionario').click(function() {
			if (flag2 == 0) { 
				$('#questionario').slideDown('fast'); flag2 = 1;
				$(this).html($(this).html().replace("+","-")); }
			else {
				$('#questionario').slideUp('fast'); flag2 = 0;
				$(this).html($(this).html().replace("-","+")); }

		});
		$('a#a_IIA').click(function() {
			if (flag3 == 0) { 
				$('#IIA').slideDown('fast'); flag3 = 1;
				$(this).html($(this).html().replace("+","-")); }
			else {
				$('#IIA').slideUp('fast'); flag3 = 0;
				$(this).html($(this).html().replace("-","+")); }

		});
		$('a#a_IIA2').click(function() {
			if (flag4 == 0) { 
				$('#IIA2').slideDown('fast'); flag4 = 1;
				$(this).html($(this).html().replace("+","-")); }
			else {
				$('#IIA2').slideUp('fast'); flag4 = 0;
				$(this).html($(this).html().replace("-","+")); }

		});
		$('a#a_IIB').click(function() {
			if (flag5 == 0) { 
				$('#IIB').slideDown('fast'); flag5 = 1;
				$(this).html($(this).html().replace("+","-")); }
			else {
				$('#IIB').slideUp('fast'); flag5 = 0;
				$(this).html($(this).html().replace("-","+")); }

		});
		$('a#a_IIC').click(function() {
			if (flag6 == 0) { 
				$('#IIC').slideDown('fast'); flag6 = 1;
				$(this).html($(this).html().replace("+","-")); }
			else {
				$('#IIC').slideUp('fast'); flag6 = 0;
				$(this).html($(this).html().replace("-","+")); }

		});
		$('a#a_IID').click(function() {
			if (flag7 == 0) { 
				$('#IID').slideDown('fast'); flag7 = 1;
				$(this).html($(this).html().replace("+","-")); }
			else {
				$('#IID').slideUp('fast'); flag7 = 0;
				$(this).html($(this).html().replace("-","+")); }

		});
		$('a#a_IIIA').click(function() {
			if (flag8 == 0) { 
				$('#IIIA').slideDown('fast'); flag8 = 1;
				$(this).html($(this).html().replace("+","-")); }
			else {
				$('#IIIA').slideUp('fast'); flag8 = 0;
				$(this).html($(this).html().replace("-","+")); }

		});
		$('a#a_IIIB').click(function() {
			if (flag9 == 0) { 
				$('#IIIB').slideDown('fast'); flag9 = 1;
				$(this).html($(this).html().replace("+","-")); }
			else {
				$('#IIIB').slideUp('fast'); flag9 = 0;
				$(this).html($(this).html().replace("-","+")); }

		});
		$('a#a_IIIC').click(function() {
			if (flag10 == 0) { 
				$('#IIIC').slideDown('fast'); flag10 = 1;
				$(this).html($(this).html().replace("+","-")); }
			else {
				$('#IIIC').slideUp('fast'); flag10 = 0;
				$(this).html($(this).html().replace("-","+")); }

		});
		$('a#a_IIID').click(function() {
			if (flag11 == 0) { 
				$('#IIID').slideDown('fast'); flag11 = 1;
				$(this).html($(this).html().replace("+","-")); }
			else {
				$('#IIID').slideUp('fast'); flag11 = 0;
				$(this).html($(this).html().replace("-","+")); }

		});
		$('a#a_IIID2').click(function() {
			if (flag12 == 0) { 
				$('#IIID2').slideDown('fast'); flag12 = 1;
				$(this).html($(this).html().replace("+","-")); }
			else {
				$('#IIID2').slideUp('fast'); flag12 = 0;
				$(this).html($(this).html().replace("-","+")); }

		});
		$('a#a_IV').click(function() {
			if (flag13 == 0) { 
				$('#IV').slideDown('fast'); flag13 = 1;
				$(this).html($(this).html().replace("+","-")); }
			else {
				$('#IV').slideUp('fast'); flag13 = 0;
				$(this).html($(this).html().replace("-","+")); }

		});
		$('a#a_IVB').click(function() {
			if (flag14 == 0) { 
				$('#IVB').slideDown('fast'); flag14 = 1;
				$(this).html($(this).html().replace("+","-")); }
			else {
				$('#IVB').slideUp('fast'); flag14 = 0;
				$(this).html($(this).html().replace("-","+")); }

		});
		$('a#a_V').click(function() {
			if (flag15 == 0) { 
				$('#V').slideDown('fast'); flag15 = 1;
				$(this).html($(this).html().replace("+","-")); }
			else {
				$('#V').slideUp('fast'); flag15 = 0;
				$(this).html($(this).html().replace("-","+")); }

		});
	});
</script>
<style type="text/css">
	.selecionado {
		color: blue;
	}
	#pontuacao { display: none; }
	#questionario { display: none; }

	#IIA { display: none; }

	#IIA2 { display: none; }

	#IIB { display: none; }

	#IIC { display: none; }

	#IID { display: none; }

	#IIIA { display: none; }

	#IIIB { display: none; }

	#IIIC { display: none; }

	#IIID { display: none; }

	#IIID2 { display: none; }

	#IV { display: none; }

	#IVB { display: none; }

	#V { display: none; }
</style>
<h3><b><a id="a_ficha_inscricao">[-] Campos da ficha de inscrição</a></b></h3>
<hr/>
<div id="ficha_inscricao" class="filtro">
<?php
	echo $form->create('Estudante', array('action' => 'filtrar/resultados', 'class' => 'formulario'));

	echo $form->input('numero_inscricao', array('label' => 'Número de inscrição', 'size' => '10'));
	echo '<br/>';

	echo $form->input('ano', array('label' => 'Ano', 'size' => '10'));
	echo '<br/>';

	echo $form->input('nome', array('label' => 'Nome', 'size' => '60'));
	echo '<br/>';

	echo $form->input('rg', array('label' => 'RG', 'size' => '30'));
	echo '<br/>';

	echo $form->input('orgao_emissor_rg', array('label' => 'Orgão emissor', 'size' => '30'));
	echo '<br/>';

	echo $form->input('cpf', array('label' => 'CPF', 'size' => '30'));
	echo '<br/>';

	echo $form->input('endereco', array('label' => 'Endereço', 'size' => '60'));
	echo '<br/>';

	echo $form->input('bairro', array('label' => 'Bairro', 'size' => '40'));
	echo '<br/>';

	echo $form->label('estado');

	echo $form->select('estado', $estados, $estado_selecionado, null, true);
	echo '<br/>';

	echo '<div id="cidades">';
	echo $form->label('Cidade');
	echo $form->select('cidade', $cidades, $cidade_selecionada, null, true);
	echo '</div>';

	echo '<br/>';

	echo $form->input('cep', array('label' => 'CEP', 'size' => '30'));
	echo '<br/>';

	echo $form->input('telefone_residencial', array('label' => 'Telefone residencial', 'size' => '30'));
	echo '<br/>';

	echo $form->input('telefone_outro', array('label' => 'Outro telefone', 'size' => '30'));
	echo '<br/>';

	echo $form->input('ano_conclusao_ensino_medio', array('label' => 'Ano de conclusão do ensino médio', 'size' => '10'));
	echo '<br/>';

	echo $form->label('unidade_id', 'Unidade');
	echo $form->select('unidade_id', $unidades, null, null, true);
	echo '<br/>';

echo $form->label('rematriculado', 'Apenas rematriculados');
echo $form->checkbox('rematriculado');
echo '<br/>';


?>
</div>
<br/>
<h3><b><a id="a_questionario">[+] Campos do questionário</a></b></h3>
<hr/>
<div id="questionario" class="filtro">
<br/>
	<?php 

		foreach ($grupoQuestoes['0'] as $questao)
		{
			$questaoHtml = $questionario->montar_questao($questao['Questao']);

			$questionario->preencher_questao($questaoHtml, '');
			echo $questaoHtml;
		}

	?>
	<h3><a id="a_IIA">[+] II.A. Sobre a escolaridade do estudante</a></h3>
	<hr/>
	<div id="IIA" class="filtro">
		<?php 

			foreach ($grupoQuestoes['1'] as $questao)
			{
				$questaoHtml = $questionario->montar_questao($questao['Questao']);

				$questionario->preencher_questao($questaoHtml, '');
				echo $questaoHtml;
			}

		?>
	</div><br/>
	<h3><a id="a_IIA2">[+] II.A.2. Escolarização não formal</a></h3>
	<hr/>
	<div id="IIA2" class="filtro">
		<?php 

			foreach ($grupoQuestoes['2'] as $questao)
			{
				$questaoHtml = $questionario->montar_questao($questao['Questao']);

				$questionario->preencher_questao($questaoHtml, '');
				echo $questaoHtml;
			}

		?>
	</div><br/>

	<h3><a id="a_IIB">[+] II.B. Sobre o trabalho do estudante</a></h3>
	<hr/>
	<div id="IIB" class="filtro">
		<?php 

			foreach ($grupoQuestoes['3'] as $questao)
			{
				$questaoHtml = $questionario->montar_questao($questao['Questao']);

				$questionario->preencher_questao($questaoHtml, '');
				echo $questaoHtml;
			}

		?>
	</div><br/>

	<h3><a id="a_IIC">[+] II.C. Sobre o estudante</a></h3>
	<hr/>
	<div id="IIC" class="filtro">
		<?php 

			foreach ($grupoQuestoes['4'] as $questao)
			{
				$questaoHtml = $questionario->montar_questao($questao['Questao']);

				$questionario->preencher_questao($questaoHtml, '');
				echo $questaoHtml;
			}

		?>
	</div><br/>

	<h3><a id="a_IID">[+] II.D. Informações sobre os pais do(a) estudante</a></h3>
	<hr/>
	<div id="IID" class="filtro">
		<?php 

			foreach ($grupoQuestoes['5'] as $questao)
			{
				$questaoHtml = $questionario->montar_questao($questao['Questao']);

				$questionario->preencher_questao($questaoHtml, '');
				echo $questaoHtml;
			}

		?>
	</div><br/>

	<h3><a id="a_IIIA">[+] III.A. Moradia</a></h3>
	<hr/>
	<div id="IIIA" class="filtro">
		<?php 

			foreach ($grupoQuestoes['6'] as $questao)
			{
				$questaoHtml = $questionario->montar_questao($questao['Questao']);

				$questionario->preencher_questao($questaoHtml, '');
				echo $questaoHtml;
			}

		?>
	</div><br/>

	<h3><a id="a_IIIB">[+] III.B. Outros imóveis do estudante</a></h3>
	<hr/>
	<div id="IIIB" class="filtro">
		<?php 

			foreach ($grupoQuestoes['7'] as $questao)
			{
				$questaoHtml = $questionario->montar_questao($questao['Questao']);

				$questionario->preencher_questao($questaoHtml, '');
				echo $questaoHtml;
			}

		?>
	</div><br/>

	<h3><a id="a_IIIC">[+] III.C. Quantidade de eletrodomésticos</a></h3>
	<hr/>
	<div id="IIIC" class="filtro">
		<?php 

			foreach ($grupoQuestoes['8'] as $questao)
			{
				$questaoHtml = $questionario->montar_questao($questao['Questao']);

				$questionario->preencher_questao($questaoHtml, '');
				echo $questaoHtml;
			}

		?>
	</div><br/>

	<h3><a id="a_IIID">[+] III.D. Serviços</a></h3>
	<hr/>
	<div id="IIID" class="filtro">
		<?php 

			foreach ($grupoQuestoes['9'] as $questao)
			{
				$questaoHtml = $questionario->montar_questao($questao['Questao']);

				$questionario->preencher_questao($questaoHtml, '');
				echo $questaoHtml;
			}

		?>
	</div><br/>

	<h3><a id="a_IIID2">[+] III.D.2. Informações sobre veículos</a></h3>
	<hr/>
	<div id="IIID2" class="filtro">
		<?php 

			foreach ($grupoQuestoes['10'] as $questao)
			{
				$questaoHtml = $questionario->montar_questao($questao['Questao']);

				$questionario->preencher_questao($questaoHtml, '');
				echo $questaoHtml;
			}

		?>
	</div><br/>

	<h3><a id="a_IV">[+] IV. Dados econômicos</a></h3>
	<hr/>
	<div id="IV" class="filtro">
		<?php 

			foreach ($grupoQuestoes['11'] as $questao)
			{
				$questaoHtml = $questionario->montar_questao($questao['Questao']);

				$questionario->preencher_questao($questaoHtml, '');
				echo $questaoHtml;
			}

		?>
	</div><br/>

	<h3><a id="a_IVB">[+] IV.B. Informações sobre as pessoas que moram com o estudante</a></h3>
	<hr/>
	<div id="IVB" class="filtro">
		<?php 

			foreach ($grupoQuestoes['12'] as $questao)
			{
				$questaoHtml = $questionario->montar_questao($questao['Questao']);

				$questionario->preencher_questao($questaoHtml, '');
				echo $questaoHtml;
			}

		?>
	</div><br/>

	<h3><a id="a_V">[+] V. Informações adicionais</a></h3>
	<hr/>
	<div id="V" class="filtro">
		<?php 

			foreach ($grupoQuestoes['13'] as $questao)
			{
				$questaoHtml = $questionario->montar_questao($questao['Questao']);

				$questionario->preencher_questao($questaoHtml, '');
				echo $questaoHtml;
			}

		?>
	</div><br/>
</div>
<br/>
<?php 
	echo $form->button('Filtrar', array('type' => 'submit'));
	echo $form->button('Limpar o formulário', array('type' => 'reset'));

	echo $form->end();
?>
