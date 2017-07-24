<?php 
class ArquivoRetornoItem extends AppModel {

	var $useTable = 'arquivo_retorno_item';
	var $primaryKey = 'id';

	var $name = 'ArquivoRetornoItem';

    var $belongsTo = array('ArquivoRetorno' => array('className' => 'ArquivoRetorno',
        'foreignKey' => 'arquivo_retorno_id'));

    function beforeSave($options) {

        return true;
    }




}
?>
