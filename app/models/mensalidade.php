<?php 
class Mensalidade extends AppModel {

	var $useTable = 'mensalidade';
	var $primaryKey = 'recibo_mensalidade_id';
	var $order = 'Mensalidade.data_pagamento ASC';
	var $name = 'Mensalidade';
	var $validate = array(
		'mensalidade_id' => array('numeric')
	);
	var $belongsTo = array('Estudante' => array('className' => 'Estudante',
												'foreignKey' => 'estudante_id'));
        var $meses = array(
            'Janeiro' => 1,
            'Fevereiro' => 2,
            'Março' => 3,
            'Abril' => 4,
            'Maio' => 5,
            'Junho' => 6,
            'Julho' => 7,
            'Agosto' => 8,
            'Setembro' => 9,
            'Outubro' => 10,
            'Novembro' => 11,
            'Dezembro' => 12
        );



        var $mes = array();

        function __construct($id = false, $table = null, $ds = null) {
            parent::__construct($id, $table, $ds);
            $this->mes[] = array('mes' => 'marco', 'nome_mes' => 'Março');
            $this->mes[] = array('mes' => 'abril', 'nome_mes' => 'Abril');
            $this->mes[] = array('mes' => 'maio', 'nome_mes' => 'Maio');
            $this->mes[] = array('mes' => 'junho', 'nome_mes' => 'Junho');
            $this->mes[] = array('mes' => 'julho', 'nome_mes' => 'Julho');
            $this->mes[] = array('mes' => 'agosto', 'nome_mes' => 'Agosto');
            $this->mes[] = array('mes' => 'setembro', 'nome_mes' => 'Setembro');
            $this->mes[] = array('mes' => 'outubro', 'nome_mes' => 'Outubro');
            $this->mes[] = array('mes' => 'novembro', 'nome_mes' => 'Novembro');
        }

	function criar_mensalidades($estudante_id, $dia, $valor, $ano)
	{
		//verificar se já foi criado as mensalidades do ano
                $mensalidades = $this->find('all', array('conditions' => array('Mensalidade.estudante_id' => $estudante_id)));
                $cria_mens = true;
                if(count($mensalidades ) > 0){
                    foreach($mensalidades as $mens){
                        $temp = split('-', $mens['Mensalidade']['data_pagamento']);
                        if($temp[0] == $ano ){
                            $cria_mens = false;
                        }
                    }
                }

                if($cria_mens){
                    $data['Mensalidade']['estudante_id'] = $estudante_id;
                    for ($i = 0; $i < count($this->mes); $i++)
                    {

                            $data['Mensalidade']['estudante_id'] = $estudante_id;
                            $data['Mensalidade']['mes'] = $this->mes[$i]['mes'];
                            $data['Mensalidade']['valor'] = $valor;
                            $data['Mensalidade']['data_pagamento'] = $ano.'-'.($i+3).'-'.$dia;
                            $this->id = null;
                            $this->save($data);
                    }
                    return true;
                }else{
                    return false;
                }
	}

        function obterIntervaloMeses(){
            $mes_inicio = $this->mes[0]['nome_mes'];
            $mes_final = $this->mes[count($this->mes)-1]['nome_mes'];
            return $mes_inicio.' a '.$mes_final;
        }

	function registrar_pagamento($estudante_id, $mes, $valor)
	{
		$mensalidade = $this->find('first', array('conditions' => array('Mensalidade.mes' => $mes, 'Mensalidade.estudante_id' => $estudante_id)));

		//$mensalidade['Mensalidade']['valor'] = $valor;
		$mensalidade['Mensalidade']['foi_pago'] = 1;

		$this->save($mensalidade);
	}

        // o $ano não usa mais, pois o ano_letivo é String, e com isso não é 
        //possível saber o ano letivo atual
        function num_pendentes($estudante_id, $ano){
            $mensalidades = $this->find('all', array('conditions' => array('Mensalidade.estudante_id' => $estudante_id)));
            $pendentes = -1;

            if(count($mensalidades ) > 0){
                $pendentes = 0;

                foreach($mensalidades as $mens){
                    $temp = split('-', $mens['Mensalidade']['data_pagamento']);

                    if(date('Y', strtotime($mens['Mensalidade']['data_pagamento'])) < date('Y')){
                        if ($mens['Mensalidade']['foi_pago'] == 0){
                            $pendentes++;
                        }
                    }else{
                        if(date('m', strtotime($mens['Mensalidade']['data_pagamento'])) < date('m') ){
                            if ($mens['Mensalidade']['foi_pago'] == 0){
                                $pendentes++;
                            }
                        }
                    }
                }
            }
            return $pendentes;
        }

	function beforeSave($options) {
//		if (!empty($this->data['Produto']['valor_form'])) {
//	    		$this->data['Produto']['valor'] = $this->valorFormatBeforeSave($this->data['Produto']['valor_form']);
//		}
//		if (!empty($this->data['Produto']['peso_form'])) {
//	    		$this->data['Produto']['peso'] = $this->valorFormatBeforeSave($this->data['Produto']['peso_form']);
//		}
		return true;
	}
//	
//	function valorFormatBeforeSave($valor) {
//		return str_replace(',', '.', $valor);
//	}	

	function afterFind($results) {
		foreach ($results as $key => $val) {
			if (isset($val['Mensalidade']['data_pagamento'])) {
				$results[$key]['Mensalidade']['data_pagamento_form'] = $this->dataFormatAfterFind($val['Mensalidade']['data_pagamento']);
			}
			if (isset($val['Mensalidade']['data_recebido'])) {
				$results[$key]['Mensalidade']['data_recebido_form'] = $this->dataFormatAfterFind($val['Mensalidade']['data_recebido']);
			}
		}
		return $results;
	}

	function valorFormatAfterFind($valor) {
		return number_format($valor, 2, ',', '.');
	}

        function dataFormatAfterFind($valor) {
            $temp = split('-', $valor);
            return $temp[2].'/'.$temp[1].'/'.$temp[0];
	}

}
?>
