<h3>Total de candidatos encontrados: <?php echo $paginator->counter(array('format' => '%count%')); ?></h3>
<br/>
<!--
<h3><?php //echo $paginator->counter(array('format' => 'Página %page% de %pages%')); ?></h3>
<br/>
<h3><?php //echo $paginator->counter(array('format' => 'Mostrando registros de %start% até %end%')); ?></h3>
<br/>
-->
<h3>
	<?php //echo $html->link('Filtrar', '/candidatos/filtrar/formulario', array('style' => 'color: #0000CC;')); ?>
	<span style="float: left">
		<?php
		echo $html->link(
		    $html->image('icon/doc_excel_table.png', array("alt" => "Exportar dados", 'class' => 'tipHoverBottom', 'title' => 'Exportar dados')),
		    '/candidatos/montar_relatorio',
		    array('escape' => false)
		);
		?>
	</span>
</h3>
<br/>
<hr/>
<h3><?php echo $paginator->counter(array('format' => 'Total de páginas: %pages%')); ?></h3>
<br/>
<?php 
	echo $paginator->prev(' << Anterior ', null, null, null);
	echo $paginator->numbers();
	echo $paginator->next(' Próximo >> ', null, null, null);
?>
<table class="listagem">
    <tr class="listagem_header">
    	<td style="width:60px; text-align:center;"><?php echo $paginator->sort('Ano', 'ano'); ?></td>
        <td style="width:60px; text-align:center;"><?php echo $paginator->sort('Número', 'numero_inscricao'); ?></td>
        <td style="width:380px"><?php echo $paginator->sort('Nome', 'nome'); ?></td>
        <td style="width:auto"><?php echo $paginator->sort('E-mail', 'email'); ?></td>
        <td style="width:100px; text-align:center;">Taxa de inscrição</td>
        <td style="width:100px; text-align:center;">Inscrição</td>
        <td style="width:100px; text-align:center;"><?php echo $paginator->sort('Questionário', 'pontuacao_social'); ?></td>
        <td style="width:100px; text-align:center;"><?php echo $paginator->sort('Prova', 'nota_prova'); ?></td>
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
	        <td>
	        	<?php echo $candidato['Candidato']['nome'] ?>
	        </td>
            <td>
                <?php echo $candidato['Candidato']['email'] ?>
            </td>

            <td>
                <?php
                if ($candidato['Candidato']['taxa_inscricao'] == 1) {
                    echo '<span style="color:blue">Pago</span> ';

                    if ($candidato['Candidato']['cancelado'] == 1){
                        echo '<span style="color:gray">Cancelado</span>';
                    }

                }else{

                    if ($candidato['Candidato']['lista_espera'] == 1){
                        if ($candidato['Candidato']['cancelado'] == 1){
                            echo '<span style="color:gray">Cancelado</span>';
                        }else{
                            echo '<span style="color:#773D00">Lista de espera</span> ';
                        }
                    }
                    else
                        echo '<span style="color:red">Não pago</span>';
                }
                ?>
            </td>

			<td style="text-align:center">
	        	<?php 
					echo $html->link(
					    $html->image('icon/find.png', array("alt" => "Visualizar inscrição", 'class' => 'tipHoverBottom', 'title' => 'Visualizar inscrição')),
					    "/candidatos/visualizar/" . $candidato['Candidato']['numero_inscricao']. "/" . $candidato['Candidato']['ano'],
					    array('escape' => false)
					); 
	        	?>
	        </td>

	        <?php if ($candidato['Candidato']['questionario_vazio'] == '1'): ?>
	        	<td class="questionario_em_branco" style="text-align:center;">
		        	<?php
						echo $html->link(
						    $html->image('icon/cross.png', array("alt" => "Em branco", 'class' => 'tipHoverBottom', 'title' => 'Em branco')),
						    '/questao_questionarios/preencher/'.$candidato['Candidato']['numero_inscricao'].'/'.$candidato['Candidato']['ano'].'/1',
						    array('escape' => false)
						);
                    if($candidato['Candidato']['numero_inscricao'] == 1){
                        echo $html->link(
                            $html->image('icon/exclamation_octagon_fram.png', array("alt" => "Em branco", 'class' => 'tipHoverBottom', 'title' => 'Pendências')),
                            '/questao_questionarios/preencher/'.$candidato['Candidato']['numero_inscricao'].'/'.$candidato['Candidato']['ano'].'/5',
                            array('escape' => false)
                        );
                    }
		        	?>
	        	</td>
	        <?php else: ?>
	        	<td class="questionario_preenchido" style="text-align:center">
	        		<?php
						echo $html->link(
						    $html->image('icon/find.png', array("alt" => "Visualizar questionário", 'class' => 'tipHoverBottom', 'title' => 'Visualizar questionário')),
						    '/questao_questionarios/preencher/'.$candidato['Candidato']['numero_inscricao'].'/'.$candidato['Candidato']['ano'].'/1',
						    array('escape' => false)
						);
                    if($candidato['Candidato']['numero_inscricao'] == 1){
                        echo $html->link(
                            $html->image('icon/exclamation_octagon_fram.png', array("alt" => "Em branco", 'class' => 'tipHoverBottom', 'title' => 'Pendências')),
                            '/questao_questionarios/preencher/'.$candidato['Candidato']['numero_inscricao'].'/'.$candidato['Candidato']['ano'].'/5',
                            array('escape' => false)
                        );
                    }

                    if(!empty($questionario_pendente[$candidato['Candidato']['candidato_id']])){
                        echo $html->image('icon/exclamation_octagon_fram.png', array("alt" => "Falta a útlima página", 'class' => 'tipHoverBottom', 'title' => 'Falta a útlima página'));
                    }

	        		?>
	        	</td>
	        <?php endif; ?>

	        <?php if ($candidato['Candidato']['nota_prova'] == null or $candidato['Candidato']['nota_prova'] == ''): ?>
				<td class="questionario_em_branco" style="text-align:center">
					<?php 
						echo $html->link(
						    $html->image('icon/cross.png', array("alt" => "Em branco", 'class' => 'tipHoverBottom', 'title' => 'Em branco')),
						    //'/candidatos/preencher_prova/'.$candidato['Candidato']['numero_inscricao'].'/'.$candidato['Candidato']['ano'],
                            '#',
						    array('escape' => false)
						);
					?>
				</td>
			<?php else: ?>
				<td class="questionario_preenchido" style="text-align:center">
					<?php 
						echo $html->link(
						    $html->image('icon/find.png', array("alt" => "Visualizar prova", 'class' => 'tipHoverBottom', 'title' => 'Visualizar prova')),
						    //'/candidatos/visualizar_respostas_questao_prova/'.$candidato['Candidato']['numero_inscricao'].'/'.$candidato['Candidato']['ano'],
                            '#',
						    array('escape' => false)
						);
					?>
				</td>
			<?php endif; ?>
   		</tr>
    <?php endforeach; ?>
</table>
<br/>
<?php

	echo $paginator->prev(' << Anterior ', null, null, null);
	echo $paginator->numbers();
	echo $paginator->next(' Próximo >> ', null, null, null);
?>