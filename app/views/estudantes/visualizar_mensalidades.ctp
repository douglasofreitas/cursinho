
<?php

echo 'Teste'.strtotime('2014/03/20');
echo 'Teste'. intval(date("m", strtotime('2014-03-20')));

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


<script>
	function gerar_mensalidade_ano(){
		var titulo = 'Gerar mensalidades';
		var mensagem = '<?php  
		echo $form->create('Estudante', array('url' => array('controller' => 'estudantes', 'action' => 'gerar_mensalidades/'.$estudante['Estudante']['estudante_id']), 'class' => 'formulario'));
		echo $form->hidden('estudante_id');

		echo '<label>Dia do pagamento</label><br/>';
		echo $form->input('dia', array('label' => false));
		echo '<br/>';
		echo '<label>Mês</label><br/>';
		echo $form->input('mes', array('label' => false, 'value' => date('m')));
		echo '<br/>';
		echo '<label>Valor das mensalidades</label><br/>';
		echo $form->input('valor', array('label' => false, 'value' => $estudante['Estudante']['valor_mensalidade']));
		echo '<br/>';

		echo $form->end('Gerar');
		?>';
		popupMsg(titulo,mensagem);
		return false;
	}
</script>
<h3>Estudante: <?php echo $estudante['Candidato']['nome'] ?></h3>
<br/>
<h3>Número de inscrição: <?php echo $estudante['Candidato']['numero_inscricao'].' <br/>Ano: '.$estudante['Candidato']['ano'] ?></h3>
<br/>
<h3>Código do estudante: <?php echo $estudante['Estudante']['estudante_id'] ?> </h3>
<br/><br/>
<h3><?php echo $html->link('Voltar para a ficha do Estudante', '/estudantes/visualizar_ficha/'.$estudante['Estudante']['estudante_id'], array('style' => 'color:#0000CC')) ?></h3>
<br/>
<h3><?php //echo $html->link('Gerar mensalidades de outro ano', '#', array('onclick' => 'gerar_mensalidade_ano()'  , 'style' => 'color:#0000CC')) ?></h3>
<h3><?php echo $html->link('Imprimir todas as mensalidades', '/faturas/imprimir_mensalidades/'.$estudante['Estudante']['estudante_id'], array('style' => 'color:#0000CC')) ?></h3>

<br/><br/><hr/>
<?php foreach ($mensalidades as $mensalidade): ?>
<?php 
	if ($mensalidade['Fatura']['pago'] == 1)
		$cor = 'green';
	else
		$cor = 'red';
?>
<h2 style="color: <?php echo $cor ?>"><?php echo $meses[$mensalidade['Fatura']['mes_ref']].' - '.$mensalidade['Fatura']['ano_ref'] ?> </h2>
<br/>
<h3 style="color: <?php echo $cor ?>">Valor: <?php echo $mensalidade['Fatura']['valor'] ?> <?php //echo $html->link('Mudar valor da mensalidade', '/mensalidades/editar_mensalidade/'.$mensalidade['Fatura']['id']); ?> </h3>
<br/>
<h3 style="color: <?php echo $cor ?>">Vencimento: <?php echo date('d/m/Y', strtotime($mensalidade['Fatura']['data_vencimento'])) ?></h3>
<br/>
<h3 style="color: <?php echo $cor ?>">Data do pagamento: <?php if(!empty($mensalidade['Fatura']['data_baixa'])) echo date('d/m/Y', strtotime($mensalidade['Fatura']['data_baixa'])) ?></h3>
<br/>
<a style="font-weight: bold;font-size: 15px;background: #FFBD76;padding: 3px;" href="http://www.cursinho.ufscar.br/boleto/?codigo=<?php echo codificar($mensalidade['Fatura']['id']) ; ?>">
	Visualizar fatura
</a>
    <br/><br/>
    <?php echo $html->link('Editar', '/faturas/editar/'.$mensalidade['Fatura']['id'], array('style' => 'background:#ffe8b1;padding: 3px;', 'target' => '_blank')) ?></h3>

<br/><br/>

<?php 

	if ($mensalidade['Fatura']['pago'] == 0)
	{
		$url = 'registrar_pagamento_mensalidade/'.$mensalidade['Fatura']['id'];
		echo $form->create('Estudante', array('action' => $url, 'class' => 'formulario'));
		echo $form->end('Registrar pagamento');
	}
	else
	{
		//todo gruopdf: falta atualizar
		
		//$url = 'gerar_recibo_mensalidade/'.$mensalidade['Fatura']['id'];
		//echo $form->create('Estudante', array('action' => $url, 'class' => 'formulario'));
		//echo $form->end('Gerar comprovante');
	}

	if ($mensalidade['Fatura']['isento'] == 1)
	{
		echo "<br/><br/>";
		echo "<h3>Observação: Estudante isento de mensalidade neste mês</h3>";
	}
?>
<br/><br/><hr/>
<?php endforeach; ?>
