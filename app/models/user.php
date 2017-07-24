<?php
class User extends AppModel {

    var $useTable = 'user';
    var $primaryKey = 'id';

    var $name = 'User';
    var $validate = array(
        'id' => array('numeric'),
        'username' => array('notempty'),
        'password' => array('notempty'),
        'group_id' => array('numeric')
    );
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
        'Group' => array(
            'className' => 'Group',
            'foreignKey' => 'group_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Cidade' => array(
            'className' => 'Cidade',
            'foreignKey' => 'cidade'
        )
    );
    var $actsAs = array('Acl' => 'requester');

    function parentNode() {
        if (!$this->id && empty($this->data)) {
            return null;
        }
        $data = $this->data;
        if (empty($this->data)) {
            $data = $this->read();
        }
        if (!$data['User']['group_id']) {
            return null;
        } else {
            return array('Group' => array('id' => $data['User']['group_id']));
        }
    }

    /**
     * After save callback
     *
     * Update the aro for the user.
     *
     * @access public
     * @return void
     */
    function afterSave($created) {
        if (!$created) {
            $parent = $this->parentNode();
            $parent = $this->node($parent);
            $node = $this->node();
            $aro = $node[0];
            $aro['Aro']['parent_id'] = $parent[0]['Aro']['id'];
            $this->Aro->save($aro);
        }
    }

    function possuiUsername($cpf){
        $condicao = array('username' => $cpf);
        if ($this->find('count', array('conditions' => $condicao, 'recursive' => -1)) > 0)
            return true;
        else
            return false;
    }

    function createUserByCandidato($candidato){
        $user = array();
        $user['User']['nome'] = $candidato['Candidato']['nome'];
        $user['User']['username'] = $candidato['Candidato']['cpf'];
        $user['User']['password'] = $candidato['Candidato']['senha'];
        $user['User']['group_id'] = 3;

        if($this->save($user)){
            return true;
        }else{
            return false;
        }


    }
}
?>
