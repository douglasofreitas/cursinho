
<?php
function codificar($string){
    if((isset($string)) && (is_string($string))){
        $enc_string = base64_encode($string);
        $enc_string = str_replace("=","",$enc_string);
        $enc_string = strrev($enc_string);
        $md5 = md5($string);
        $enc_string = substr($md5,0,3).$enc_string.substr($md5,-3);
    }else{
        $enc_string = "Parâmetro incorreto ou inexistente!";
    }
    return $enc_string;
}
function descodificar($string){
    if((isset($string)) && (is_string($string))){
        $ini = substr($string,0,3);
        $end = substr($string,-3);
        $des_string = substr($string,0,-3);
        $des_string = substr($des_string,3);
        $des_string = strrev($des_string);
        $des_string = base64_decode($des_string);
        $md5 = md5($des_string);
        $ver = substr($md5,0,3).substr($md5,-3);
        if($ver != $ini.$end){
            $des_string = "Erro na desencriptação!";
        }
    }else{
        $des_string = "Parâmetro incorreto ou inexistente!";
    }
    return $des_string;
}
?>

<h1><?php echo $processo['ProcessoSeletivo']['nome'] ?></h1>

<?php
if(empty($faturas)){

	
	if (true){
		echo '<p>Não há mensalidades registradas</p>';
	}else{
		//método antigo de 2013
    ?>

    <br/>
    Escolha a forma de pagamento de sua preferência:<br/>
    <br/>
    <table class="listagem">
        <tr class="listagem_header">
            <td>
                Tipo de pagamento
            </td>
            <td>
                Ação
            </td>
        </tr>
        <tr>
            <td>
                1 parcela de R$ 190,00 com vencimento para o dia 10 de setembro de 2013.
            </td>
            <td>
                <?php
                echo $html->link(
                    'Gerar fatura',
                    '/candidatos/gerar_faturas/'.$candidato_id.'/'.$processo['ProcessoSeletivo']['processo_seletivo_id'].'/1',
                    array('style' => 'font-weight: bold;font-size: 15px')
                );
                ?>
            </td>
        </tr>
        <tr>
            <td>
                3 parcelas de R$ 70,00 com vencimento para o dia 20 de agosto, 10 de setembro e 10 de outrubro de 2013.
            </td>
            <td>
                <?php
                echo $html->link(
                    'Gerar faturas',
                    '/candidatos/gerar_faturas/'.$candidato_id.'/'.$processo['ProcessoSeletivo']['processo_seletivo_id'].'/2',
                    array('style' => 'font-weight: bold;font-size: 15px')
                );
                ?>
            </td>
        </tr>
    </table>
<?php
	}	


}else{
    ?>
    <br/>
    <h3>Faturas</h3><br/>

    <table class="listagem">
        <tr class="listagem_header">
            <td>
                Valor
            </td>
            <td>
                Vencimento
            </td>
            <td>
                Status
            </td>
            <td>
                Ação
            </td>
        </tr>


        <?php $numero_linha = 0; ?>

        <?php foreach ($faturas as $fatura): ?>

            <?php $numero_linha++ ?>

            <?php if ($numero_linha % 2 == 1): ?>
                <tr class="linha_impar">
            <?php else: ?>
                <tr class="linha_par">
            <?php endif; ?>

            <td>
                R$ <?php echo number_format($fatura['Fatura']['valor'], 2, ',', ''); ?>
            </td>
            <td>
                <?php echo date('d/m/Y', strtotime($fatura['Fatura']['data_vencimento'])); ?>
            </td>
            <td style="text-align:center;">
                <?php
                if($fatura['Fatura']['pago'] == 0)
                    echo '<span style="color:red">Não pago</span>';
                else{
                    echo '<span style="color:blue">Pago</span>';
                }
                ?>
            </td>

            <td style="text-align:center;">
                <?php
                if($fatura['Fatura']['pago'] == 0){
                    ?>
                    <a style="font-weight: bold;font-size: 15px;background: #FFBD76;padding: 3px;" href="http://www.cursinho.ufscar.br/boleto/?codigo=<?php echo codificar($fatura['Fatura']['id']) ; ?>">
                        Fazer pagamento
                    </a>
                <?php
                }else{

                }

                ?>
            </td>
            </tr>
        <?php endforeach; ?>


    </table>

<?php
}
?>
