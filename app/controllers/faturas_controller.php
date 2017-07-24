<?php 
class FaturasController extends AppController {

	//var $scaffold;

	var $layout = 'tricolor_layout';
	var $pageTitle = 'coordenador';
	var $paginate;

	var $uses = array('Fatura');

	function index($tipo = 'candidato')
	{
		$this->set('content_title', 'Faturas');

        $param_get = '?';

        $conditions = array('Fatura.ativo' => 1);
        if($tipo == 'candidato')
            $conditions['Fatura.candidato_id > '] = 0;
        elseif($tipo == 'estudante')
            $conditions['Fatura.estudante_id > '] = 0;

        if (!empty($this->data)){
            //filtro de fatura
            if(!empty($this->data['Fatura']['ano']))
                $conditions['Candidato.ano'] = $this->data['Fatura']['ano'];
            if(!empty($this->data['Fatura']['numero_inscricao']))
                $conditions['Candidato.numero_inscricao'] = $this->data['Fatura']['numero_inscricao'];
            if(!empty($this->data['Fatura']['nome']))
                $conditions['Candidato.nome LIKE'] = '%'.$this->data['Fatura']['nome'].'%';
            if(!empty($this->data['Fatura']['valor']))
                $conditions['Fatura.valor'] = $this->data['Fatura']['valor'];
            if(!empty($this->data['Fatura']['nossonumero']))
                $conditions['Fatura.nossonumero LIKE '] = '%'.$this->data['Fatura']['nossonumero'].'%';
        }
        if(!empty($_GET)){
            if(!empty($_GET['ano']))
                $conditions['Candidato.ano'] = $_GET['ano'];
            if(!empty($_GET['numero_inscricao']))
                $conditions['Candidato.numero_inscricao'] = $_GET['numero_inscricao'];
            if(!empty($_GET['nome']))
                $conditions['Candidato.nome LIKE '] = '%'.$_GET['nome'].'%';
            if(!empty($_GET['valor']))
                $conditions['Fatura.valor'] = $_GET['valor'];
            if(!empty($_GET['nossonumero']))
                $conditions['Fatura.nossonumero LIKE '] = '%'.$_GET['nossonumero'].'%';


            foreach($_GET as $key => $value)
                if($key != 'url')
                    $param_get .= $key.'='.$value.'&';
            ;

        }

        $this->paginate = array('limit' => 100, 'order' => array('Fatura.id' => 'desc'));

        $faturas = $this->paginate('Fatura', $conditions);

        if($tipo == 'estudante'){
            $estudantes_id = array();
            if($faturas)
                foreach($faturas as $fat)
                    $estudantes_id[$fat['Fatura']['estudante_id']] = $fat['Fatura']['estudante_id'];
            //busca dados de estudantes
            $estudantes_faturas = $this->Fatura->Estudante->find('all', array('conditions' => array('Estudante.estudante_id' => $estudantes_id)));
            $candidatos = array();
            if($estudantes_faturas)
                foreach($estudantes_faturas as $est)
                    $candidatos[$est['Estudante']['estudante_id']] = $est['Candidato'];
            $this->set('candidatos', $candidatos);
        }

        $this->set('tipo', $tipo);
        $this->set('faturas', $faturas);
        $this->set('param_get', $param_get);
	}

	function baixa_manual($id){
        $fatura = $this->Fatura->read(null, $id);
        if(!empty($fatura)){

            if($fatura['Fatura']['pago'] == 0){
                $fatura['Fatura']['pago'] = 1;
                $this->Fatura->create();
                if($this->Fatura->save($fatura)){
                    $this->Session->setFlash('Baixa manual efetuada com sucesso');

                    //atualizar o status da taxa de inscrição do candidato
                    if(!empty($fatura['Fatura']['candidato_id'])){
                        $candidato = $this->Fatura->Candidato->read(null, $fatura['Fatura']['candidato_id']);
                        $candidato['Candidato']['taxa_inscricao'] = 1;
                        $this->Fatura->Candidato->create();
                        $this->Fatura->Candidato->save($candidato);
                    }
                }else{
                    $this->Session->setFlash('Erro ao dar baixa na fatura, tentar novamente');
                }
            }else{
                $this->Session->setFlash('Fatura já esta paga');
            }
        }
        if(!empty($fatura['Fatura']['candidato_id'])){
            $this->redirect('/faturas/index');
        }else{
            $this->redirect('/faturas/index/estudante');
        }

    }


