<?php
namespace Application\Controller;
use Zend\View\Model\ViewModel;
use Application\Classes\GlobalController;

class ComprarCreditoController extends GlobalController
{
    public function indexAction()
    {
        //view
        $this->head->setTitle("Comprar Crédito");
        return new ViewModel($this->view);
    }
}