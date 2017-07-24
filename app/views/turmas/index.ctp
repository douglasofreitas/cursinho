<h3>Abaixo as turmas cadastradas para cada ano.</h3>
<br/>
<table class="listagem">
    <tr class="listagem_header">
    	<td style="width:60px; text-align:center;"><?php echo 'Ano'; ?></td>
    	<td style="width:400px; text-align:left;"><?php echo 'Nome'; ?></td>
        <td style="width:auto; text-align:left;"><?php echo 'Unidade'; ?></td>
        <td style="width:100px; text-align:left;"><?php echo 'Vagas'; ?></td>
        <td style="width:40px; text-align:left;"><?php echo 'Ações'; ?></td>
    </tr>

    <?php $numero_linha = 0; ?>

    <?php foreach ($turmas as $turma): ?>

    	<?php $numero_linha++ ?>

		<?php if ($numero_linha % 2 == 1): ?>
		<tr class="linha_impar">
		<?php else: ?>
		<tr class="linha_par">
		<?php endif; ?>

			<td style="text-align:center"><?php echo $turma['Turma']['ano_letivo'] ?></td>
                        <td><?php echo $html->link($turma['Turma']['nome'], '/turmas/editar/'.$turma['Turma']['id']) ?></td>
                        <td style="text-align:left"><?php echo $array_unidades[$turma['Turma']['unidade_id']]['Unidade']['nome']; ?></td>
                        <td style="text-align:center"><?php echo $turma['Turma']['vagas'] ?></td>

                        <td>
                            <?php 

                            echo $html->link(
                                $html->image('icon/page_white_edit.png', array("alt" => "Editar", 'class' => 'tipHoverBottom', 'title' => 'Editar turma')),
                                '/turmas/editar/'.$turma['Turma']['id'],
                                array('escape' => false)
                            );
                            echo ' ';
                            echo $html->link(
                                $html->image('icon/cross.png', array("alt" => "Remover", 'class' => 'tipHoverBottom', 'title' => 'Remover turma')),
                                '/turmas/remover/'.$turma['Turma']['id'],
                                array('escape' => false)
                            );

                            ?>
                        </td>
   		</tr>
    <?php endforeach; ?>
</table>
<br/>
<h3><?php echo $html->link('Inserir nova turma', '/turmas/inserir'); ?></h3>