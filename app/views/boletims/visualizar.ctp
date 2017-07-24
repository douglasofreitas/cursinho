<label class="formulario">Nome do estudante:</label>
<?php  
echo $html->link($nome, '/estudantes/visualizar_ficha/'.$estudante['Estudante']['estudante_id'].'/'.date('Y'), array('style' => 'color:#0000CC'));
?>
<br/>
<label class="formulario">Número de notas:</label>
<?php echo count($this->data); ?>
<br/>
<table>
<?php foreach($this->data as $boletim):?>
	<tr>
		<td style="width: 400px">
			<hr/>
			<br/>
			<label  class="formulario">Nome da atividade:</label>
			<?php echo $boletim['Boletim']['nome_atividade'] ?>
			<br/>
			<label class="formulario">Descrição:</label>
			<?php echo $boletim['Boletim']['descricao'] ?>
			<br/>
			<label class="formulario">Data da atividade:</label>
			<?php echo $boletim['Boletim']['data_atividade_form'] ?>
			<br/>
			<label class="formulario">Nota:</label>
			<?php echo $boletim['Boletim']['nota'] ?>
			<br/>
			<br/>
			<br/>
		</td>
		<td>
			<?php echo $html->link('Editar Nota', '/boletims/editar_direto/' . 
					$boletim['Boletim']['boletim_id']) ?>
			<br/><br/>
			<?php echo $html->link('Remover Nota', '/boletims/remover/' . 
					$boletim['Boletim']['boletim_id']) ?>
		</td>
	</tr>	

<?php endforeach;?>
</table>
