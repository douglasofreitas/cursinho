<?php 
class ChartHelper extends Helper
{
	function createXamlChart($data = array(), $options = array())
	{		
		if (!isset($options['type']))
			$options['type'] = 'Point';

		if (!isset($options['width']))
			$options['width'] = '500';

		if (!isset($options['height']))
			$options['height'] = '300';

		if (!isset($options['borderThickness']))
			$options['borderThickness'] = '0.5';

		if (!isset($options['padding']))
			$options['padding'] = '0.3';

		if (!isset($options['title']))
			$options['title'] = 'Chart';

		if (!isset($options['axisXTitle']))
			$options['axisXTitle'] = 'Eixo X';

		if (!isset($options['axisYTitle']))
			$options['axisYTitle'] = 'Eixo Y';

		$xaml = '<vc:Chart xmlns:vc="clr-namespace:Visifire.Charts;assembly=SLVisifire.Charts" '; 
		$xaml .= 'Width="' . $options['width'] . '" Height="' . $options['height'] . '" ';
		$xaml .= 'BorderThickness="' . $options['borderThickness'] . '" Padding="' . $options['padding'] . '">';

		$xaml .= '<vc:Chart.Titles>';
		$xaml .= 	'<vc:Title Text="' . $options['title'] . '" FontSize="14"/>';
		$xaml .= '</vc:Chart.Titles>';

		$xaml .= '<vc:Chart.AxesX>';
		$xaml .= 	'<vc:Axis Title="' . $options['axisXTitle'] . '" TitleFontSize="14">';
		$xaml .=		'<vc:Axis.AxisLabels>';
		$xaml .=			'<vc:AxisLabels FontSize="12"/>';
		$xaml .=		'</vc:Axis.AxisLabels>';		
		$xaml .= 	'</vc:Axis>';
		$xaml .= '</vc:Chart.AxesX>';

		$xaml .= '<vc:Chart.AxesY>';
		$xaml .= 	'<vc:Axis Title="' . $options['axisYTitle'] . '" TitleFontSize="14">';
		$xaml .=		'<vc:Axis.AxisLabels>';
		$xaml .=			'<vc:AxisLabels FontSize="12"/>';
		$xaml .=		'</vc:Axis.AxisLabels>';	
		$xaml .= 	'</vc:Axis>';
		$xaml .= '</vc:Chart.AxesY>';

		$xaml .= '<vc:Chart.Series>';
		$xaml .= 	'<vc:DataSeries RenderAs="' . $options['type'] . '" LabelEnabled="True">';
		$xaml .= 		'<vc:DataSeries.DataPoints>';

							// data points
							foreach ($data as $values)
							{
								$xaml .= '<vc:DataPoint XValue="' . $values['XValue'] . '" YValue="' . $values['YValue'] . '"/>';
							}

		$xaml .= 		'</vc:DataSeries.DataPoints>';
		$xaml .= 	'</vc:DataSeries>';		
		$xaml .= '</vc:Chart.Series>';

		$xaml .= '</vc:Chart>';

		//return $this->output($xaml, true);
		return $xaml;
	}
}
?>
