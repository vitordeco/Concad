<?php
namespace Application\Controller;
use Zend\View\Model\ViewModel;
use Application\Classes\GlobalController;

class PlanosController extends GlobalController
{
    public function indexAction()
    {
        //view
        $this->head->setTitle("Planos");
        return new ViewModel($this->view);
    }
}