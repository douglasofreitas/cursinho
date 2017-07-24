<?php 
class Fatura extends AppModel {

	var $useTable = 'fatura';
	var $primaryKey = 'id';

	var $name = 'Fatura';

    var $belongsTo = array(
        'Candidato' => array('className' => 'Candidato',
            'foreignKey' => 'candidato_id'),
        'Estudante' => array('className' => 'Estudante',
            'foreignKey' => 'estudante_id'),
        );


    var $meses = array(
        1 => 'Janeiro',
        2 => 'Fevereiro',
        3 => 'Março',
        4 => 'Abril',
        5 => 'Maio' ,
        6 => 'Junho' ,
        7 => 'Julho' ,
        8 => 'Agosto' ,
        9 => 'Setembro',
        10 => 'Outubro' ,
        11 => 'Novembro' ,
        12 => 'Dezembro'
    );

    function getMeses(){
        return $this->meses;
    }

    function beforeSave($options) {
		if (empty($this->data['Fatura']['data_vencimento'])) {
	    		$this->data['Fatura']['data_vencimento'] = $this->geraDataVencimento();
		}
		
		if (!empty($this->data['Fatura']['data_pagamento'])) {
	    		$this->data['Fatura']['data_pagamento'] = $this->dateFormatBeforeSave($this->data['Fatura']['data_pagamento']);
		}
		
        if (empty($this->data['Fatura']['nossonumero'])) {
            $this->data['Fatura']['nossonumero'] = $this->geraNossoNumero();
        }

        //mes e ano de referência
        if(empty($this->data['Fatura']['mes_ref'])){
            $this->data['Fatura']['mes_ref'] = intval(date("m", strtotime($this->data['Fatura']['data_vencimento'])));
        }
        if(empty($this->data['Fatura']['ano_ref'])){
            $this->data['Fatura']['ano_ref'] = intval(date("Y", strtotime($this->data['Fatura']['data_vencimento'])));
        }

        return true;
    }
	
	public function valorFormatBeforeSave($valor) {
        return str_replace(',', '.', $valor);
    }
	
	public function dateFormatBeforeSave($dateString) {

		if( strpos($dateString, '/') !== false ){
            list($d, $m, $y) = preg_split('/\//', $dateString);
            $dateString = sprintf('%4d%02d%02d', $y, $m, $d);
            return date('Y-m-d', strtotime($dateString));
		}else{
            return $dateString;
		}        
    }

    function geraDataVencimento(){
        return date('Y-m-d', strtotime('+5 day'));
    }

    function geraNossoNumero(){
        //obtem o último nosso número gerado.
        $ult_numero = $this->find('first', array('conditions' => array(), 'fields' => 'MAX(Fatura.nossonumero) AS nossonumero', 'recursive' => -1));
        if($ult_numero)
            return intval($ult_numero[0]['nossonumero'])+1;
        else{
            return 1;
        }
    }

    function existeFatura($id){
        $condicao = array('id' => $id);
        if ($this->find('count', array('conditions' => $condicao, 'recursive' => -1)) > 0)
            return true;
        else
            return false;
    }
    function existeFaturas($ids){
        $condicao = array('id' => $ids);
        if ($this->find('count', array('conditions' => $condicao, 'recursive' => -1)) > 0)
            return true;
        else
            return false;
    }

    function getByCadidatoId($candidato_id){
        $fatura = $this->find('all', array('conditions' => array('Fatura.candidato_id' => $candidato_id,'Fatura.ativo' => 1 ), 'recursive' => -1, 'order' => array('Fatura.id DESC')));
        if($fatura)
            return $fatura;
        else{
            return false;
        }
    }

    function baixa_automatica($nosso_numero, $data_pagamento){
        $fatura = $this->find('first', array('conditions' => array('Fatura.nossonumero' => $nosso_numero), 'recursive' => -1));
        if($fatura){
            $fatura['Fatura']['pago'] = 1;
			$fatura['Fatura']['ativo'] = 1;
            $fatura['Fatura']['baixa_automatica'] = 1;
            $fatura['Fatura']['data_baixa'] = $data_pagamento;
            $this->create();
            if($this->save($fatura)){

                //marcar a taxa de inscrição do candidato como paga
                $this->Candidato->baixa_taxa_inscricao($fatura['Fatura']['candidato_id']);

                return true;
            }else
                return false;
        }else
            return false;
    }
	
	
    function baixa_manual($fatura_id, $data_pagamento){
        $fatura = $this->find('first', array('conditions' => array('Fatura.id' => $fatura_id), 'recursive' => -1));
        if($fatura){
            $fatura['Fatura']['pago'] = 1;
            $fatura['Fatura']['baixa_automatica'] = 0;
            $fatura['Fatura']['data_baixa'] = date('Y-m-d');
			$fatura['Fatura']['data_pagamento'] = $data_pagamento;
            $this->create();
            if($this->save($fatura)){
				
				if(!empty($fatura['Fatura']['candidato_id'])){
					//marcar a taxa de inscrição do candidato como paga
					$this->Candidato->baixa_taxa_inscricao($fatura['Fatura']['candidato_id']);
				}
                return true;
            }else
                return false;
        }else
            return false;
    }
	
