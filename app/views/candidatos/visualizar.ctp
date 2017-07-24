
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


<h3>
	<?php echo $html->link('Ir para o questionário', '/questao_questionarios/preencher/' .
		$candidato['Candidato']['numero_inscricao'] . '/' . $candidato['Candidato']['ano'] . '/1') ?>
	<span style="float: right">
		<?php
		echo $html->link(
		    $html->image('icon/page_white_edit.png', array("alt" => "Editar ficha de inscrição", 'class' => 'tipHoverBottom', 'title' => 'Editar ficha de inscrição')),
		    '/candidatos/alterar/' .$candidato['Candidato']['numero_inscricao'] . '/' . $candidato['Candidato']['ano'],
		    array('escape' => false)
		);
		echo $html->link(
		    $html->image('icon/pdf.gif', array("alt" => "Gerar PDF da ficha de inscrição", 'class' => 'tipHoverBottom', 'title' => 'Gerar PDF da ficha de inscrição')),
		    '/candidatos/gerar_pdf/'.$candidato['Candidato']['numero_inscricao'] . '/' . $candidato['Candidato']['ano'],
		    array('escape' => false, 'target' => '_blank')
		);
		?>
	</span>
</h3>
<?php
if(count($candidato['Estudante']) > 0){
    echo '<br/>';
    foreach($candidato['Estudante'] as $estudante){
        echo $html->link(
            'Visualizar ficha do estudante deste candidato ( Ano letivo: '.$estudante['ano_letivo'].', código '.$estudante['estudante_id'].')',
            '/estudantes/visualizar_ficha/'.$estudante['estudante_id'],
            null
        ).'<br/><br/>';
    }
    echo '<br/>';
}
?>
<br/>
<label class="formulario">Número de inscrição:</label>
<?php echo $candidato['Candidato']['numero_inscricao'] ?>
<br/>
<label class="formulario">Ano:</label>
<?php echo $candidato['Candidato']['ano'] ?>
<br/>
<label class="formulario">Nome:</label>
<?php echo $candidato['Candidato']['nome'] ?>
<br/>
<label class="formulario">E-mail:</label>
<?php echo $candidato['Candidato']['email'] ?>
<br/>
<label class="formulario">RG:</label>
<?php echo $candidato['Candidato']['rg'] ?>
<br/>
<label class="formulario">Orgão Emissor:</label>
<?php echo $candidato['Candidato']['orgao_emissor_rg'] ?>
<br/>
<label class="formulario">CPF:</label>
<?php echo $candidato['Candidato']['cpf'] ?>
<br/>
<label class="formulario">Nome da Mãe:</label>
<?php echo $candidato['Candidato']['nome_mae'] ?>
<br/>
<label class="formulario">Nome do Pai:</label>
<?php echo $candidato['Candidato']['nome_pai'] ?>
<br/>
<label class="formulario">Logradouro:</label>
<?php echo $candidato['Candidato']['endereco'] ?>
<br/>
<label class="formulario">Número:</label>
<?php echo $candidato['Candidato']['numero'] ?>
<br/>
<label class="formulario">Complemento:</label>
<?php echo $candidato['Candidato']['complemento'] ?>
<br/>
<label class="formulario">Bairro:</label>
<?php echo $candidato['Candidato']['bairro'] ?>
<br/>
<label class="formulario">Cidade:</label>
<?php echo $candidato['Cidade']['nome'] . ' - ' . $candidato['Cidade']['estado_id'] ?>
<br/>
<label class="formulario">CEP:</label>
<?php echo $candidato['Candidato']['cep'] ?>
<br/>
<label class="formulario">Telefone Residencial:</label>
<?php echo $candidato['Candidato']['telefone_residencial'] ?>
<br/>
<label class="formulario">Outro Telefone:</label>
<?php echo $candidato['Candidato']['telefone_outro'] ?>
<br/>
<label class="formulario">Ano de conclusão do Ensino Medio</label>
<?php echo $candidato['Candidato']['ano_conclusao_ensino_medio'] ?>
<br/>
<label class="formulario">Data para o preenchimento de Questionário</label>
<?php 
if(!empty($candidato['Candidato']['data_preenchimento_questionario']))
echo date('d/m/Y', strtotime($candidato['Candidato']['data_preenchimento_questionario'])); 
?>
<br/>
<label class="formulario">Data para o preenchimento de Prova</label>
<?php 
if(!empty($candidato['Candidato']['data_preenchimento_prova']))
echo date('d/m/Y', strtotime($candidato['Candidato']['data_preenchimento_prova']));
?>
<br/>
<label class="formulario">Unidade:</label>
<?php  
if(!empty($candidato['Unidade']['nome']))
    echo $candidato['Unidade']['nome'];
?>
<br/>
<label class="formulario">Turma:</label>
<?php  
	if ($candidato['Candidato']['turma'] == 1)
		echo $candidato['Candidato']['turma'] . ' ano';
	else if ($candidato['Candidato']['turma'] == 2)
		echo $candidato['Candidato']['turma'] . ' anos';
?>
<br/>


