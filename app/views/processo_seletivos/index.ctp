<h3>Abaixo você pode visualizar os processos seletivos existentes</h3>
<br/>
<h3><?php echo $html->link('Adicionar novo processo seletivo', '/processo_seletivos/adicionar_processo_seletivo'); ?></h3>
<br/>
<table class="listagem">
    <tr class="listagem_header">
    	<td style="width:60px; text-align:center;"><?php echo 'Ano / Sigla'; ?></td>
    	<td style="width:400px; text-align:left;"><?php echo 'Nome'; ?></td>
        <td style="width:auto; text-align:left;"><?php echo 'Salário Mínimo'; ?></td>
        <td style="width:auto; text-align:left;"><?php echo 'Valor da inscrição'; ?></td>
        <td style="width:auto; text-align:left;"><?php echo 'Inscrição online'; ?></td>
        <td style="width:auto; text-align:left;"><?php echo 'Data Limite Pagamento'; ?></td>
        <td style="width:51px; text-align:center;"><?php echo 'Ativo'; ?></td>
        <td style="width:51px; text-align:center;"><?php echo 'Ações'; ?></td>
    </tr>

	<?php $numero_linha = 0; ?>

    <?php foreach ($processos_seletivos as $processo_seletivo): ?>

    	<?php $numero_linha++ ?>

		<?php if ($numero_linha % 2 == 1): ?>
		<tr class="linha_impar">
		<?php else: ?>
		<tr class="linha_par">
		<?php endif; ?>
			<td style="text-align:center">
                <?php echo $processo_seletivo['ProcessoSeletivo']['ano']; ?>

            </td>
			<td><?php echo $processo_seletivo['ProcessoSeletivo']['nome'] ?></td>
            <td>R$ <?php echo number_format($processo_seletivo['ConfiguracaoProcessoSeletivo']['valor_salario_minimo'], 2, ',', '.') ?></td>
            <td>
				R$ 
				<?php 
				
				if($processo_seletivo['ConfiguracaoProcessoSeletivo']['valor_inscricao'] == 0){
					if(!empty($processo_seletivo['ConfiguracaoProcessoSeletivo']['forma_pagamento'])){
						$formas_pagamentos = json_decode($processo_seletivo['ConfiguracaoProcessoSeletivo']['forma_pagamento']);
						$valor = 0;
						foreach($formas_pagamentos as $forma_pagamento){
							$valor += intval($forma_pagamento->valor);
						}
						echo number_format($valor, 2, ',', '.');
					}
				}else{
					echo number_format($processo_seletivo['ConfiguracaoProcessoSeletivo']['valor_inscricao'], 2, ',', '.');
				}
				
				?>
			</td>

            <td>
                <?php
                if($processo_seletivo['ConfiguracaoProcessoSeletivo']['inscricao_online']){
                    echo date('d/m/Y', strtotime($processo_seletivo['ConfiguracaoProcessoSeletivo']['inscricao_online_inicio'])).' - ';
                    echo date('d/m/Y', strtotime($processo_seletivo['ConfiguracaoProcessoSeletivo']['inscricao_online_fim']));
                }else{
                    echo 'NÃO';
                }
                ?>
            </td>
            <td>
                <?php
                if(!empty($processo_seletivo['ConfiguracaoProcessoSeletivo']['data_limite_pagamento']))
                echo date('d/m/Y', strtotime($processo_seletivo['ConfiguracaoProcessoSeletivo']['data_limite_pagamento']));
                ?>
            </td>
            <td>
                <?php
                if($processo_seletivo['ConfiguracaoProcessoSeletivo']['ativo']){
                    echo 'SIM';
                }else{
                    echo 'NÃO';
                }
                ?>
            </td>
            <td style="text-align: center">
                <?php
                echo $html->link(
                    $html->image('icon/find.png', array("alt" => "Visualizar detalhes", 'class' => 'tipHoverBottom', 'title' => 'Visualizar detalhes')),
                    '/processo_seletivos/view/'.$processo_seletivo['ProcessoSeletivo']['processo_seletivo_id'],
                    array('escape' => false)
                );
                ?>
            </td>
   		</tr>
    <?php endforeach; ?>
</table>
