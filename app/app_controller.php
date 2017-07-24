<?php
class AppController extends Controller {
    var $components = array('Acl', 'Auth');
	var $uses = array('Group');
	
        var $url_logo_ufscar = '../webroot/img/logo_ufscar.jpg';
        
    function beforeFilter() {
        //-- Configure AuthComponent --
        
        $this->Auth->authorize = 'actions';
        //$this->Auth->authorize = 'model';
		$this->Auth->actionPath = 'controllers/';
		
		//carregando as frases de aviso do sistema de autenticação
		$this->Auth->loginError = 'Senha incorreta! tente novamente<br/><br/>';
		$this->Auth->authError = 'Você não possui privilégios. Favor fazer o login com permissão no sistema.<br/><br/>';
		
		//configurar os redirecionamentos
        $this->Auth->loginAction = array('controller' => 'users', 'action' => 'login');
        $this->Auth->logoutRedirect = array('controller' => 'pages', 'action' => 'home');
        $this->Auth->loginRedirect = array('controller' => 'pages', 'action' => 'home');
        
        //coletando atributos do usuário para o layout
        $this->set('login_group', $this->Group->getNome($this->Auth->user('group_id')));
        $this->set('login_nome', $this->Auth->user('nome'));
		
		$this->set('moduloAtual', '');


        //verifica se as configurações do sistema estão ativas
        if(empty($_SESSION['Configuracao'])){
            $_SESSION['Configuracao']['possui_questionario'] = true;
        }
		
    }
    
    function debugVar($obj){
            echo '<pre>';
            print_r($obj);
            echo '</pre>';
    }


    function codificar($string){
        if((isset($string)) && (is_string($string))){
            $enc_string = base64_encode($string);
            $enc_string = str_replace("=","",$enc_string);
            $enc_string = strrev($enc_string);
            $md5 = md5($string);
            $enc_string = substr($md5,0,3).$enc_string.substr($md5,-3);
        }else{
            $enc_string = "Parâmetro incorreto ou inexistente!";
        }
        return $enc_string;
    }
    function descodificar($string){
        if((isset($string)) && (is_string($string))){
            $ini = substr($string,0,3);
            $end = substr($string,-3);
            $des_string = substr($string,0,-3);
            $des_string = substr($des_string,3);
            $des_string = strrev($des_string);
            $des_string = base64_decode($des_string);
            $md5 = md5($des_string);
            $ver = substr($md5,0,3).substr($md5,-3);
            if($ver != $ini.$end){
                $des_string = "Erro na desencriptação!";
            }
        }else{
            $des_string = "Parâmetro incorreto ou inexistente!";
        }
        return $des_string;
    }


    //manipulação de valor
    function valorFormatBeforeSave($valor) {
        return str_replace(',', '.', $valor);
    }
    //manipulador de datas
    function dateFormatBeforeSave($dateString) {
        list($d, $m, $y) = preg_split('/\//', $dateString);
        $dateString = sprintf('%4d%02d%02d', $y, $m, $d);
        return date('Y-m-d', strtotime($dateString)); // Direction is from
    }


    /**
     * uploads files to the server
     * @params:
     *      $folder     = the folder to upload the files e.g. 'img/files'
     *      $formdata   = the array containing the form files
     *      $itemId     = id of the item (optional) will create a new sub folder
     * @return:
     *      will return an array with the success of each file upload
     */
    function uploadFiles($folder, $formdata, $itemId = null, $names = array(), $resize = false, $lado_max = 200, $filename_mini = null, $lado_max_mini = 100) {
        // setup dir names absolute and relative
        $folder_url = WWW_ROOT.$folder;
        $rel_url = $folder;

        // create the folder if it does not exist
        if(!is_dir($folder_url)) {
            mkdir($folder_url);
        }

        // if itemId is set create an item folder
        if($itemId) {
            // set new absolute folder
            $folder_url = WWW_ROOT.$folder.'/'.$itemId;
            // set new relative folder
            $rel_url = $folder.'/'.$itemId;
            // create directory
            if(!is_dir($folder_url)) {
                mkdir($folder_url);
            }
        }

        // list of permitted file types,
        $permitted = array('image/jpg', 'image/jpe', 'image/jpeg', 'image/gif', 'image/bmp', 'image/png' );

        // loop through and deal with the files
        foreach($formdata as $key => $file) {
            if(!empty($names[$key])) {
                $filename = $names[$key];
            }else{
                // replace spaces with underscores
                $filename = str_replace(' ', '_', $file['name']);
            }
            // assume filetype is false
            $typeOK = false;

            // check filetype is ok
            //if(in_array($file['type'], $permitted))
            //    $typeOK = true;
            $typeOK = true;


            // if file type ok upload the file
            if($typeOK) {
                // switch based on error code
                switch($file['error']) {
                    case 0:
                        $url = $folder_url.'/'.$filename;
                        $success = move_uploaded_file($file['tmp_name'], $url);
                        // if upload was successful
                        if($success) {
                            // save the url of the file
                            $result['urls'][] = $url;

                            //diminuir imagem
                            if($resize){
                                $this->Image->resize_img($url, $lado_max, $url) ;
                            }

                            //criar miniatura
                            if(!empty($filename_mini)){
                                $url_mini = $folder_url.'/'.$filename_mini;
                                $result['urls'][] = $url_mini;
                                $this->Image->resize_img($url, $lado_max_mini, $url_mini) ;

                                //facebook
                                $url_mini_facebook = $folder_url.'/'.str_replace('_mini', '_mini_facebook', $filename_mini);
                                $this->Image->resize_img($url, 130, $url_mini_facebook) ;
                            }


                        } else {
                            $result['errors'][] = "Error uploaded $filename. Please try again.";
                        }
                        break;
                    case 3:
                        // an error occured
                        $result['errors'][] = "Error uploading $filename. Please try again.";
                        break;
                    default:
                        // an error occured
                        $result['errors'][] = "System error uploading $filename. Contact webmaster.";
                        break;
                }
            } elseif($file['error'] == 4) {
                // no file was selected for upload
                $result['nofiles'][] = "No file Selected";
            } else {
                // unacceptable file type
                $result['errors'][] = "$filename cannot be uploaded. Acceptable file types: png, jpg, bmp, gif.";
            }
        }
        return $result;
    }

    function deleteFiles($folder, $itemId = null, $names = array()) {
        // setup dir name
        $folder_url = WWW_ROOT.$folder;

        // if itemId is set create an item folder
        if($itemId) {
            // set new absolute folder
            $folder_url = WWW_ROOT.$folder.'/'.$itemId;
        }

        // loop through and delete files
        foreach($names as $filename) {
            $url = $folder_url.'/'.$filename;
            if(file_exists($url)) unlink($url);
        }
        return true;
    }

}
?>