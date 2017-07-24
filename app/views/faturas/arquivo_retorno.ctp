
<h3>Processar arquivo de retorno dos boletos.</h3>
<br/> 


<div id='form_filtro' >
    <?php
    echo $form->create('Fatura', array('action' => 'arquivo_retorno', 'class' => 'formulario', 'enctype' => 'multipart/form-data'));

    echo "<input type='file' name='data[Fatura][arquivo][]' />";
    echo '<br/>';
    
    echo $form->button('Processar arquivo', array('type' => 'submit'));
    
    echo $form->end();
?>

</div>

<br/><br/>

<table class="listagem">
    <tr class="listagem_header">
        <td style="width:auto; text-align:center;">Nome do arquivo</td>
        <td style="width:auto; text-align:center;">Data de processamento</td>
        <td style="width:auto; text-align:center;">Faturas encontradas</td>
        <td style="width:auto; text-align:center;">Baixas realizadas</td>
        <td style="width:100px; text-align:center;">Ações</td>
    </tr>

    <?php $numero_linha = 0; ?>

    <?php foreach ($arquivos as $arquivo): ?>

        <?php $numero_linha++ ?>

        <?php if ($numero_linha % 2 == 1): ?>
            <tr class="linha_impar">
        <?php else: ?>
            <tr class="linha_par">
        <?php endif; ?>
        <td style="text-align:center"><?php echo $arquivo['ArquivoRetorno']['nome'] ?></td>
        <td style="text-align:center"><?php echo date('d/m/Y', strtotime($arquivo['ArquivoRetorno']['created'])) ?></td>
        <td style="text-align:center"><?php echo count($arquivo['ArquivoRetornoItem']) ?></td>
        <td style="text-align:center"><?php echo $arquivo['ArquivoRetorno']['numero_faturas'] ?></td>
        <td style="text-align:center;">
            <?php
            echo $html->link(
                'Visualizar',
                '/faturas/visualizar_arquivo_retorno/' . $arquivo['ArquivoRetorno']['id'],
                array('escape' => false)
            );
            ?>
        </td>
        </tr>
    <?php endforeach; ?>
</table> 
