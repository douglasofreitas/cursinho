<?php 
/**
 * Classe correspondente ao Sistema Geral
 */
class UsersController extends AppController {

	//var $scaffold;

	var $layout = 'tricolor_layout';
	var $pageTitle = 'home';
	var $paginate;

	var $helpers = array('Chart');

	function index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}
	function view($user_id = null) {
		if (!$user_id) {
			$this->Session->setFlash(__('Invalid User.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('user', $this->User->read(null, $user_id));
	}
	function add() {
		if (!empty($this->data)) {
			$this->User->create();
			if ($this->User->save($this->data)) {
				$this->Session->setFlash(__('The User has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The User could not be saved. Please, try again.', true));
			}
		}
		$users = $this->User->find('list');
		$groups = $this->User->Group->find('list');
		$this->set(compact('users', 'groups'));
	}
	function edit($user_id = null) {
		if (!$user_id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid User', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->User->save($this->data)) {
				$this->Session->setFlash(__('The User has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The User could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->User->read(null, $user_id);
		}
		$users = $this->User->find('list');
		$groups = $this->User->Group->find('list');
		$this->set(compact('users','groups'));
	}
	function delete($user_id = null) {
		if (!$user_id) {
			$this->Session->setFlash(__('Invalid id for User', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->User->del($user_id)) {
			$this->Session->setFlash(__('User deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}

	function login() {
		$this->set('content_title', 'Login');
		//Auth Magic
		if ($this->Session->read('Auth.User')) {
			$this->Session->setFlash('You are logged in!');
			$this->redirect('/candidatos/', null, false);
		}
	}

	function logout() {
		//Leave empty for now.
		$this->Session->setFlash('Até a próxima!');
		$this->redirect($this->Auth->logout());
	}

	function beforeFilter() {
		parent::beforeFilter(); 
		$this->Auth->allow('login', 'logout', 'muda_senha', 'esqueceu_senha');
		//$this->Auth->allow('*');
	}

	function initDB() {
		$group =& $this->User->Group;

		//permissões para Coordenadores
		$group->id = $this->User->Group->obterId('coordenador');     
		$this->Acl->allow($group, 'controllers');

		//permissões para Funcionários
		$group->id = $this->User->Group->obterId('estudante');
		$this->Acl->deny($group, 'controllers');        
    	$this->Acl->allow($group, 'controllers/candidatos/index');
        $this->Acl->allow($group, 'controllers/faturas/gerar_segunda_via');
        $this->Acl->allow($group, 'controllers/candidatos/atualizar_email');
        $this->Acl->allow($group, 'controllers/users/esqueceu_senha');
        $this->Acl->allow($group, 'controllers/candidatos/edita_inscricao');
        $this->Acl->allow($group, 'controllers/candidatos/mensalidades');
        $this->Acl->allow($group, 'controllers/candidatos/gerar_faturas');
        $this->Acl->allow($group, 'controllers/candidatos/ativar_inscricao');

	    /*

		//permissões para Comissão
		$group->id = $this->User->Group->obterId('comissao');
		$this->Acl->allow($group, 'controllers');

		//permissões para Docentes
		$group->id = $this->User->Group->obterId('docente');
		$this->Acl->allow($group, 'controllers');

	    */
        $this->render('branco');
	}	

	/*
	function login_manual(){

		if(!empty($this->data))
		{
			$this->User->create();

			if($this->User->existe_login($this->data['User']['username'], $this->data['User']['password']))
			{
				$pessoa = $this->User->obtem_pessoa_login($this->data['User']['username'], $this->data['User']['password']);
				$_SESSION['login'] =  $pessoa['User']['username'];
				$_SESSION['login_nome'] =  $pessoa['User']['nome'];
				$_SESSION['login_tipo_acesso'] =  $pessoa['User']['id'];

			}
			else
			{
				$this->Session->setFlash('Usuário ou Senha incorretos!');
			}
		}

		$this->redirect('/candidatos/index');
	}

	function fazer_logoff(){
		unset($_SESSION['login']);
		unset($_SESSION['login_nome']);
		unset($_SESSION['login_tipo_acesso']);

		$this->redirect('/pages/home');
	}
	*/

    function esqueceu_senha(){
        $this->set('content_title', 'Esqueceu a senha?');

        if (!empty($this->data)){
            //verifica cpf e e-mail do candidato
            $this->loadModel('Candidato');
            $candidato = $this->Candidato->find('first', array('conditions' => array('Candidato.cpf' => $this->data['User']['cpf'], 'Candidato.email' => $this->data['User']['email'])));

            if($candidato){
                $_SESSION['candidato_user'] = $candidato['Candidato'];
                $this->redirect('/users/muda_senha/');
            }else{
                $this->Session->setFlash(__('CPF e e-mail não encontrados. Tente novamente ou entre em contato com o cursinho', true));
            }
        }
    }

    function muda_senha(){
        $candidato_simples = $_SESSION['candidato_user'];
        $this->set('content_title', 'Mudar senha');

        if(empty($candidato_simples['cpf'])){
            $this->redirect('/users/esqueceu_senha/');
        }

        if (!empty($this->data)) {

            $user = $this->User->find('first', array('conditions' => array('User.username' => $candidato_simples['cpf']) ) );

            $user['User']['password'] = $this->Auth->password($this->data['User']['password']);

            $this->User->create();
            if ($this->User->save($user)) {
                $this->Session->setFlash(__('Senha salva', true));
                $this->redirect(array('action'=>'login'));
            } else {
                $this->Session->setFlash(__('Houve um erro ao salvar a senha. Tente novamente', true));
            }
        }

        $this->set('candidato', $candidato_simples);
    }
}
?>