    function editar($id){
        if(empty($id )){
            $this->redirect('/faturas/index/candidato');
        }

        $fatura = $this->Fatura->read(null, $id );

        $this->set('content_title', 'Editar fatura');
        if(!empty($this->data)){
            //salvar mensalidade

            $this->data['Fatura']['valor'] = $this->Fatura->valorFormatBeforeSave($this->data['Fatura']['valor']);
            $this->data['Fatura']['data_vencimento'] =  $this->Fatura->dateFormatBeforeSave($this->data['Fatura']['data_vencimento']);

            if($this->Fatura->save($this->data)){
                $this->Session->setFlash('Valor alterado');
            }else{
                $this->Session->setFlash('Valor foi alterado. Tente novamente');
            }

            if(!empty($fatura['Fatura']['candidato_id'])){
                $this->redirect('/faturas/index/candidato');
            }else{
                $this->redirect('/faturas/index/estudante');
            }

        }
        $this->data = $this->Fatura->read(null, $id);
        $this->render('fatura');
    }


    function arquivo_retorno(){
        $this->set('content_title', 'Gerenciamento de Boletos');

        //obter lista de arquivos de retorno
        $this->loadModel('ArquivoRetorno');


        if(!empty($this->data['Fatura']['arquivo'])){

            //gravar arquivo no sistema
            $count = 0;
            foreach ($this->data['Fatura']['arquivo'] as $arquivo){
                if(!empty($arquivo)) {
                    if($arquivo['error'] == 0){

                        $result = $this->uploadFiles('arquivo_retorno', array($arquivo), null, array($arquivo['name']));

                        //gravar registro do arquivo no banco
                        $arquivo_retorno = array();
                        $arquivo_retorno['ArquivoRetorno']['nome'] = $arquivo['name'];
                        $this->ArquivoRetorno->create();
                        if($this->ArquivoRetorno->save($arquivo_retorno)){
                            $arquivo_retorno_id = $this->ArquivoRetorno->getInsertId();

                            //fazer processamento do arquivo
                            $this->loadModel('ArquivoRetornoItem');
                            App::Import('Core','File');

                            $dir = new Folder('arquivo_retorno');
                            $file = new File($dir->pwd() . DS . $arquivo['name']);
                            $contents = $file->read();
                            $file->close();
                            $lines = preg_split('/\n|\r\n?/', $contents);
                            $count_baixas = 0;

                            foreach($lines as $linha){
                                $registro = explode(';', $linha);

                                if(!empty($registro[1])){
                                    list($d, $m, $y) = preg_split('/\//', $registro[1] );
                                    $dateString = sprintf('%4d%02d%02d', $y, $m, $d);
                                    $data_pagamento = date('Y-m-d', strtotime($dateString));

                                    $nosso_numero = intval(substr($registro[0], 12, 8));

                                    $valor = intval($registro[2]);

                                    //gravar registro no banco
                                    $arq_retorno_item = array();
                                    $arq_retorno_item['ArquivoRetornoItem']['nosso_numero'] = $nosso_numero;
                                    $arq_retorno_item['ArquivoRetornoItem']['data_pagamento'] = $data_pagamento;
                                    $arq_retorno_item['ArquivoRetornoItem']['valor'] = $valor;
                                    $arq_retorno_item['ArquivoRetornoItem']['arquivo_retorno_id'] = $arquivo_retorno_id;

                                    $this->ArquivoRetornoItem->create();
                                    if($this->ArquivoRetornoItem->save($arq_retorno_item)){
                                        //dar baixa na fatura do candidato
                                        if($this->Fatura->baixa_automatica($nosso_numero, $data_pagamento)){
                                            $count_baixas++;
                                        }
                                    }
                                }

                            }



                            //atualizar dados do arquivo de retorno;
                            $arq_retorno = $this->ArquivoRetorno->read(null, $arquivo_retorno_id);
                            $arq_retorno['ArquivoRetorno']['numero_faturas'] = $count_baixas;
                            $this->ArquivoRetorno->save($arq_retorno);
                        }
                    }
                }
            }

        }


        $arquivos = $this->ArquivoRetorno->find('all', array('order' => array('ArquivoRetorno.nome DESC') ) );
        $this->set('arquivos', $arquivos);
    }

    function visualizar(){

    }

