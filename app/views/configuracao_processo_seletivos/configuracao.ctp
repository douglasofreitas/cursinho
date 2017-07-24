<script type="text/javascript" >
    function exibe_box() {
        if ( $("#ConfiguracaoProcessoSeletivoInscricaoOnline").is(":checked") ){
            $("#inscricao_online_box").show("fast");
        }else
            $("#inscricao_online_box").hide("fast");
    }
</script>

<h3>Configuração do processo seletivo</h3>
<br/>

<br/>
<?php 
echo $form->create('ConfiguracaoProcessoSeletivo', array('action' => 'edit/'.$processo_seletivo_id, 'class' => 'formulario'));
echo $form->hidden('processo_seletivo_id', array());
echo $form->hidden('id', array());

echo $form->input('valor_salario_minimo', array('label' => 'Valor do salário mínimo atual', 'style' => 'width: 70px;'));
echo '<br/>';

//echo $form->input('valor_inscricao', array('label' => 'Valor da inscrição', 'style' => 'width: 70px;'));
//echo '<br/>';

if(!empty($this->data['ConfiguracaoProcessoSeletivo']['data_prova']))
    echo $form->input('data_prova', array('label' => 'Data da prova', 'type' => 'text', 'value' => date('d/m/Y', strtotime($this->data['ConfiguracaoProcessoSeletivo']['data_prova']))));
else
    echo $form->input('data_prova', array('label' => 'Data da prova', 'type' => 'text'));
echo '( dd/mm/aaaa )';
echo '<br/>';

if($_SESSION['Configuracao']['possui_questionario']){
    if(!empty($this->data['ConfiguracaoProcessoSeletivo']['data_questionario']))
        echo $form->input('data_questionario', array('label' => 'Data do questionário', 'type' => 'text', 'value' => date('d/m/Y', strtotime($this->data['ConfiguracaoProcessoSeletivo']['data_questionario']))));
    else
        echo $form->input('data_questionario', array('label' => 'Data do questionário', 'type' => 'text'));
    echo '( dd/mm/aaaa )';
    echo '<br/>';
}

//if(!empty($this->data['ConfiguracaoProcessoSeletivo']['data_questionario']))
//    echo $form->input('data_limite_pagamento', array('label' => 'Data limite de pagamento', 'type' => 'text', 'value' => date('d/m/Y', strtotime($this->data['ConfiguracaoProcessoSeletivo']['data_limite_pagamento'])) ));
//else
//    echo $form->input('data_limite_pagamento', array('label' => 'Data limite de pagamento', 'type' => 'text'));
//echo '( dd/mm/aaaa )';
//echo '<br/>';

echo '<label class="formulario">Inscrição online?</label>';
echo $form->checkbox('inscricao_online', array('onclick' => 'exibe_box()'));
echo '<br/>';

$style_box = ' style="display:none" ';
if(!empty($this->data['ConfiguracaoProcessoSeletivo']['inscricao_online'])){
    $style_box = '';
}

echo '<div id="inscricao_online_box" '.$style_box.'>';

    echo '<label class="formulario">Inscrição online (Início)</label>';
    if(!empty($this->data['ConfiguracaoProcessoSeletivo']['inscricao_online_inicio']))
        echo $form->input('inscricao_online_inicio', array('label' => false, 'type' => 'text', 'value' => date('d/m/Y', strtotime($this->data['ConfiguracaoProcessoSeletivo']['inscricao_online_inicio']))));
    else
        echo $form->input('inscricao_online_inicio', array('label' => false, 'type' => 'text'));
    echo '( dd/mm/aaaa )';
    echo '<br/>';

    echo '<label class="formulario">Inscrição online (Fim)</label>';
    if(!empty($this->data['ConfiguracaoProcessoSeletivo']['inscricao_online_fim']))
        echo $form->input('inscricao_online_fim', array('label' => false, 'type' => 'text', 'value' => date('d/m/Y', strtotime($this->data['ConfiguracaoProcessoSeletivo']['inscricao_online_fim']))));
    else
        echo $form->input('inscricao_online_fim', array('label' => false, 'type' => 'text'));
    echo '( dd/mm/aaaa )';
    echo '<br/>';

    echo '<label class="formulario">Vagas</label>';
    echo $form->input('vagas', array('label' => false, 'type' => 'text', 'value' => $this->data['ConfiguracaoProcessoSeletivo']['vagas']));
    echo '<br/>';


echo '</div>';

echo '<label class="formulario">Ativo</label>';
echo $form->checkbox('ativo', array());
echo '<br/>';
?>


<script type="text/javascript">
    $(document).ready(function(){

        <?php
        $formas_pagamentos = json_decode($this->data['ConfiguracaoProcessoSeletivo']['forma_pagamento']);
        $total_forma_pagamento = count($formas_pagamentos);
        ?>

        var new_forma_pagamento_index = <?php echo $total_forma_pagamento; ?>;
        $('#add_forma_pagamento').click(function (){
            var new_forma_pagamento = '<fieldset> <legend>Forma de pagamento '+(new_forma_pagamento_index+1)+'</legend>'+
                '            <label>Número de parcelas</label>'+
                '        <input name="data[ConfiguracaoProcessoSeletivo][forma_pagamento]['+new_forma_pagamento_index+'][num_parcelas]" type="text"/>'+
                '            <br>'+
                '            <label>Valor da parcela</label>'+
                '        <input name="data[ConfiguracaoProcessoSeletivo][forma_pagamento]['+new_forma_pagamento_index+'][valor]" type="text"/>'+
                '            <br>'+
                '            <label>Vencimento da primeira parcela</label>'+
                '        <input name="data[ConfiguracaoProcessoSeletivo][forma_pagamento]['+new_forma_pagamento_index+'][vencimento]" type="text"/>'+
                '            <br>'+
                '            </fieldset>';

            $('#forma_pagamento_div').append(new_forma_pagamento);
            new_forma_pagamento_index++;
        });
    });


</script>

<h3>Formas de pagamento</h3>
<br>
<div id="forma_pagamento_div">
    <?php $count_forma_pagamento = 0; ?>
    <?php foreach($formas_pagamentos as $forma_pagamento): ?>
        <fieldset> <legend>Forma de pagamento <?php echo $count_forma_pagamento+1; ?></legend>
            <label>Número de parcelas</label>
            <input name="data[ConfiguracaoProcessoSeletivo][forma_pagamento][<?php echo $count_forma_pagamento; ?>][num_parcelas]" type="text" value="<?php echo $forma_pagamento->num_parcelas ?>"/>
            <br>
            <label>Valor da parcela</label>
            <input name="data[ConfiguracaoProcessoSeletivo][forma_pagamento][<?php echo $count_forma_pagamento; ?>][valor]" type="text" value="<?php echo $forma_pagamento->valor ?>"/>
            <br>
            <label>Vencimento da primeira parcela</label>
            <input name="data[ConfiguracaoProcessoSeletivo][forma_pagamento][<?php echo $count_forma_pagamento; ?>][vencimento]" type="text" value="<?php echo $forma_pagamento->vencimento ?>"/>
            <br>
        </fieldset>
        <?php $count_forma_pagamento++; ?>
    <?php endforeach; ?>


</div>
<br>
<a id="add_forma_pagamento">Adicionar forma de pagamento</a>


<br>
<br>
<br>
<?php
echo $form->end('Salvar');
?>