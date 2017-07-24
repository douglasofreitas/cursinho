<?php
class Unidade extends AppModel {

    var $useTable = 'unidades';
    var $primaryKey = 'id';

    var $name = 'Unidade';

    var $validate = array(
        'nome'              => array('rule' => 'notEmpty',
            'message' => 'Nome obrigatório')
    );

    var $hasMany = array(
        'Turma' => array('className' => 'turma',
            'foreignKey' => 'unidade_id'),
        'Candidato' => array('className' => 'Candidato',
            'foreignKey' => 'unidade_id'),
        'Sala' => array('className' => 'Sala',
            'foreignKey' => 'unidade_id')
    );

    function getSelectForm($todos = false)
    {
        $array_unidade = array();
        $unidades = $this->find('all');

        foreach($unidades as $u){
            if(!$todos){
                if($u['Unidade']['ativo'] == 1)
                    $array_unidade[$u['Unidade']['id']] = $u['Unidade']['nome'];
            }else{
                $array_unidade[$u['Unidade']['id']] = $u['Unidade']['nome'];
            }
        }
        return $array_unidade;
    }

}
?>
