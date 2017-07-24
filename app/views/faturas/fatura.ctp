<h3>Modifique os dados necessários</h3>
<br /><br /><br />
<?php


	echo $form->create('Fatura', array('url' => array('controller' => 'faturas', 'action' =>'editar/'.$this->data['Fatura']['id']), 'class' => 'formulario'));

	echo $form->hidden('fatura_id');
	echo $form->input('data_vencimento', array('type' => 'text', 'label' => 'Data de vencimento', 'value' => date('d/m/Y', strtotime($this->data['Fatura']['data_vencimento']) )));
	echo '<br/>';
	echo $form->input('valor', array('label' => 'Valor', 'value' => number_format($this->data['Fatura']['valor'], 2, ',', '')));
	echo '<br/>';

    echo $form->input('ano_ref', array('label' => 'Ano de referência', 'value' => $this->data['Fatura']['ano_ref']));
    echo '<br/>';
    echo $form->input('mes_ref', array('label' => 'Mês de referência', 'value' => $this->data['Fatura']['mes_ref'], 'options' => $meses_ano ));
    echo '<br/>';

	echo $form->end('Salvar');
?>
