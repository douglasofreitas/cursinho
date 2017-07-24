function renderChart(target, xaml, title, width, height)
{
	var vChart = new Visifire2('/Cursinho_v20/SL.Visifire.Charts.xap', title, width, height);
	
	vChart.setDataXml(xaml);
	vChart.render(target);
}