<label class="formulario">Cancelado:</label>
<?php
if ($candidato['Candidato']['cancelado'] == 1){
    echo '<span style="color:red">Sim</span> ';
    echo $html->link(
        'Reativar inscrição',
        '/candidatos/cancelar_inscricao/' . $candidato['Candidato']['candidato_id'],
        array('escape' => false),
        'Deseja realmente reativar a inscrição?'
    );
}else{
    echo '<span style="color:black">Não</span> ';
    if ($candidato['Candidato']['reativado'] == 1) echo ' (Reativado) ';
    echo $html->link(
        'Cancelar inscrição',
        '/candidatos/cancelar_inscricao/' . $candidato['Candidato']['candidato_id'],
        array('escape' => false),
        'Deseja realmente cancelar a inscrição?'
    );
}
?>
<br/>


<label class="formulario">Lista de espera:</label>
<?php
if ($candidato['Candidato']['lista_espera'] == 1){
    echo '<span style="color:red">Sim</span> ';
    echo $html->link(
        'Liberar candidato',
        '/candidatos/liberar_candidato/' . $candidato['Candidato']['candidato_id'],
        array('escape' => false),
        'Deseja realmente liberar este candidato?'
    );
}else{
    echo '<span style="color:black">Não</span>';
}
?>
<br/>

<br/>
<label class="formulario">Taxa de Inscrição Paga:</label>
<?php
if ($candidato['Candidato']['taxa_inscricao'] == 1) {
    echo '<span style="color:blue">Sim</span>';
}else{
    echo '<span style="color:red">Não</span>';
}

if(!empty($faturas)){
    ?>
    <br/>
    <br/>
    <div>
    <?php
    foreach($faturas as $fatura){
    ?>
    <fieldset style="width: 208px;">
        <legend>Dados da fatura</legend>
        <strong>Nosso Número:</strong> <?php echo $fatura['Fatura']['nossonumero'] ?><br/>
        <strong>Valor:</strong> <?php echo number_format($fatura['Fatura']['valor'], 2, ',', ''); ?><br/>
        <strong>Data de vencimento:</strong> <?php echo date('d/m/Y', strtotime($fatura['Fatura']['data_vencimento'])) ?>


        <strong>Status:</strong>
        <?php
        if($fatura['Fatura']['pago'] == 0){
            echo '<span style="color:red">Não pago</span>';
            echo '<br/>';
            echo $html->link(
                'Baixa Manual',
                '/faturas/baixa_manual/' . $fatura['Fatura']['id'],
                array('escape' => false),
                'Deseja realmente dar baixa manual desta fatura?'
            );
			echo ', ';
			echo $html->link(
                'Editar',
                '/faturas/editar/' . $fatura['Fatura']['id'],
                array('escape' => false, 'target' => '_blank')
            );

            echo '<br/><br/>';
            if(strtotime($fatura['Fatura']['data_vencimento']) < strtotime("today"))  {
                //vencida
                echo $html->link(
                    'Gerar segunda via',
                    '/faturas/gerar_segunda_via/' . $fatura['Fatura']['id'].'/'.$fatura['Fatura']['valor'],
                    array('escape' => false, 'style' => 'font-weight: bold;'),
                    'Confirma a geração da segunda via da fatura no valor de R$ '.number_format($fatura['Fatura']['valor'], 2, ',', '').'?'
                );
            }else{
                ?>
                <a target="_blank" style="font-weight: bold;" href="http://www.cursinho.ufscar.br/boleto/?codigo=<?php echo codificar($fatura['Fatura']['id']) ; ?>">
                    Visualizar fatura
                </a>
            <?php
            }


        }else{
            echo '<span style="color:blue">Pago</span>';
        }
        ?>
    </fieldset>
    <?php
    }
    ?>
    </div>
<?php
}

?>
<br/>
<label class="formulario">Pontuação Social:</label>
<?php echo $candidato['Candidato']['pontuacao_social'] ?>
<br/>
<label class="formulario">Pontuação Econômica:</label>
<?php printf("%.7f", $candidato['Candidato']['pontuacao_economica']); ?>
<br/>

<label class="formulario">Nota de Prova:</label>
<?php echo $candidato['Candidato']['nota_prova'] ?>
<br/>

<label class="formulario">Fase Eliminatória:</label>
<?php 
if($candidato['Candidato']['fase_eliminatoria_status'] == 1){
    echo '<span style="color:blue">Passou</span>';
}else{
    echo '<span style="color:red">Não Passou</span>';
}
?>
<br/>
<label class="formulario">Fase Classificatória:</label>
<?php 
if($candidato['Candidato']['fase_classificatoria_status'] == 1){
    echo '<span style="color:blue">Passou</span>';
}else{
    echo '<span style="color:red">Não Passou</span>';
}
?>
<br/>
<label class="formulario">Matriculado:</label>
<?php  if ($candidato['Candidato']['matriculado'] == 1) echo 'Sim'; else echo 'Não'; ?>
<br/>
<label class="formulario">Rematriculado</label>
<?php 

	if (empty($candidato['Candidato']['rematriculado']))
	{
		echo 'Não';
                //verifica se pode fazer rematrícula
//                if ($candidato['Candidato']['matriculado'] == 1)
//                    if ($candidato['Candidato']['turma'] == 2)
//                        if (count($candidato['Estudante']) < 2)
//                            echo ' (<a id="fazer_rematricula" href="#" style="color:blue">Fazer rematrícula</a>)';
	}
	else
	{
		echo 'Sim';

                //exibe nota de prova para rematrícula
                echo '<br/>';
                echo '<label class="formulario">Nota de prova de rematrícula:</label>';
                echo $candidato['Candidato']['prova_nota_rematriculado'];

	}
?>