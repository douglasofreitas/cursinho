<table>
  <tr>
    <th width="400px">

    	<label>Ano:</label>
		<?php echo $ano ?>
		<br/><br/>
		<label>Número de salas:</label>
		<?php echo count($this->data); ?>
		<br/>
		<br/>

	</th>
    <th>

    	<?php echo $html->link('Adicionar Sala', '/salas/inserir_direto/'.$ano) ?>

    </th>
  </tr>
</table>

<table>

<?php    
    if(count($this->data) == 0){
        echo 'Não há salas cadastradas';
    }else{

        foreach($this->data as $sala){
        ?>

            <tr>
                    <td style="width: 400px">
                            <hr/>
                            <br/>
                            <label  class="formulario">Número da sala:</label>
                            <?php echo $sala['Sala']['numero'] ?>
                            <br/>
                            <label class="formulario">Quantidade de salas:</label>
                            <?php echo $sala['Sala']['quantidade_vagas'] ?>
                            <br/>
                            <label class="formulario">Unidade:</label>
                            <?php echo strtoupper($sala['Unidade']['nome']) ?>
                            <br/>
                    </td>
                    <td>
                            <?php echo $html->link('Editar Sala', '/salas/editar_direto/' . 
                                            $sala['Sala']['sala_id']) ?>
                            <br/><br/>
                            <?php echo $html->link('Remover Sala', '/salas/remover/' . 
                                            $sala['Sala']['sala_id']) ?>
                    </td>
            </tr>	

        <?php 
        }
    }
?>

</table>
