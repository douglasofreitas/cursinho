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

<style type="text/css">
    @media print {
        .page-break	{ display: block; page-break-before: always; }
    }
</style>


<?php
foreach($faturas as $mensalidade):
    if ($mensalidade['Fatura']['pago'] != 1):
    ?>
        <iframe style="width: 688px;height: 1007px;" src="http://www.cursinho.ufscar.br/boleto/?codigo=<?php echo codificar($mensalidade['Fatura']['id']) ; ?>"></iframe><br/>
        <div class="page-break"></div>
    <?php
    endif;
endforeach;
?>