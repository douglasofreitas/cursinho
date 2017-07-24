<br /><div align="right"><h3><?php echo $html->link('Exportar para Excel', '/provas/exportar_rel_est_2/'.$prova_ano.'/'.'excel', array('style' => 'color:#0000CC')); ?></h3></div>
<br /><div align="right"><h3><?php echo $html->link('Exportar para PDF', '/provas/exportar_rel_est_2/'.$prova_ano.'/'.'pdf', array('style' => 'color:#0000CC')); ?></h3></div>
<br />
<h3>Prova: <?php echo $prova_ano;?></h3>
<br /> 
<h3>Número de Questões: <?php echo $num_questoes;?></h3>
<br />
<hr/>
<br/>
<table>
<tr>
<td>
<h3>Turma de 1 ano: <?php echo $tabela_questao_estatistica['Turma1ano']['total'];?> candidatos</h3>
<br />
<br />

<table class="listagem">
	<tr class="listagem_header">
		<td style="width:100px">Nº questão</th>
		<td style="width:100px">% erros</th>
		<td style="width:100px">total erros</th>
	</tr>
	<?php
	$string = '';
	for($i = 1; $i <= $num_questoes; $i++)
	{
		if ($i % 2 == 1)
		{
			echo '<tr class="linha_impar">';
		}
		else
		{
			echo '<tr class="linha_par">';
		}

		if($tabela_questao_estatistica['anulada'][$i] == 1)
		{
			echo '<td>';
			echo $i;
			echo '</td><td width="80px">anulada</td><td width="80px">anulada</td>';
		}
		else
		{
			echo '<td>';
			echo $i;
			echo '</td>';

			echo '<td>';
			if($tabela_questao_estatistica['Turma1ano']['total'] == 0)
			{
				echo '0';
			}
			else
			{
				$string = $tabela_questao_estatistica['Turma1ano'][$i]['erros']/$tabela_questao_estatistica['Turma1ano']['total']*100;
				if(strlen($string)>5)
				{
					echo substr($string, 0, 5);
				}
				else
				{
					echo $string;
				}
			}
			echo '</td>';

			echo '<td width="80px">';
			echo $tabela_questao_estatistica['Turma1ano'][$i]['erros'];
			echo '</td>';
		}

		echo '</tr>';
	}

	?>
</table>
</td>
<td style="width:80px"></td>
<td>
<h3>Turma de 2 anos: <?php echo $tabela_questao_estatistica['Turma2ano']['total'];?> candidatos</h3>
<br />
<br />
<table class="listagem">
	<tr class="listagem_header">
		<td style="width:100px">Nº questão</th>
		<td style="width:100px">% erros</th>
		<td style="width:100px">total erros</th>
	</tr>
	<?php

	for($i = 1; $i <= $num_questoes; $i++)
	{
		if ($i % 2 == 1)
		{
			echo '<tr class="linha_impar">';
		}
		else
		{
			echo '<tr class="linha_par">';
		}

		if($tabela_questao_estatistica['anulada'][$i])
		{
			echo '<td>';
			echo $i;
			echo '</td><td width="80px">anulada</td><td width="80px">anulada</td>';
		}
		else
		{
			echo '<td>';
			echo $i;
			echo '</td>';

			echo '<td>';
			if($tabela_questao_estatistica['Turma2ano']['total'] == 0)
			{
				echo '0';
			}
			else
			{
				$string = $tabela_questao_estatistica['Turma2ano'][$i]['erros']/$tabela_questao_estatistica['Turma2ano']['total']*100;
				if(strlen($string)>5)
				{
					echo substr($string, 0, 5);
				}
				else
				{
					echo $string;
				}
			}
			echo '</td>';

			echo '<td width="80px">';
			echo $tabela_questao_estatistica['Turma2ano'][$i]['erros'];
			echo '</td>';
		}

		echo '</tr>';
	}

	?>
</table>
</td>
</tr>
</table>
<br />
