<?php
namespace Painel\Controller;
use Painel\Classes\GlobalController;
use Zend\View\Model\ViewModel;
use Model\ModelLogin;

class LoginController extends GlobalController
{
    public function indexAction()
    {
    	$this->init();
    	
    	//view
    	$this->setTitle('Login');
    	$view = new ViewModel($this->view);
    	$view->setTerminal(true);
    	return $view;
    }
   	
    public function logoutAction()
    {
    	//remover session
    	$session = new \Zend\Session\Container('AuthBackend');
    	$session->getManager()->destroy();
    	
    	//redirecionar
    	return $this->redirect()->toUrl('/painel/login');
    }
	
    public function __logarComOutraContaAction()
    {
        //definir o login do administrador
        $administrador = $this->layout()->me;
        
        //id do login
        $id_login = $this->params('id');
        
        //get user
        $user = self::getUser($id_login, $this->tb, $this->adapter);
        
        //salvar informações do usúario para a opção de voltar
        $user->administrador = $administrador;
        $session = new \Zend\Session\Container('AuthBackend');
        $session->offsetSet('me', $user);
        
        //redirecionamento
        return $this->redirect()->toUrl('/');
    }
    
    public function __voltarParaMinhaContaAction()
    {
        //definir o login do administrador
        $user = $this->layout()->me->administrador;
    
        //salvar informações do usúario para a opção de voltar
        $session = new \Zend\Session\Container('AuthBackend');
        $session->offsetSet('me', $user);
        
        //redirecionamento
        return $this->redirect()->toUrl('/');
    }
    
	protected function logar()
    {
    	//login
    	$login = $this->params()->fromPost('lg');
    	
    	//password
    	$password = $this->params()->fromPost('pw');
    	
    	//redirecionamento
    	$redirect = !empty($this->params()->fromPost('r')) ? $this->params()->fromPost('r') : '/painel';
    	
    	try 
    	{
    		//selecionar login
    		$where = 'login.login = "' . $login . '" AND login.senha = "' . md5($password) . '" AND login.nivel = "' . ModelLogin::NIVEL_BACKEND . '"';
    		$model = new ModelLogin($this->tb, $this->adapter);
    		$result = $model->where($where)->current()->get();
//     		echo'<pre>'; print_r($result); exit;
    		
    		//retornar erro caso inválido
    		if( empty($result->id_login) ) 
    		{
    			throw new \Exception('Dados inválidos.');
    		}
    		
    		//validar se está ativo
    		if( empty($result->status) )
    		{
    			throw new \Exception('Usuário inativo.');
    		}
    		
    		self::getUser($result->id_login, $this->tb, $this->adapter);

    	} catch(\Exception $e){
    		
    		//mensagem erro
    		$this->flashMessenger()->addErrorMessage($e->getMessage());
    		
    	}
    	
    	//redirecionamento
    	return $this->redirect()->toUrl($redirect);
    }
    
    protected function __configSave()
    {
    	try
    	{
    		//params
    		$config = $this->params()->fromPost('config');
    		if( is_array($this->layout()->me->config) ) $config = array_merge($this->layout()->me->config, $config);
    		$params['config'] = json_encode($config);
    		//echo'<pre>'; print_r($config); exit;
    		
    		//save
    		$params['where'] = "id_login = '" . $this->layout()->me->id_login . "'";
    		$this->api->call('login/update', $params)->result();
    		
    		//atualiza o this
    		self::getUser($this->layout()->me->id_login, $this->tb, $this->adapter);
    		
    		//return
    		$return = array(
    			'error' => 0,
    		);
    		
    	} catch(\Exception $e)
    	{
    		$return = array(
    			'error' => 1,
    			'message' => $e->getMessage(),
    		);
    	}
    	
    	//return
    	echo json_encode($return); exit;
    }
    
    public static function getUser($id_login, $tb, $adapter)
    {
        //selecionar login
        $where = 'login.id_login = "' . $id_login . '"';
        $model = new ModelLogin($tb, $adapter);
        $result = $model->where($where)->current()->get();
        
//         if( $result->nivel == 2 )
//         {
//             //habilitar todas as  permissões
//             $model = new ModelPermissoes($tb, $adapter);
//             $permissoes = $model->get(["only_permissions" => true]);
//             $result->permissoes = array_column($permissoes, 'permissao', 'permissao');
            
//         } else {
            
//     	    //selecionar permissão
//         	$where = 'login_permissao.id_login = "' . $result->id_login . '"';
//             $model = new ModelPermissoes($tb, $adapter);
//             $permissoes = $model->where($where)->get();
//         	$result->permissoes = array_column($permissoes, 'permissao', 'permissao');
//         	$result->permissoes['/index/index'] = '/index/index';
//         }
        
    	//set session
    	$session = new \Zend\Session\Container('AuthBackend');
    	$session->offsetSet('me', $result);
    	
    	return $result;
    }
}