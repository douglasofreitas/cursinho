<?php if($exibe_conteudo): ?>

    <p>Veja na lista abaixo as inscrições realizadas e o status do pagamento da taxa de inscrição</p>
	<?php if(false): ?>
	
    <p>Caso não tenha preechido o questionário sócio-econômico, clique no link "preencher" no espaço de questionário</p>
    <p style="font-size: 18px">Pague a taxa de inscrição para que você possa participar do processo seletivo <br/>
        e fique atendo as informações no site sobre a data da prova</p>
		
	<?php endif; ?>
	<p style="font-size: 18px">Pague seu boleto até o vencimento. O não pagamento implicará desistência do Curso Pré-Vestibular. </p>
	

<br/>
    <p style="font-size: 24px;display:none">
    <strong>Data do questionário socioeconômico:</strong>  <a href="http://www.cursinho.ufscar.br/novo/wp-content/uploads/2013/04/manual_2013-20142.pdf" target="_blank">Clique aqui para ver a tabela de datas</a> <br/>

<strong>Data da prova: <?php echo date('d/m/Y', strtotime($processo['ConfiguracaoProcessoSeletivo']['data_prova'])); ?> </strong>
</p>
    <br/>
    <br/>

    <?php
    //verificar se há alguma mensagem para ser exibida
    if(!empty($aviso_email)){

        ?>
        <div style="background: #FFC5C5;padding: 10px;color: #000;width: 555px;">
            Não há seu e-mail no sistema. Insera seu e-mail abaixo para atualizar seu cadastro.
            <br/><br/>
            <?php
            echo $form->create('Candidato', array('action' => 'atualizar_email', 'class' => 'formulario'));
            echo '<label style="width: auto;">E-mail</label>';
            echo $form->input('email', array('label' => false, 'size' => '50'));
            echo $form->end('Atualizar cadastro');
            ?>
            <div style="clear:both"></div>
        </div>
        <br/>
        <br/>
        <?php
    }
    ?>


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

