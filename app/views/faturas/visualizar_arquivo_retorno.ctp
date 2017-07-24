<h3>Nome do arquivo: <?php echo $arquivo_retorno['ArquivoRetorno']['nome'] ?> </h3><br/>
<h3>Data de Processamento: <?php echo date('d/m/Y', strtotime($arquivo_retorno['ArquivoRetorno']['created'])) ?> </h3><br/>
<h3>Faturas encontradas: <?php echo count($arquivo_retorno['ArquivoRetornoItem']) ?> </h3><br/>
<h3>Baixas realizadas: <?php echo $arquivo_retorno['ArquivoRetorno']['numero_faturas'] ?> </h3><br/>



<table class="listagem">
    <tr class="listagem_header">
        <td style="width:auto; text-align:center;">Nosso número</td>
        <td style="width:auto; text-align:center;">Data de pagamento</td>
        <td style="width:auto; text-align:center;">Nome do candidato</td>
        <td style="width:auto; text-align:center;">Ações</td>
    </tr>

    <?php $numero_linha = 0; ?>

    <?php foreach ($arquivo_retorno['ArquivoRetornoItem'] as $item): ?>

        <?php
        $numero_linha++;
        ?>

        <?php if ($numero_linha % 2 == 1): ?>
            <tr class="linha_impar">
        <?php else: ?>
            <tr class="linha_par">
        <?php endif; ?>
        <td style="text-align:center"><?php echo $item['nosso_numero'] ?></td>
        <td style="text-align:center"><?php echo date('d/m/Y', strtotime($item['data_pagamento'])); ?></td>
        <td style="text-align:center">
            <?php
            if(!empty($fatura_nosso_numero[$item['nosso_numero']]['Candidato']['nome'])){
				echo $fatura_nosso_numero[$item['nosso_numero']]['Candidato']['nome'];
			}else{
				echo $nome_estudantes[$fatura_nosso_numero[$item['nosso_numero']]['Estudante']['estudante_id']].'';
			}
            ?>
        </td>

        <td style="text-align:center;">
            <?php
            if(!empty($fatura_nosso_numero[$item['nosso_numero']]['Candidato']['nome'] )){
				echo $html->link(
					'Visualizar candidato',
					'/candidatos/visualizar/' . $fatura_nosso_numero[$item['nosso_numero']]['Candidato']['numero_inscricao'].'/'.$fatura_nosso_numero[$item['nosso_numero']]['Candidato']['ano'],
					array('escape' => false)
				);
			}else{
				echo $html->link(
					'Visualizar estudante',
					'/estudantes/visualizar_ficha/' . $fatura_nosso_numero[$item['nosso_numero']]['Estudante']['estudante_id'],
					array('escape' => false)
				);
			}
            ?>
        </td>
        </tr>
    <?php endforeach; ?>
</table>
