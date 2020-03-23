<?php
namespace Application\Controller;
use Application\Classes\MailMessage;
use Application\Validate\ValidateAbstract;
use Model\ModelLogin;
use Model\ModelUsuario;
use Zend\View\Model\ViewModel;
use Application\Classes\GlobalController;

class ContaController extends GlobalController
{
    public function indexAction()
    {
        return $this->redirect()->toUrl('conta/meus-dados');
    }

    public function meusDadosAction()
    {
        $this->init();

        //selecionar usuário
        $where = "(usuario.id_usuario = '" . $this->layout()->me->id_usuario . "')";
        $model = new ModelUsuario($this->tb, $this->adapter);
        $this->view['data'] = $model->where($where)->current()->get();

        //view
        $this->head->setTitle("Meus Dados");
        $this->head->setJs("helpers/maskCpfCnpj.js");
        return new ViewModel($this->view);
    }

    protected function meusDadosSave()
    {
        try
        {
            //validar
            \Application\Validate\ValidateConta::meusdados($this->post, $this->tb, $this->adapter);
            \Tropaframework\Security\NoCSRF::valid('content');

            //salvar
            $model = new ModelUsuario($this->tb, $this->adapter);
            $model->save($this->post, $this->layout()->me->id_usuario);

            //logar
            ModelLogin::login($this->post['email'], $this->tb, $this->adapter);

            //redirecionar
            $this->flashMessenger()->addSuccessMessage(ValidateAbstract::SUCCESS_ACTION);
            $response = array(
                'redirect' => '/' . $this->layout()->routes['controller'] . '/' . $this->layout()->routes['action']
            );

        } catch ( \Exception $e ) {

            $response = array(
                'error' => $e->getMessage()
            );

        }

        echo json_encode($response); exit;
    }
}