	function isentar($fatura_id){
        $fatura = $this->find('first', array('conditions' => array('Fatura.id' => $fatura_id), 'recursive' => -1));
        if($fatura){
            $fatura['Fatura']['pago'] = 1;
            $fatura['Fatura']['data_baixa'] = date('Y-m-d');
            $fatura['Fatura']['isento'] = 1;
			
            $this->create();
            if($this->save($fatura)){
				
				if(!empty($fatura['Fatura']['candidato_id'])){
					//marcar a taxa de inscrição do candidato como paga
					$this->Candidato->baixa_taxa_inscricao($fatura['Fatura']['candidato_id']);
				}
                return true;
            }else
                return false;
        }else
            return false;
    }

    function getFaturasProcesso($candidato_id, $processo_id){
        $fatura = $this->find('all', array('conditions' => array('Fatura.candidato_id' => $candidato_id,'Fatura.ativo' => 1, 'Fatura.processo_seletivo_id' => $processo_id ), 'recursive' => -1, 'order' => array('Fatura.data_vencimento ASC') ) );
        if($fatura)
            return $fatura;
        else{
            return false;
        }
    }
	
	function getMensalidadesEstudantes($estudantes_id){
		$fatura = $this->find('all', array('conditions' => array('Fatura.estudante_id' => $estudantes_id,'Fatura.ativo' => 1), 'recursive' => 0, 'order' => array('Fatura.data_vencimento ASC') ) );
        if($fatura)
            return $fatura;
        else{
            return false;
        }
	}
	
	//métodos referente a geração de fatiras para estudantes

    //possível saber o ano letivo atual
    function num_pendentes($estudante_id){
        $mensalidades = $this->find('all', array('conditions' => array('Fatura.estudante_id' => $estudante_id)));
        $pendentes = -1;

        if(count($mensalidades ) > 0){
            $pendentes = 0;

            foreach($mensalidades as $mens){


                if(date('Y', strtotime($mens['Fatura']['data_vencimento'])) < date('Y')){
                    if ($mens['Fatura']['pago'] == 0){
                        $pendentes++;
                    }
                }else{

                    //echo date('m', strtotime($mens['Fatura']['data_vencimento'])) .'-'. date('m').'<br>';

                    if( date('m', strtotime($mens['Fatura']['data_vencimento'])) <= date('m') ){
                        if ($mens['Fatura']['pago'] == 0){
                            $pendentes++;
                        }
                    }
                }
            }
        }

        //echo "Pendente:".$pendentes.'<br/>';

        return $pendentes;
    }
	
	function criar_mensalidades($estudante_id, $info)
	{
		//verificar se já foi criado as mensalidades do ano
		$meses_bloq = array();
		$mensalidades = $this->find('all', array('conditions' => array('Fatura.estudante_id' => $estudante_id)));
		$cria_mens = true;
		if(count($mensalidades ) > 0){
			foreach($mensalidades as $mens){
				if(date('Y') == date('Y', strtotime($mens['Fatura']['data_vencimento'])) ){
					$meses_bloq[] = intval(date('m', strtotime($mens['Fatura']['data_vencimento'])));
				}
				
			}
		}

		
		$fatura['Fatura']['estudante_id'] = $estudante_id;
		for ($mes_item = $info['mes_inicio']; $mes_item < $info['mes_fim']; $mes_item++)
		{
			$pode_inserir = true;
			foreach($meses_bloq as $mes => $value){
				if($mes == $mes_item){
                    //$pode_inserir = false;
                }
			}
		
			if($pode_inserir){			
				$fatura['Fatura']['valor'] = $info['valor'];
				$fatura['Fatura']['data_vencimento'] = date('Y').'-'.$mes_item.'-'.$info['dia'];
				
				$this->create();
				$this->id = null;
				$this->save($fatura);
			}
		}
		return true;
		
	}
	
	
	function criar_mensalidade($estudante_id, $info)
	{
		//verificar se já foi criado as mensalidades do ano
		$meses_bloq = array();
		$mensalidades = $this->find('all', array('conditions' => array('Fatura.estudante_id' => $estudante_id)));
		$cria_mens = true;
		if(count($mensalidades ) > 0){
			foreach($mensalidades as $mens){
				if(date('Y') == date('Y', strtotime($mens['Fatura']['data_vencimento'])) ){
					$meses_bloq[] = intval(date('m', strtotime($mens['Fatura']['data_vencimento'])));
				}
			}
		}

        $fatura = array();
        $fatura['Fatura'] = array();
		$fatura['Fatura']['estudante_id'] = $estudante_id;
		
		$pode_inserir = true;
		foreach($meses_bloq as $mes => $value){
			if($mes == $info['mes']){
                //$pode_inserir = false;
            }

		}
	
		if($pode_inserir){
			$fatura['Fatura']['valor'] = $info['valor'];
			$fatura['Fatura']['data_vencimento'] = date('Y').'-'.$info['mes'].'-'.$info['dia'];

			$this->create();
			$this->id = null;

			if(!$this->save($fatura))
				return false;
		}else{
        }
	
		return true;
		
	}

}
?>
