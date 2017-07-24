<h3>Informe o processo seletivo.</h3>
<br />
<?php
echo $form->create('Estudante', array('action' => 'atrasados', 'class' => 'formulario'));

echo $form->input('ano', array('label' => 'Ano de vencimento')).' (Ex: '.date('Y').')';
echo '<br/>';

echo $form->input('mes', array('label' => 'Mês de vencimento')).' (01 a 12)';
echo '<br/>';

echo $form->end('Prosseguir');

if(!empty($estudantes)){
	?>
	
	<br/><br/>
	<table class="listagem"> 
		<tr class="listagem_header"> 
		  <td style="width:60px; text-align:center;">Ano</td>
			<td style="width:60px; text-align:center;">Número inscrição</td>
			<td style="width:60px; text-align:center;">Código estudante</td> 
			<td style="width:380px">Nome</td>
			<td style="width:111px">Unidade</td>
			
			<td style="width:60px">Pagamento</td>
			<td style="width:100px; text-align:center;">Ficha</td>
		</tr> 

	  <?php $numero_linha = 0; ?> 

		<?php foreach ($estudantes as $estudante): ?> 

		  <?php $numero_linha++ ?> 

		<?php if ($numero_linha % 2 == 1): ?> 
		<tr class="linha_impar"> 
		<?php else: ?> 
		<tr class="linha_par"> 
		<?php endif; ?> 
			<td style="text-align:center">
				<?php echo $estudante['Candidato']['ano'] ?>
			</td>
			<td style="text-align:center">
				<?php echo $estudante['Candidato']['numero_inscricao'] ?>
			</td>
			<td style="text-align:center"><?php echo $estudante['Estudante']['estudante_id'] ?></td>
			<td>
				<?php echo $estudante['Candidato']['nome'] ?>
			</td>
			<td style="text-align:center;">
				<?php
				if(!empty($estudante['Candidato']['unidade_id']))
					echo $unidades[$estudante['Candidato']['unidade_id']];
				else{
					//solicitar que seja alocado a unidade para o estudante, pelos dados do Candidato
					echo $html->link('Alocar', '/candidatos/alterar/'.$estudante['Candidato']['numero_inscricao'].'/'.$estudante['Candidato']['ano']);
				}
				?>
			</td>
			
			<td style="text-align:center;">
				Atrasado
			</td>
			<td style="text-align:center;">
			<?php
				echo $html->link(
					$html->image('icon/find.png', array("alt" => "Visualizar ficha do estudante", 'class' => 'tipHoverBottom', 'title' => 'Visualizar ficha do estudante')),
					'/estudantes/visualizar_ficha/' . $estudante['Estudante']['estudante_id'],
					array('escape' => false)
				);
			?>
			</td>
		</tr> 
		<?php endforeach; ?> 
	</table> 
	
	
	<?php
}else{
	echo '<br/><br/>Não há estudantes';
}

?>
