<h3>Por enquanto não é possível visualizar o gráfico de pontuação sócio-econômica diretamente através do sistema.</h3>
<br/>
<h3>Entretanto, você pode gerá-lo utilizando o Excel. Para isso, vá em <?php echo $html->link('Filtrar Candidatos', '/candidatos/filtrar/formulario'); ?> e selecione o perfil desejado, indicando que quer visualizar os pontos social e economico na exportação da lista de candidatos.</h3>
<br/><br/>

<br/><br/>
<h2><?php //echo $html->link('Exporta lista de pontuações sócio-econômica', '/candidatos/exportar_pontuacao_socio_economica') ?></h2>
<br />
<?php
	/*$contador = 1;
	$chartData = array();

	foreach ($candidatos as $candidato)
	{
		$chartData[$contador] = array('XValue' => $candidato['Candidato']['pontuacao_social'],
									  'YValue' => $candidato['Candidato']['pontuacao_economica']);

		$contador++;
	}
	$chartOptions = array('title' => 'Gráfico pontuação social x pontuação econômica',
						  'axisXTitle' => 'Pontuação Social',
						  'axisYTitle' => 'Pontuação Econômica',
						  'type' => 'point',
						  'width' => '760', 'height' => '560');*/
?>