<table class="listagem">
    <tr class="listagem_header">
        <td style="width:auto; text-align:center;">Processo Seletivo</td>
        <td style="width:136px; text-align:center;">Número de inscrição</td>
        <td style="width:136px; text-align:center;">Dados da inscrição</td>

        <td style="width:100px; text-align:center;">Taxa de inscrição</td>
        <td style="width:auto; text-align:center;">Questionário sócio econômico</td>
        <td style="width:100px; text-align:center;">Fase Eliminatória</td>
        <td style="width:100px; text-align:center;">Fase Classificatória</td>

        <td style="width:411px; text-align:center;">Opções</td>
    </tr>
    <?php $numero_linha = 0; ?>

    <?php foreach ($candidatos as $candidato): ?>

        <?php $numero_linha++ ?>

        <?php if ($numero_linha % 2 == 1): ?>
            <tr class="linha_impar">
        <?php else: ?>
            <tr class="linha_par">
        <?php endif; ?>
        <td style="text-align:center"><?php echo $candidato['Candidato']['ano'] ?></td>
        <td style="text-align:center"><?php echo $candidato['Candidato']['numero_inscricao'] ?></td>
        <td style="text-align:center">
            <?php
            echo $html->link(
                '<span style="color:blue;font-weight: bold">VER DETALHES</span>',
                '/candidatos/edita_inscricao/' .$candidato['Candidato']['candidato_id'],
                array('escape' => false)
            );
            ?>
        </td>


        <td>
            <?php
            if($candidato['Candidato']['taxa_inscricao'] == 1){
                echo 'Pago';
            }else{
                echo 'Não pago';
            }
            ?>
        </td>

        <td>
            <?php
            if($candidato['Candidato']['questionario_vazio'] == 1){
                echo '<span style="color:red;font-weight: bold">Não preenchido</span> ';
                if( $candidato['Candidato']['processo_seletivo_id'] == $processo['ProcessoSeletivo']['processo_seletivo_id'] ){
                    echo $html->link('Preencher','/questao_questionarios/preencher_candidato/'.$candidato['Candidato']['candidato_id'].'/1', array('style' => 'font-weight: bold;font-size: 15px;background: #FFBD76;padding: 3px;'));
                }

            }else{
                echo '<span style="color:blue;font-weight: bold">Preenchido</span> ';
				//echo $candidato['Candidato']['processo_seletivo_id'].' == '.$processo['ProcessoSeletivo']['processo_seletivo_id'];
                if( $candidato['Candidato']['processo_seletivo_id'] == $processo['ProcessoSeletivo']['processo_seletivo_id'] ){
                    echo $html->link('Ver detalhes','/questao_questionarios/preencher_candidato/'.$candidato['Candidato']['candidato_id'].'/1', array('style' => 'float: right;'));
                }else{
                    echo $html->link(
                        $html->image('icon/pdf.gif', array("alt" => "PDF completo", 'class' => 'tipHoverBottom', 'title' => 'PDF completo')),
                        '/questao_questionarios/pdf_questionario/' .$candidato['Candidato']['numero_inscricao'] . '/' . $candidato['Candidato']['ano'] . '',
                        array('escape' => false));
                }
            }
            ?>
        </td>

        <td>
            <?php
            if($candidato['Candidato']['fase_eliminatoria_status'] == 1){
                echo 'Passou';
            }else{
                if ($candidato['Candidato']['cancelado'] == 1){
                    echo ' CANCELADO ';
                }else{
                    echo ' --- ';
                }

            }
            ?>
        </td>

        <td>
            <?php
            if($candidato['Candidato']['fase_classificatoria_status'] == 1){
                echo 'Passou';
            }else{
                if ($candidato['Candidato']['cancelado'] == 1){
                    echo ' CANCELADO ';
                }else{
                    echo ' --- ';
                }
            }
            ?>
        </td>

        <td style="text-align: left;">
            <?php
            if($candidato['Candidato']['cancelado'] == 1){

                echo $html->link(
                    'Ativar inscrição',
                    '/candidatos/ativar_inscricao/'.$candidato['Candidato']['candidato_id'],
                    array('style' => 'font-weight: bold;font-size: 15px;background: #FFBD76;padding: 3px;'),
                    'Ao ativar a incrição, você ficará na lista de espera para liberação de vaga. Deseja continuar?'
                );

            }else{




                //if(true)
                if($candidato['Candidato']['taxa_inscricao'] == 0 || $candidato['Candidato']['taxa_inscricao'] == 1){
                    //print_r($candidato);

                    if($candidato['Candidato']['lista_espera'] == 0){
                        foreach($faturas[$candidato['Candidato']['candidato_id']] as $item_fatura){
                            if(strtotime($item_fatura['Fatura']['data_vencimento']) >= strtotime(date('Y-m-d 00:00:00'))){
                                ?>
                                <a style="font-weight: bold;font-size: 15px;background: #FFBD76;padding: 3px;line-height: 24px;" href="http://www.cursinho.ufscar.br/boleto/?codigo=<?php echo codificar($item_fatura['Fatura']['id']) ; ?>">
                                    <?php if ($item_fatura['Fatura']['tag'] == 'matricula') echo 'MATRÍCULA => '; ?>
                                    Valor: R$<?php echo $item_fatura['Fatura']['valor'] ?>, Vencimento: <?php echo date('d/m/Y', strtotime($item_fatura['Fatura']['data_vencimento']) ) ?>
                                </a>
                                <br/>
                                <br/>
                                <?php
                            }else{
								if($item_fatura['Fatura']['pago'] == 1){
									?> 
									<span style="font-weight: bold;font-size: 15px;background: #CBDAFF;padding: 3px;line-height: 24px;">
									Fatura paga: Valor: R$<?php echo $item_fatura['Fatura']['valor'] ?>, Vencimento: <?php echo date('d/m/Y', strtotime($item_fatura['Fatura']['data_vencimento']) ) ?>
									</span>
									<?php 
								}else{
									?>
									
									<span style="font-weight: bold;font-size: 15px;background: #CBDAFF;padding: 3px;line-height: 24px;">
									Fatura vencida: Valor: R$<?php echo $item_fatura['Fatura']['valor'] ?>, Vencimento: <?php echo date('d/m/Y', strtotime($item_fatura['Fatura']['data_vencimento']) ) ?>
									</span>
                                    <br/>
									
									<?php 
									
									if( $candidato['Candidato']['processo_seletivo_id'] == $processo['ProcessoSeletivo']['processo_seletivo_id'] ){
										
										if(strtotime($processo['ConfiguracaoProcessoSeletivo']['data_limite_pagamento']) > strtotime(date('Y-m-d')))
											if(strtotime($processo['ConfiguracaoProcessoSeletivo']['inscricao_online_fim']) > strtotime(date('Y-m-d')))
										
												echo $html->link('Gerar segunda via da fatura',
													'/faturas/gerar_segunda_via/'.$item_fatura['Fatura']['id'],
													array('style' => 'font-weight: bold;font-size: 15px;background: #FFBD76;padding: 3px;')
												);

									}
								}
                            }
                        }
                    }else{
                        echo 'Lista de espera';
                    }

                    if(false) // FALSE
                    if(strtotime($faturas_vencimento[$candidato['Candidato']['candidato_id']]) >= strtotime(date('Y-m-d 00:00:00'))){
                        if($candidato['Candidato']['processo_seletivo_id'] == $processo['ProcessoSeletivo']['processo_seletivo_id']){

                            if($candidato['Candidato']['lista_espera'] == 0){
                                //verficar se ainda pode realizar pagamento
                                if(strtotime($processo['ConfiguracaoProcessoSeletivo']['data_limite_pagamento']) >= strtotime(date('Y-m-d'))){
                                ?>
                                    <a style="line-height: 24px;font-weight: bold;font-size: 15px;background: #FFBD76;padding: 3px;line-height: 24px;" href="http://www.cursinho.ufscar.br/boleto/?codigo=<?php echo codificar($faturas[$candidato['Candidato']['candidato_id']]) ; ?>">
                                        Fazer pagamento
                                    </a>
                                <?php
                                }
                            }else{
                                echo 'Lista de espera';
                            }
                        }
                    }else{

                        //não gera segunda via
                        /*
                        if($candidato['Candidato']['processo_seletivo_id'] == $processo['ProcessoSeletivo']['processo_seletivo_id'] | $candidato['Candidato']['processo_seletivo_id'] == 10){

                            if($cadidato['Candidato']['lista_espera'] == 0){
                                ?>
                                <a style="font-weight: bold;font-size: 15px;background: #FFBD76;padding: 3px;" href="http://www.cursinho.ufscar.br/boleto/?codigo=<?php echo codificar($faturas[$candidato['Candidato']['candidato_id']]) ; ?>">
                                    Fazer pagamento
                                </a>
                            <?php
                            }else{
                                echo 'Lista de espera';
                            }

                        }
                        */

                        if($candidato['Candidato']['processo_seletivo_id'] == $processo['ProcessoSeletivo']['processo_seletivo_id'] | $candidato['Candidato']['processo_seletivo_id'] == 11){
                            if($candidato['Candidato']['lista_espera'] == 0){
                                echo $html->link('Gerar segunda via da fatura',
                                    '/faturas/gerar_segunda_via/'.$faturas[$candidato['Candidato']['candidato_id']],
                                    array('style' => 'font-weight: bold;font-size: 15px;background: #FFBD76;padding: 3px;')
                                );
                            }else{
                                echo 'Lista de espera';
                            }
                        }



                    }
                }
                if($candidato['Candidato']['taxa_inscricao'] == 1)
					if($candidato['Candidato']['matriculado'] == 1){

						//echo $html->link(
						//	'Ver mensalidades',
						//	'/candidatos/mensalidades/'.$candidato['Candidato']['processo_seletivo_id'].'/'.$candidato['Candidato']['candidato_id'],
						//	array('style' => 'font-weight: bold;font-size: 15px;background: #FFBD76;padding: 3px;')
						//);

					}
            }
            ?>
        </td>


        </tr>
    <?php endforeach; ?>
