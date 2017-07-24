<h3>Abaixo as unidades cadastradas.</h3>
<br/>
<h3><?php echo $html->link('Inserir nova Unidade', '/unidades/inserir'); ?></h3>
<br/>
<table class="listagem">
    <tr class="listagem_header">
    	<td style="width:400px; text-align:left;"><?php echo 'Nome'; ?></td>
        <td style="width:40px; text-align:left;"><?php echo 'Ativo'; ?></td>
        <td style="width:40px; text-align:left;"><?php echo 'Ações'; ?></td>
    </tr>

    <?php $numero_linha = 0; ?>

    <?php foreach ($unidades as $unidade): ?>

    	<?php $numero_linha++ ?>

		<?php if ($numero_linha % 2 == 1): ?>
		<tr class="linha_impar">
		<?php else: ?>
		<tr class="linha_par">
		<?php endif; ?>

			<td><?php echo $html->link($unidade['Unidade']['nome'], '/unidades/editar/'.$unidade['Unidade']['id']) ?></td>
            <td>
                <?php
                if($unidade['Unidade']['ativo']){
                    echo 'SIM';
                }else{
                    echo 'NÃO';
                }
                ?>
            </td>
            <td>
                <?php
                echo $html->link(
                    $html->image('icon/page_white_edit.png', array("alt" => "Editar", 'class' => 'tipHoverBottom', 'title' => 'Editar')),
                    '/unidades/editar/'.$unidade['Unidade']['id'],
                    array('escape' => false)
                );
                echo ' ';
                echo $html->link(
                    $html->image('icon/cross.png', array("alt" => "Remover", 'class' => 'tipHoverBottom', 'title' => 'Remover')),
                    '/unidades/remover/'.$unidade['Unidade']['id'],
                    array('escape' => false)
                );
                ?>
            </td>


   		</tr>
    <?php endforeach; ?>
</table>
