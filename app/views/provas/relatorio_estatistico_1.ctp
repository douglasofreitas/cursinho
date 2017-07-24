<br /><div align="right"><h3><?php echo $html->link('Exportar para Excel', '/provas/exportar_rel_est_1/'.$prova_ano.'/'.'excel', array('style' => 'color:#0000CC')); ?></h3></div>
<br /><div align="right"><h3><?php echo $html->link('Exportar para PDF', '/provas/exportar_rel_est_1/'.$prova_ano.'/'.'pdf', array('style' => 'color:#0000CC')); ?></h3></div>
<br />
<h3>Prova: <?php echo $prova_ano;?></h3>
<br /> 
<h3>Número de Questões: <?php echo $num_questoes;?></h3>
<br/>
<hr/>
<br />
<br />
<h3>Turma de 1 ano: <?php echo $tabela_questao_estatistica['Turma1ano']['total'];?> candidatos</h3>
<br />
<h3>Turma de 2 anos: <?php echo $tabela_questao_estatistica['Turma2ano']['total'];?> candidatos</h3>
<br />
<h3>Sem Turma: <?php echo $tabela_questao_estatistica['semTurma']['total'];?> candidatos</h3>
<br/>
<hr/>
<br />
<br />
<table class="listagem">
	<tr class="listagem_header">
		<td rowspan="2">Nº Questão</td>
		<td rowspan="2">Alt. correta</td>
		<td rowspan="2" style="width:240px">Habilidade Avaliada</td>
		<td colspan="2">% Erro</td>
		<td colspan="2">alt. errada mais assinalada(%)</td>
	</tr>
	<tr class="listagem_header">
		<td>T. 1 ano</td>
		<td>T. 2 anos</td>
		<td>T. 1 ano</td>
		<td>T. 2 anos</td>
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

		if($tabela_questao_estatistica[$i]['anulada'] == 1)
		{
			echo '<td width="80px">';
			echo $i;
			echo '</td><td >anulada</td><td >-</td><td >-</td><td >-</td><td >-</td><td >-</td>';
		}
		else
		{
			echo '<td >';
			echo $i;
			echo '</td>';

			echo '<td >';
			echo $tabela_questao_estatistica[$i]['alteranativa_correta'];
			echo '</td>';

			echo '<td >';
			echo $tabela_questao_estatistica[$i]['habilidade_avaliada'];
			echo '</td>';

			echo '<td >';
			if($tabela_questao_estatistica['Turma1ano']['total'] == 0)
			{
				echo '0';
			}
			else
			{
				$string = $tabela_questao_estatistica[$i]['erro_1_ano']/ $tabela_questao_estatistica['Turma1ano']['total'] *100;
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

			echo '<td >';
			if($tabela_questao_estatistica['Turma2ano']['total'] == 0)
			{
				echo '0';
			}
			else
			{
				$string = $tabela_questao_estatistica[$i]['erro_2_ano']/ $tabela_questao_estatistica['Turma2ano']['total'] *100;
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

			echo '<td >';
			if($tabela_questao_estatistica['Turma1ano']['total'] == 0)
			{
				echo '0';
			}
			else
			{
				$string = $count_alt_errada['turma_1_ano'][$i]['mais_marcada'].' - '.($count_alt_errada['turma_1_ano'][$i][$count_alt_errada['turma_1_ano'][$i]['mais_marcada']]/ $tabela_questao_estatistica['Turma1ano']['total'] *100);
				if(strlen($string)>9)
				{
					echo substr($string, 0, 9);
				}
				else
				{
					echo $string;
				}
			}
			echo '</td>';

			echo '<td >';
			if($tabela_questao_estatistica['Turma2ano']['total'] == 0)
			{
				echo '0';
			}
			else
			{
				$string = $count_alt_errada['turma_2_ano'][$i]['mais_marcada'].' - '.($count_alt_errada['turma_2_ano'][$i][$count_alt_errada['turma_2_ano'][$i]['mais_marcada']]/ $tabela_questao_estatistica['Turma2ano']['total'] *100);
				if(strlen($string)>9)
				{
					echo substr($string, 0, 9);
				}
				else
				{
					echo $string;
				}
			}
			echo '</td>';
		}

		echo '</tr>';
	}

	?>
</table>
</table>

<br />
