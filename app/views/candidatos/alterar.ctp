<script type="text/javascript">
	$(function() {

		$('#CandidatoEstado').change(function()
			{
			    // selected value   
			    var selected = $(this).val();   

			    // set loading image   
			    //ajax_loading_image('.ajax_loading_image');

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
			});
	});
</script>
<h3>Altere os dados que desejar e em seguida clique no botão Alterar no final da página</h3>
<br />
<?php
	echo $form->create('Candidato', array('action' => 'alterar/'.$this->data['Candidato']['numero_inscricao'].'/'.$this->data['Candidato']['ano'], 'class' => 'formulario'));
	echo $form->hidden('candidato_id');

	echo $form->input('numero_inscricao', array('label' => 'Número de inscrição *', 'size' => '10'));
	echo '<br/>';

	echo $form->input('ano', array('label' => 'Ano *', 'size' => '10'));
	echo '<br/>';

	echo $form->input('nome', array('label' => 'Nome *', 'size' => '60'));
	echo '<br/>';

    echo $form->input('email', array('label' => 'E-mail', 'size' => '60'));
    echo '<br/>';

	echo $form->input('rg', array('label' => 'RG', 'size' => '30'));
	echo '<br/>';

	echo $form->input('orgao_emissor_rg', array('label' => 'Orgão emissor', 'size' => '30'));
	echo '<br/>';

	echo $form->input('cpf', array('label' => 'CPF', 'size' => '30'));
	echo '<br/>';

	echo $form->input('nome_mae', array('label' => 'Nome da Mãe(completo) *', 'size' => '60'));
	echo '<br/>';

	echo $form->input('nome_pai', array('label' => 'Nome do Pai(completo)', 'size' => '60'));
	echo '<br/>';

	echo $form->input('endereco', array('label' => 'Logradouro', 'size' => '60'));
	echo '<br/>';

	echo $form->input('numero', array('label' => 'Número'));
	echo '<br/>';

	echo $form->input('complemento', array('label' => 'Complemento', 'size' => '30'));
	echo '<br/>';

	echo $form->input('bairro', array('label' => 'Bairro', 'size' => '40'));
	echo '<br/>';

	echo $form->label('estado');

	echo $form->select('estado', $estados, $estado_selecionado, null, false);
	echo '<br/>';

	echo '<div id="cidades">';
	echo $form->label('Cidade');
	echo $form->select('cidade', $cidades, $cidade_selecionada, null, false);
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

	echo $form->label('taxa_inscricao', 'Taxa de inscrição');
	echo $form->checkbox('taxa_inscricao', array());
	echo '<br/>';

	echo $form->input('data_preenchimento_questionario', array('label' => 'Data para preenchimento do questionário'));
	echo '<br/>';

	echo $form->label('unidade_id', 'Unidade');
	echo $form->select('unidade_id', $unidades, $unidade_selecionada, null, true);
	echo '<br/>';

        echo $form->label('turma', 'Turma');
	echo $form->select('turma', array('1' => '1 ano', '2' => '2 anos'), $this->data['Candidato']['turma'], null, true);
	echo '<br/>';

        echo $form->label('fase_eliminatoria_status', 'Fase Eliminatória');
	echo $form->checkbox('fase_eliminatoria_status', array('checked' => 'false'));
	echo '<br/>';

        echo $form->label('fase_classificatoria_status', 'Fase classificatória');
	echo $form->checkbox('fase_classificatoria_status', array('checked' => 'false'));
	echo '<br/>';

	echo $form->label('matriculado', 'Matriculado');
	echo $form->checkbox('matriculado', array('checked' => 'false'));
	echo '<br/>';

	if ($turma == '2')
	{	
		echo $form->label('rematriculado', 'Rematrícula');
		echo $form->select('rematriculado', array('ano1' => 'Primeiro ano', 'ano2' => 'Segundo ano'));
		echo '<br/>';
	}

	echo $form->end('Alterar');
?>