</table>


<?php 
//Mensalidade de estudante
if(!empty($mensalidades)){

?>
<br/><br/>
<h3>Abaixo as faturas da mensalidade </h3>
<br/><br/>
	<table class="listagem"> 
		<tr class="listagem_header"> 
		    <td style="width:60px; text-align:center;">Ano</td> 
			
			<td style="width:80px">Valor</td>
			<td style="width:80px">Status</td>
			<td style="width:80px">Vencimento</td>
			<td style="width:200px; text-align:center;">Ações</td>
		</tr> 

	  <?php $numero_linha = 0; ?> 

		<?php foreach ($mensalidades as $fatura): ?>

            <?php if($fatura['Fatura']['ativo']): ?>

		<?php $numero_linha++ ?> 

		<?php if (date('Y', strtotime($fatura['Fatura']['data_vencimento'])) > 1900): ?>
			<?php if ($numero_linha % 2 == 1): ?> 
			<tr class="linha_impar"> 
			<?php else: ?> 
			<tr class="linha_par"> 
			<?php endif; ?> 
					  
					  <td style="text-align:center"><?php echo $fatura['Estudante']['ano_letivo'] ?></td>
					  
					  <td> 
						<?php echo number_format($fatura['Fatura']['valor'], 2, ',', ''); ?>
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
					<td>
						<?php echo date('d/m/Y', strtotime($fatura['Fatura']['data_vencimento'])); ?>
					</td>
					<td style="text-align:center;">
						<?php 
						if($fatura['Fatura']['pago'] == 0){
							?>
							<a style="font-weight: bold;font-size: 15px;background: #FFBD76;padding: 3px;" href="http://www.cursinho.ufscar.br/boleto/?codigo=<?php echo codificar( $fatura['Fatura']['id'] ) ; ?>">
								Fazer pagamento
							</a>
							<?php
						}
						?>
						
							
					</td>
			</tr>
			<?php endif; ?>
		<?php endif; ?>
		<?php endforeach; ?> 
	</table> 

<?php

}
?>




<?php endif; ?>