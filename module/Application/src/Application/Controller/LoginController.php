<?php
namespace Application\Controller;
use Application\Classes\MailMessage;
use Model\ModelLogin;
use Zend\View\Model\ViewModel;
use Application\Classes\GlobalController;

class LoginController extends GlobalController
{
    public function indexAction()
    {
        $this->init();

        //verificar se já está logado
        if( ModelLogin::loginCheck($this->layout()->me) )
        {
            $this->redirect()->toUrl('/conta');
        }

        //view
        $this->head->setTitle("Login");
        $view = new ViewModel();
        $view->setTerminal(true);
        $view->setTemplate('layout/layout_alt');
        $content = new ViewModel($this->view);
        $content->setTemplate( $this->layout()->routes['module'] . '/' . $this->layout()->routes['controller'] . '/index');
        $view->addChild($content, 'content');
        return $view;
    }

    public function logoutAction()
    {
        ModelLogin::logout();
        return $this->redirect()->toUrl('/?logout=true');
    }

    public function emailTestAction()
    {
        $mail = new MailMessage($this->layout()->config_smtp);
        $response = $mail->recuperarSenha('vitordeco@gmail.com', []);
        var_dump($response); exit;
    }

    protected function login()
    {
        try
        {
            //validar
            \Application\Validate\ValidateLogin::validate($this->post, $this->tb, $this->adapter);
            \Tropaframework\Security\NoCSRF::valid('content');

            //iniciar sessão de login
            ModelLogin::login($this->post['lg'], $this->tb, $this->adapter);

            //redirecionar
            $this->post['r'] = !empty($this->get['r']) ? $this->get['r'] : '/';
            $response = array(
                'redirect' => $this->post['r']
            );

        } catch ( \Exception $e ) {

            $response = array(
                'error' => $e->getMessage()
            );

        }

        echo json_encode($response); exit;
    }




    /**
     * @todo login com facebook?
     */
    public function ___facebookAction()
    {
        try
        {
            //verificar se existe as configurações do facebook
            if( empty($this->layout()->config_facebook) )
            {
                throw new \Exception('Houve um problema ao carregar o Facebook, utilize o login padrão!');
            }

            //redirecionamento após o login
            $redirect = \Naicheframework\Session\Session::get('r');
            if( empty($redirect) )
            {
                $redirect = $this->params()->fromQuery('r', '/');
                \Naicheframework\Session\Session::set('r', $redirect);
            }

            //instancia do SDK
            $fb = new \Naicheframework\Facebook\SDK($this->layout()->config_facebook);

            //verificar se retornou algum erro
            if( !empty($this->params()->fromQuery('error')) )
            {
                throw new \Exception('O login foi cancelado ou recusado.');
            }

            //caso não retornou o code redireciona para o facebook
            if( empty($this->params()->fromQuery('code')) )
            {
                //redireciona ao facebook
                $url = $fb->loginRedirect($redirect);
                return $this->redirect()->toUrl($url);
            }

            //selecionar todos os dados recuperados do facebook
            $params = $fb->get();
            //echo'<pre>'; print_r($params); exit;

            //define os parâmetros
            $data = array();
            $data['id_facebook'] = $params["id"];
            $data['email'] = $params["email"];
            $data['nome'] = $params["name"];
            $data['sexo'] = (strtolower($params["gender"]) == "male") ? "Masculino" : "Feminino";
            $data['foto_cover'] = isset($params["cover"]["source"]) ? $params["cover"]["source"] : null;
            $data['foto'] = isset($params["picture"]['url']) ? $params["picture"]['url'] : null;

            //forçar o login do usuário
            $loginReturn = self::logarUsuarioSemSenha($data['email'], $this->api);

            //se encontrou o usuário e fez o login
            if( $loginReturn === true )
            {
                //redirecionar
                \Naicheframework\Session\Session::unset('r');
                return $this->redirect()->toUrl($redirect);
            }

            //armazena dados na session
            \Naicheframework\Session\Session::set("cadastro", $data);

            //mensagem
            $this->flashMessenger()->addInfoMessage("Complete o seu cadastro para continuar.");

            //redirecionar para o cadastro
            return $this->redirect()->toUrl("/cadastro");

        } catch( \Exception $e ){

            //mensagem de erro
            $this->flashMessenger()->addErrorMessage($e->getMessage());

            //redirecionamento
            return $this->redirect()->toRoute(null, ['controller'=>'login'], ['query'=>['r'=>$redirect]]);
        }
    }
}