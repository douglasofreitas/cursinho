<?php 
class ArquivoRetorno extends AppModel {

	var $useTable = 'arquivo_retorno';
	var $primaryKey = 'id';

	var $name = 'ArquivoRetorno';

    var $hasMany = array('ArquivoRetornoItem' => array('className' => 'ArquivoRetornoItem',
        'foreignKey' => 'arquivo_retorno_id'));

    function beforeSave($options) {

        return true;
    }




}
?>
