<?php
namespace Application\Controller;
use Zend\View\Model\ViewModel;
use Application\Classes\GlobalController;
use Model\ModelLogin;
use Application\Classes\MailMessage;

class RecuperarSenhaController extends GlobalController
{
	public function indexAction()
	{
		$this->init();
		
		//view
		$this->head->setCss("login.css");
		$this->head->setTitle("Recuperar senha");
		$view = new ViewModel($this->view);
		$view->setTerminal(true);
		return $view;
	}
	
    public function redefinirEmailAction()
    {
    	$this->init();
    	
    	//params
    	$secure = $this->params()->fromQuery('secure');
    	$secure = unserialize(base64_decode(base64_decode($secure)));
    	
    	try
    	{
    		//valida o formato do hash
    		if( !is_array($secure) ) throw new \Exception("Hash inválido.");
    		 
    		//valida se expirou o tempo do hash
    		if( ($secure['time'] + (60*120)) < time() ) throw new \Exception("Hash expirado.");
    		
    		//selecionar usuario e validar se existe
    		$where = "login.login = '" . $secure['email'] . "'";
    		$model = new ModelLogin($this->tb, $this->adapter);
    		$usuario = $model->where($where)->current()->get();
    		if( empty($usuario) ) $usuario = $this->api->call('administrador/select', ['where'=>$where], 'GET')->current();
    		if( empty($usuario) ) throw new \Exception("O e-mail digitado não está cadastrado.");
    		if( !count($usuario) ) throw new \Exception("Hash inválido.");
    		
    	} catch( \Exception $e ) {
    		$this->flashmessenger()->addErrorMessage($e->getMessage());
    		return $this->redirect()->toUrl('/recuperar-senha');
    	}
    	
    	//view
    	$this->head->setCss("login.css");
    	$this->head->setJs("helpers/passwordForce.js");
    	$this->head->setTitle("Redefinir senha");
    	$view = new ViewModel($this->view);
		$view->setTerminal(true);
		return $view;
    }
    
    protected function enviarEmailParaRedefinir()
    {
    	try
    	{
    		//validar origem
    		\Tropaframework\Security\NoCSRF::valid("content");
    		
    		//definir email
    		$email = $this->params()->fromPost("email");
    		
    		//selecionar usuario
    		$where = "login.login = '" . $email . "'";
    		$model = new ModelLogin($this->tb, $this->adapter);
    		$usuario = $model->where($where)->current()->get();
    		if( empty($usuario) ) throw new \Exception("O e-mail digitado não está cadastrado."); 
    		
    		//enviar e-mail
    		$replace = [
    			'nome' => $usuario->nome,
    			'hash' => $this->generateHash($usuario->login),
    		];
    		$mail = new MailMessage($this->layout()->config_smtp);
    		$response = $mail->recuperarSenha($usuario->login, $replace);
    		if( ($response !== true) ) throw new \Exception("Não foi possível enviar o e-mail, tente novamente.");
    		
    		//mensagem sucesso
    		$this->flashmessenger()->addSuccessMessage('Clique no link enviado para o seu e-mail para alterar sua senha.');
    		
    	} catch(\Exception $e)
    	{
    		//mensagem erro
    		$this->flashmessenger()->addErrorMessage($e->getMessage());
    	}
    	
    	//redirecionar
    	return $this->redirect()->toUrl('/recuperar-senha');
    }
    
    protected function redefinirSenhaEmail()
    {
    	try
    	{
    		//validar origem
    		//\Tropaframework\Security\NoCSRF::valid("content");
    		
    		//validar força de senha
    		$validate = new \Tropaframework\Helper\Validate();
    		if( ($validate::passwordForce($this->post['senha'], 4) == false) )
    		{
    		    throw new \Exception('Crie uma senha com pelo menos 6 caracteres, contendo no mínimo 6 caracteres, com letras maiúsculas, minúsculas, números e caracteres especiais como @ ou #.');
    		}
    		
    		//validar senha
    		if( $this->post['senha'] != $this->post['senha_confirmar'] )
    		{
    		    throw new \Exception('A confirmação da senha está incorreta.');
    		}
	    	
	    	//validar token
	    	$secure = $this->params()->fromQuery('secure');
	    	$secureDecode = unserialize(base64_decode(base64_decode($secure)));
    		
	    	//selecionar usuario
	    	$where = "login.login = '" . $secureDecode['email'] . "'";
	    	$model = new ModelLogin($this->tb, $this->adapter);
	    	$usuario = $model->where($where)->current()->get();
	    	if( empty($usuario) ) throw new \Exception("Token inválido.");
	    	
    		//salvar
    		$set = array();
    		$set['senha'] = $this->post['senha'];
    		$model = new ModelLogin($this->tb, $this->adapter);
    		$response = $model->save($set, $usuario->id_login);
    		if( empty($response) ) throw new \Exception("Não foi possível redefinir sua senha, tente novamente.");
    		
        	$this->flashmessenger()->addSuccessMessage('Sua senha foi alterada com sucesso.');
        	
        	$response = array(
        	    'redirect' => '/login'
        	);
    	
    	} catch ( \Exception $e ) {
    	
    	    $response = array(
    	        'error' => $e->getMessage()
    	    );
    	
    	}
    	
    	echo json_encode($response); exit;
    }

	private function generateHash($email)
	{
		$hash = array(
			'email' => $email,
			'time' => time(),
		);
		
		return base64_encode( base64_encode(serialize($hash)) );
	}
}