<p>
    Obrigado pela sua inscrição.<br/>

    <?php
    if($candidato['Candidato']['lista_espera'] == 0){
        ?>

        <? if (!empty($faturas)) { ?>
		Clique no link abaixo para fazer o pagamento da inscrição.<br/>
        A baixa da fatura no sistema pode se dar em até 4 dias úteis.<br/>
        Lembre-se de guardar o comprovante de pagamento e, caso, seu pagamento não tenha sido confirmado, entre em contato com o cursinho.<br/>
		<?php } ?>
		
		<br/>
        <span style="font-size: 20px;">
            Entre com seu número de CPF em login e sua senha para acompanhar o status de sua inscrição
        </span>

    <?php
    }else{
    ?>

        Obrigado por sua inscrição. Você está na lista de espera. No dia 26/07 entre novamente no sistema com seu número de CPF em login e sua senha para ver se houve vagas e se você pode imprimir o boleto de pagamento e finalizar sua inscrição. 

    <?php
    }
    ?>
</p>

<br/>

<p>
    <h4>Seu número de inscrição: <?php echo $candidato['Candidato']['numero_inscricao']; ?> </h4><br/>

    <?php
    if($processo_seletivo['ConfiguracaoProcessoSeletivo']['tem_questionario'] == 1){
        ?>
        Verifique no site do cursinho a sua data para preechimento do questinoário sócio-acadêmico, com base no seu número de inscrição.
    <?php
    }
    ?>

</p>


<?php
if($candidato['Candidato']['lista_espera'] == 0){
    ?>

    <br/>

    <?php foreach($faturas as $fatura): ?>
    <a style="font-weight: bold;font-size: 15px;background: #FFBD76;padding: 10px;" href="http://www.cursinho.ufscar.br/boleto/?codigo=<?php echo $codigo_faturas[$fatura['Fatura']['id']] ?>">
        Valor: R$<?php echo $fatura['Fatura']['valor'] ?>, Vencimento: <?php echo date('d/m/Y', strtotime($fatura['Fatura']['data_vencimento']) ) ?>
    </a>
        <br/>
        <br/>
        <br/>
    <?php endforeach; ?>

<?php
}
?>

