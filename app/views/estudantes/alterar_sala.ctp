<h3>Alterar Sala</h3>
<br />
Estudante: <?php echo $estudante['Candidato']['nome'];?><br />
<br />
Ano Letivo: <?php echo date('Y');?><br />
<br />
Escolha na tabala abaixo a sala que deseja alocar o aluno:<br />
<br />
<table>
  <tr style="">
    <th>Sala</th>
    <th>Unidade</th>
    <th></th>

<?php
	foreach ($salas_disponiveis as $sala)
	{
		echo '<tr><td> '.$sala['Sala']['numero'].' </td><td> '.$sala['Sala']['unidade'].' </td> <td>';
		echo $html->link('Inserir na sala', '/estudantes/alterar_sala_direto/'.$estudante['Estudante']['estudante_id'].'/'.$sala['Sala']['sala_id']);
		echo '</td></tr>';
	}

?>
</table>