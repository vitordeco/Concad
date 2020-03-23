<?php
namespace Application\Controller;
use Application\Classes\MailMessage;
use Model\ModelLogin;
use Model\ModelTableGateway;
use Model\ModelUsuario;
use Zend\View\Model\ViewModel;
use Application\Classes\GlobalController;

class RecuperarSenhaController extends GlobalController
{
    public function indexAction()
    {
        $this->init();

        //view
        $this->head->setTitle("Recuperar Senha");
        $this->head->setJs("helpers/passwordForce.js");
        $view = new ViewModel();
        $view->setTerminal(true);
        $view->setTemplate('layout/layout_alt');
        $content = new ViewModel($this->view);
        $content->setTemplate( $this->layout()->routes['module'] . '/' . $this->layout()->routes['controller'] . '/index');
        $view->addChild($content, 'content');
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
            //selecionar usuario
            $where = "(login.status = '" . ModelTableGateway::STATUS_ATIVO . "')";
            $where .= " AND (login.login = '" . $this->post['email'] . "')";
            $model = new ModelUsuario($this->tb, $this->adapter);
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

            //redirecionar
            $this->flashmessenger()->addSuccessMessage("Clique no link enviado para o seu e-mail para alterar sua senha.");
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

    protected function redefinirSenhaEmail()
    {
        try
        {
            //validar
            \Application\Validate\ValidateRecuperarSenha::validate($this->post);

            //validar token
            $secureDecode = unserialize(base64_decode(base64_decode($this->get['secure'])));
            $where = "login.login = '" . $secureDecode['email'] . "'";
            $model = new ModelLogin($this->tb, $this->adapter);
            $usuario = $model->where($where)->current()->get();
            if( empty($usuario) ) throw new \Exception("Token inválido.");

            //validar origem
            \Tropaframework\Security\NoCSRF::valid('content');

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