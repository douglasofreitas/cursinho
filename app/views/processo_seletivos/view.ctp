<?php
echo $html->link(
    $html->image('icon/page_white_edit.png', array("alt" => "Editar", 'class' => 'tipHoverBottom', 'title' => 'Editar')),
    '/processo_seletivos/edit/'.$processo_seletivo['ProcessoSeletivo']['processo_seletivo_id'],
    array('escape' => false)
);
?>
<br/>

<label class="formulario">Ano / Sigla:</label>
<?php echo $processo_seletivo['ProcessoSeletivo']['ano'] ?>
<br/>

<label class="formulario">Nome:</label>
<?php echo $processo_seletivo['ProcessoSeletivo']['nome'] ?>
<br/>

<label class="formulario">Salário mínimo:</label>
R$ <?php echo number_format($processo_seletivo['ConfiguracaoProcessoSeletivo']['valor_salario_minimo'], 2, ',', ''); ?>
<br/>


<label class="formulario">Data da prova:</label>
<?php echo date('d/m/Y', strtotime($processo_seletivo['ConfiguracaoProcessoSeletivo']['data_prova'])); ?>
<br/>


<?php if($_SESSION['Configuracao']['possui_questionario']): ?>

    <label class="formulario">Preencimento do questionário:</label>
    <?php
    if(!empty($processo_seletivo['ConfiguracaoProcessoSeletivo']['data_questionario']))
    echo date('d/m/Y', strtotime($processo_seletivo['ConfiguracaoProcessoSeletivo']['data_questionario']));
    ?>
    <br/>

<?php endif; ?>

<label class="formulario">Possui inscrição online?</label>
<?php
if($processo_seletivo['ConfiguracaoProcessoSeletivo']['inscricao_online'] == 1){
    echo 'SIM';
}else{
    echo 'NÃO';
}
?>
<br/>


<?php if($processo_seletivo['ConfiguracaoProcessoSeletivo']['inscricao_online'] == 1): ?>

    <label class="formulario">Inscrição online (Início):</label>
    <?php echo date('d/m/Y', strtotime($processo_seletivo['ConfiguracaoProcessoSeletivo']['inscricao_online_inicio'])); ?>
    <br/>

    <label class="formulario">Inscrição online (Fim):</label>
    <?php echo date('d/m/Y', strtotime($processo_seletivo['ConfiguracaoProcessoSeletivo']['inscricao_online_fim'])); ?>
    <br/>

    <label class="formulario">Vagas:</label>
    <?php echo $processo_seletivo['ConfiguracaoProcessoSeletivo']['vagas']; ?>
    <br/>

<?php endif; ?>


<label class="formulario">Ativo:</label>
<?php
if($processo_seletivo['ConfiguracaoProcessoSeletivo']['ativo'] == 1){
    echo 'SIM';
}else{
    echo 'NÃO';
}
?>
<br/>
<br/>

<h3>Formas de pagamento</h3>
<br/>

<table class="listagem">
    <tr class="listagem_header">
        <td>
            Número de parcelas
        </td>
        <td>
            Valor
        </td>
        <td>
            Vencimento
        </td>
    </tr>


    <?php foreach(json_decode($processo_seletivo['ConfiguracaoProcessoSeletivo']['forma_pagamento']) as $forma_pagamento): ?>
    <tr class="linha_impar">
        <td>
            <?php echo $forma_pagamento->num_parcelas ?>
        </td>
        <td>
            <?php echo $forma_pagamento->valor ?>
        </td>
        <td>
            <?php echo $forma_pagamento->vencimento ?>
        </td>
    </tr>
    <?php endforeach; ?>

</table>

<br/>
<br/>
<h3>Status do processo</h3>
<br/>

<table class="listagem">
    <tr class="listagem_header">
        <td>

        </td>
        <td>
            Turma de 1 ano
        </td>
        <td>
            Turma de 2 anos
        </td>
    </tr>

    <tr class="linha_impar">
        <td>
            Número de candidatos
        </td>
        <td>
            <?php echo $num_candidatos_1; ?>
        </td>
        <td>
            <?php echo $num_candidatos_2; ?>
        </td>
    </tr>
    <tr class="linha_impar">
        <td>
            Questionários preechidos
        </td>
        <td>
            <?php echo $num_questionarios_1; ?>
        </td>
        <td>
            <?php echo $num_questionarios_2; ?>
        </td>
    </tr>
    <tr class="linha_impar">
        <td>
            Aprovados na fase eliminatória
        </td>
        <td>
            <?php echo $num_fase_aliminatoria_1; ?>
        </td>
        <td>
            <?php echo $num_fase_aliminatoria_2; ?>
        </td>
    </tr>
    <tr class="linha_impar">
        <td>
            Provas realizadas
        </td>
        <td>
            <?php echo $num_provas_1; ?>
        </td>
        <td>
            <?php echo $num_provas_2; ?>
        </td>
    </tr>
    <tr class="linha_impar">
        <td>
            Aprovados
        </td>
        <td>
            <?php echo $num_aprovados_1; ?>
        </td>
        <td>
            <?php echo $num_aprovados_2; ?>
        </td>
    </tr>
    <tr class="linha_impar">
        <td>
            Matrículados
        </td>
        <td>
            <?php echo $num_matriculados_1; ?>
        </td>
        <td>
            <?php echo $num_matriculados_2; ?>
        </td>
    </tr>

</table>

