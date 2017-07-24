<script type="text/javascript">
	$(function() {
        $("#CandidatoInscricaoForm").validate({
            rules: {
                'data[Candidato][email]': {
                    required: true, email:true
                },
                'data[Candidato][email_2]': {
                    required: true, equalTo: "#CandidatoEmail"
                },
                'data[Candidato][cpf]': {
                    required: true
                },
                'data[Candidato][senha]': {
                    required: true
                },
                'data[Candidato][senha_2]': {
                    required: true,
                    equalTo: "#CandidatoSenha"
                },
                'data[Candidato][nome]': {
                    required: true
                },
                'data[Candidato][nome_mae]': {
                    required: true
                },
                'data[Candidato][rg]': {
                    required: true
                },
                'data[Candidato][orgao_emissor_rg]': {
                    required: true
                },
                'data[Candidato][endereco]': {
                    required: true
                },
                'data[Candidato][numero]': {
                    required: true
                },
                'data[Candidato][bairro]': {
                    required: true
                },
                'data[Candidato][telefone_residencial]': {
                    required: true
                },
                'data[Candidato][ano_conclusao_ensino_medio]': {
                    required: true
                }

            },
            messages: {
                'data[Candidato][email]': {
                    required: "<span style='color:red'>Obrigatório!</span>",
                    email: "<span style='color:red'>Insira um e-mail válido</span>",
                },
                'data[Candidato][cpf]': {
                    required: "<span style='color:red'>Obrigatório!</span>"
                },
                'data[Candidato][senha]': {
                    required: "<span style='color:red'>Obrigatório!</span>"
                },
                'data[Candidato][senha_2]': {
                    required: "<span style='color:red'>Obrigatório!</span>",
                    equalTo: "<span style='color:red'>Senhas não coincidem</span>"
                },
                'data[Candidato][email_2]': {
                    required: "<span style='color:red'>Obrigatório!</span>",
                    equalTo: "<span style='color:red'>E-mail não coincide</span>"
                },
                'data[Candidato][nome]': {
                    required: "<span style='color:red'>Obrigatório!</span>"
                },
                'data[Candidato][nome_mae]': {
                    required: "<span style='color:red'>Obrigatório!</span>"
                },
                'data[Candidato][rg]': {
                    required: "<span style='color:red'>Obrigatório!</span>"
                },
                'data[Candidato][orgao_emissor_rg]': {
                    required: "<span style='color:red'>Obrigatório!</span>"
                },
                'data[Candidato][endereco]': {
                    required: "<span style='color:red'>Obrigatório!</span>"
                },
                'data[Candidato][numero]': {
                    required: "<span style='color:red'>Obrigatório!</span>"
                },
                'data[Candidato][bairro]': {
                    required: "<span style='color:red'>Obrigatório!</span>"
                },
                'data[Candidato][telefone_residencial]': {
                    required: "<span style='color:red'>Obrigatório!</span>"
                },
                'data[Candidato][ano_conclusao_ensino_medio]': {
                    required: "<span style='color:red'>Obrigatório!</span>"
                }

            }
        });

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
<script type="text/javascript" >
        function gerar_numero_inscricao() {
                if ( $("#CandidatoNumeroInscricao").is(":visible") ){
                        $("#CandidatoNumeroInscricao").val('');
                        $("#CandidatoNumeroInscricao").hide("fast");
                }else
                        $("#CandidatoNumeroInscricao").show("fast");
        }
</script>
<br/>

<h2>
    Inscricão: <?php echo $processo_seletivo['ProcessoSeletivo']['nome']; ?>
    <?php
    if($processo_seletivo['ConfiguracaoProcessoSeletivo']['vagas'] <= $num_candidatos){
        echo ' (LISTA DE ESPERA) ';
    }
    ?>
</h2>

<br/><br/>
<h3>
    Preencha a ficha de inscrição em seguida clique no botão Avançar. <br/>
    Após a conclusão da inscrição e do questionário sócio-econômico , será possível acessar o sistema com seu CPF como login e a sua senha cadastrada, para gerar uma segunda via do boleto e visualizar seu status no processo seletivo<br/>
    <br/>
    Se houver dificuldade em realizar a inscrição entre em contato com o cursinho.
    <?php
    if($processo_seletivo['ConfiguracaoProcessoSeletivo']['vagas'] <= $num_candidatos){
        echo ' <br/><br/> <span style="color:blue"> Por ser um cadastro para a lista de espera, não será gerado o boleto de inscrição. Somente quando houver desistência do curso.</span> ';
    }
    ?>
</h3>
<br />
<br/>
<?php
	echo $form->create('Candidato', array('action' => 'inscricao', 'class' => 'formulario'));

if($processo_seletivo['ConfiguracaoProcessoSeletivo']['vagas'] <= $num_candidatos){
    echo $form->hidden('lista_espera', array('value' => 1));
}

echo $form->input('email', array('label' => 'E-mail', 'size' => '40'));
echo '<br/>';
echo $form->input('email_2', array('label' => 'Confirmar E-mail', 'size' => '40'));
echo '<br/>';
echo $form->input('cpf', array('label' => 'CPF (somente números)', 'size' => '30'));
echo '<br/>';
echo $form->input('senha', array('label' => 'Senha', 'size' => '15', 'type' => 'password'));
echo '<br/>';
echo $form->input('senha_2', array('label' => 'Confirmar Senha', 'size' => '15', 'type' => 'password'));
echo '<br/>';
echo '<br/>';
echo '<br/>';

	echo $form->input('nome', array('label' => 'Nome *', 'size' => '60'));
	echo '<br/>';

	echo $form->input('rg', array('label' => 'RG *', 'size' => '30'));
	echo '<br/>';

	echo $form->input('orgao_emissor_rg', array('label' => 'Orgão emissor *', 'size' => '30'));
	echo '<br/>';

	echo $form->input('nome_mae', array('label' => 'Nome da Mãe(completo) *', 'size' => '60'));
	echo '<br/>';

	echo $form->input('nome_pai', array('label' => 'Nome do Pai(completo)', 'size' => '60'));
	echo '<br/>';

	echo $form->input('endereco', array('label' => 'Endereço *', 'size' => '60'));
	echo '<br/>';

	echo $form->input('numero', array('label' => 'Número *'));
	echo '<br/>';

	echo $form->input('complemento', array('label' => 'Complemento', 'size' => '30'));
	echo '<br/>';

	echo $form->input('bairro', array('label' => 'Bairro *', 'size' => '40'));
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

	echo $form->input('telefone_residencial', array('label' => 'Telefone residencial *', 'size' => '30'));
	echo '<br/>';

	echo $form->input('telefone_outro', array('label' => 'Outro telefone', 'size' => '30'));
	echo '<br/>';

	echo $form->input('ano_conclusao_ensino_medio', array('label' => 'Ano de conclusão do ensino médio *', 'size' => '10'));
	echo '<br/>';

	echo $form->label('unidade', 'Unidade');
	echo $form->select('unidade_id', $unidades, null, null, false);
	echo '<br/>';

    echo '<div style="display: none" >';
    echo $form->label('turma', 'Turma');
	echo $form->select('turma', array('1' => '1 ano', '2' => '2 anos'), $this->data['Candidato']['turma'], null, false);
	echo '<br/>';
    echo '</div>';

    $formas_pagamento = json_decode($processo_seletivo['ConfiguracaoProcessoSeletivo']['forma_pagamento']);
    $array_forma_pagamento = array();
	$is_forma_pagamento_empty = false;
    foreach($formas_pagamento as $index => $item){
        $array_forma_pagamento[$index] = $item->num_parcelas." x de R$ ".$item->valor;
		if(empty($item->num_parcelas)){
			$is_forma_pagamento_empty = true;
		}
    }
	
	if(! $is_forma_pagamento_empty){
		echo $form->label('forma_pagamento_index', 'Forma de pagamento');
		echo $form->select('forma_pagamento_index', $array_forma_pagamento, null, null, false);
		echo '<br/>';
	}
    

	echo $form->end('Avançar');

	//$options = array('url' => array('action' => 'atualiza_cidades'), 'update' => 'cidades', 'frequency' => 0.2);

	//echo $ajax->observeField('CandidatoEstado', $options);

?>