    function visualizar_arquivo_retorno($arq_retorno_id){
        $this->set('content_title', 'Visualizar arquivo de retorno');

        $this->loadModel('ArquivoRetorno');
        $arquivo = $this->ArquivoRetorno->read(null, $arq_retorno_id);
        if($arquivo){

            //buscar nossos números
            $nosso_numero = array();

            foreach($arquivo['ArquivoRetornoItem'] as $item){
                $nosso_numero[] = $item['nosso_numero'];
            }

            //buscar faturas
            $faturas = $this->Fatura->find('all', array('coditions' => array('Fatura.nossonumero' => $nosso_numero ), 'fields' => array('Fatura.id', 'Fatura.nossonumero', 'Fatura.candidato_id', 'Fatura.estudante_id', 'Estudante.estudante_id', 'Estudante.candidato_id', 'Candidato.nome', 'Candidato.ano', 'Candidato.numero_inscricao')));
            $fatura_nosso_numero = array();
			
			$id_estudantes = array();
			
            if($faturas ){
                foreach($faturas as $fat){
                    $fatura_nosso_numero[$fat['Fatura']['nossonumero']] = $fat;
					if(!empty($fat['Estudante']['candidato_id'])){
						$id_estudantes[$fat['Estudante']['estudante_id']] = $fat['Estudante']['estudante_id'];
					}
                }
            }
			
			//buscar nomes de estudantes
			$this->loadModel('Estudante');
			$sql_nomes = $this->Estudante->find('all', array('conditions' => array('Estudante.estudante_id' => $id_estudantes), 'fields' => array('Estudante.estudante_id', 'Candidato.candidato_id', 'Candidato.nome'), 'recursive' => 0));
			$nome_estudantes = array();
			if($sql_nomes){
				foreach($sql_nomes as $candidato){
					$nome_estudantes[$candidato['Estudante']['estudante_id']] = $candidato['Candidato']['nome'];
				}
			}
			
            $this->set('fatura_nosso_numero', $fatura_nosso_numero);
			$this->set('nome_estudantes', $nome_estudantes);
            $this->set('arquivo_retorno', $arquivo);
        }else{
            $this->Session->setFlash('Arquivo de retorno não encontrado');
            $this->redirect('/faturas/arquivo_retorno');
        }
    }

    function gerar_segunda_via($fatura_id, $valor = 0){

        if(empty($_SESSION['Auth']['User']))
            $this->redirect('/candidatos/index');

        //desativar fatura atual
        $fatura = $this->Fatura->read(null, $fatura_id);

        $fatura['Fatura']['ativo'] =0;
        $this->Fatura->save($fatura);

        //criar nova fatura
        $this->Fatura->create();
        unset($fatura['Fatura']['id']);
        unset($fatura['Fatura']['created']);
        unset($fatura['Fatura']['modified']);
        unset($fatura['Fatura']['nossonumero']);
        $fatura['Fatura']['data_vencimento'] = date('Y-m-d', strtotime("+2 day"));

        $this->loadModel('ProcessoSeletivo');
        if(strtotime($this->ProcessoSeletivo->getDataLimitePagamentoAtual()) < strtotime('+5 days')){
            $fatura['Fatura']['data_vencimento'] = date('Y-m-d', strtotime($this->ProcessoSeletivo->getDataLimitePagamentoAtual()));
        }else{
            $fatura['Fatura']['data_vencimento'] = date('Y-m-d', strtotime('+5 days'));
        }

        if($valor > 0)
            $fatura['Fatura']['valor'] = $valor;
        else{
            //buscar pela inscrição
            if (false) // renova com o mesmo valor anterior

                if(!empty($fatura['Fatura']['candidato_id'])){
                    $this->loadModel('ProcessoSeletivo');
                    $processo = $this->ProcessoSeletivo->read(null,$fatura['Candidato']['processo_seletivo_id'] );
                    if($processo)
                        if($processo['ConfiguracaoProcessoSeletivo']['valor_inscricao']){
                            $fatura['Fatura']['valor'] = $processo['ConfiguracaoProcessoSeletivo']['valor_inscricao'];
                            $fatura['Fatura']['data_vencimento'] = date('Y-m-d', strtotime("now"));
                        }

                }
        }

        $fatura['Fatura']['ativo'] = 1;

        $this->Fatura->save($fatura);

        $this->Session->setFlash('Segunda via gerada com sucesso.');
        $this->redirect('/candidatos/visualizar/'.$fatura['Candidato']['numero_inscricao'].'/'.$fatura['Candidato']['ano']);
    }

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('gerar_segunda_via', 'imprimir_mensalidades');
        $this->set('moduloAtual', 'faturas');
        $this->set('meses_ano', $this->Fatura->meses );
    }


    function delete($fatura_id){

        if(empty($_SESSION['Auth']['User']))
            $this->redirect('/candidatos/index');

        //desativar fatura atual
        $fatura = $this->Fatura->read(null, $fatura_id);
        if($fatura){
            if($this->Fatura->delete($fatura_id)){
                $this->Session->setFlash('Fatura removida');
            }else{
                $this->Session->setFlash('Fatura não removida. Tente novamente');
            }
        }

        if(!empty($fatura['Fatura']['candidato_id'])){
            $this->redirect('/faturas/index');
        }else{
            $this->redirect('/faturas/index/estudante');
        }
    }

    function imprimir_mensalidades($estudante_id){

        $faturas = $this->Fatura->find('all', array('conditions' => array('Fatura.estudante_id' => $estudante_id)));

        $this->set('faturas', $faturas);

        $this->layout = false;

    }


}
?>
