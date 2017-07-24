<label class="formulario">Nome do estudante:</label>
<?php echo $nome ?>
<br/>
<label class="formulario">Número de advertências:</label>
<?php echo count($this->data); ?>
<br/>
<?php echo $html->link('Cadastrar advertência', '/advertencias/inserir_direto/'.$estudante_id); ?>
<br/>
<table>
<?php foreach($this->data as $advertencia):?>
	<tr>
		<td style="width: 400px">
			<hr/>
			<br/>
			<label  class="formulario">Data da Advertência:</label>
			<?php echo $advertencia['Advertencia']['data_advertencia'] ?>
			<br/>
			<label class="formulario">Motivo:</label>
			<?php echo $advertencia['Advertencia']['motivo'] ?>
			<br/>
			<br/>
			<br/>
		</td>
		<td>
			<?php echo $html->link('Editar Advertência', '/advertencias/editar_direto/' . 
					$advertencia['Advertencia']['advertencia_id']) ?>
			<br/><br/>
			<?php echo $html->link('Remover Advertência', '/advertencias/remover/' . 
					$advertencia['Advertencia']['advertencia_id']) ?>
		</td>
	</tr>	

<?php endforeach;?>
</table>
