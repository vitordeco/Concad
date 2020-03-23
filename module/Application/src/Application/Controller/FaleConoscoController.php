<?php
namespace Application\Controller;
use Application\Classes\MailMessage;
use Application\Validate\ValidateAbstract;
use mysql_xdevapi\Exception;
use Zend\View\Model\ViewModel;
use Application\Classes\GlobalController;

class FaleConoscoController extends GlobalController
{
    public function indexAction()
    {
        $this->init();

        //view
        $this->head->setTitle("Fale conosco");
        return new ViewModel($this->view);
    }

    protected function send()
    {
        try
        {
            //validar
            \Application\Validate\ValidateFaleConosco::validate($this->post);
            \Tropaframework\Security\NoCSRF::valid('content');

            //enviar e-mail
            $mail = new MailMessage($this->layout()->config_smtp);
            $response = $mail->contato($this->layout()->config_smtp['emailTo'], $this->post);
            if( $response !== true ) throw new \Exception(ValidateAbstract::ERROR_SENDMAIL);

            //redirecionar
            $this->flashMessenger()->addSuccessMessage(ValidateAbstract::SUCCESS_SENDMAIL);
            $response = array(
                'redirect' => "/" . $this->layout()->routes['controller']
            );

        } catch ( \Exception $e ) {

            $response = array(
                'error' => $e->getMessage()
            );

        }

        echo json_encode($response); exit;
    }
}