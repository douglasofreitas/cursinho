<?php
class MensalidadesController extends AppController {
    //var $scaffold;
    var $layout = 'tricolor_layout';
    var $pageTitle = 'estudantes';
    function editar_mensalidade($mensalidade_id = null){
        if(empty($mensalidade_id )){
            $this->redirect('/estudantes');
        }
        $this->set('content_title', 'Editar mensalidade'); 
        if(!empty($this->data)){
            //salvar mensalidade
            if($this->Mensalidade->save($this->data)){
                $this->Session->setFlash('Valor alterado');
            }else{
                $this->Session->setFlash('Valor foi alterado. Tente novamente');
            }
            $this->redirect('/estudantes/visualizar_mensalidades/'.$this->data['Mensalidade']['estudante_id']);
        }
        $mensalidade = $this->Mensalidade->read(null, $mensalidade_id);
        $this->set('mensalidade', $mensalidade);
    }
    function gerar_mensalidades_massa($ano_letivo, $dia_pagamento, $valor){
        //obter os estudantes do ano letivo selecionado
        $estudantes = $this->Mensalidade->Estudante->find('all', array('conditions' => array('Estudante.ano_letivo' => $ano_letivo)));
        $msg = '';
        foreach($estudantes as $estudante){
            if($this->Mensalidade->criar_mensalidades($estudante['Estudante']['estudante_id'], $dia_pagamento, $valor, $ano_letivo)){
                $this->Mensalidade->Estudante->salvar_valor_mensalidade($estudante['Estudante']['estudante_id'], $valor);
                //gravar id do estudante que teve mensalidade gerada corretamente.
                $msg .= ' '.$estudante['Estudante']['estudante_id'];
            }
        }
        $this->set('msg', $msg);
        $this->render('branco');
    }
    function beforeFilter() {
            parent::beforeFilter(); 
            $this->Auth->allow(array('gerar_mensalidades_massa'));
            $this->set('moduloAtual', 'estudantes');
    }
}
?>