<?php 
	foreach ($cidades as $id => $nome)
	{
		if ($estado_id != 'SP')
		{
			$selected = $id;
			break;
		}
		else
		{
			if ($nome == 'SAO CARLOS')
			{
				$selected = $id;
				break;
			}
		}
	}

	echo $form->label('Cidade');

	echo $form->select('cidade', $cidades, $selected, array('name' => 'data[Candidato][cidade]') , $allowEmpty);
?>