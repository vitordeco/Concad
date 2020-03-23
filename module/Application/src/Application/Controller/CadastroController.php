<?php
namespace Application\Controller;
use Application\Classes\MailMessage;
use Model\ModelLogin;
use Model\ModelUsuario;
use Zend\View\Model\ViewModel;
use Application\Classes\GlobalController;

class CadastroController extends GlobalController
{
    public function indexAction()
    {
        $this->init();

        //view
        $this->head->setTitle("Cadastro");
        $this->head->setJs("helpers/maskCpfCnpj.js");
        $this->head->setJs("helpers/passwordForce.js");
        $view = new ViewModel();
        $view->setTerminal(true);
        $view->setTemplate('layout/layout_alt');
        $content = new ViewModel($this->view);
        $content->setTemplate( $this->layout()->routes['module'] . '/' . $this->layout()->routes['controller'] . '/index');
        $view->addChild($content, 'content');
        return $view;
    }

    protected function save()
    {
        try
        {
            //validar
            \Application\Validate\ValidateCadastro::validate($this->post, $this->tb, $this->adapter);
            \Tropaframework\Security\NoCSRF::valid('content');

            //salvar
            $model = new ModelUsuario($this->tb, $this->adapter);
            $model->save($this->post);

            //logar
            ModelLogin::login($this->post['email'], $this->tb, $this->adapter);

            //redirecionar
            $this->flashMessenger()->addSuccessMessage("Cadastro realizado com sucesso!");
            $this->post['r'] = !empty($this->post['r']) ? $this->post['r'] : '/';
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
}