<?php
namespace Application\Controller;
use Zend\View\Model\ViewModel;
use Application\Classes\GlobalController;

class IndexController extends GlobalController
{
    public function indexAction()
    {
        //view
        $this->head->setTitle("Consulta Cadastral");
        return new ViewModel($this->view);
    }
}