<?php
namespace Application\Controller;
use Zend\View\Model\ViewModel;
use Application\Classes\GlobalController;

class ApiController extends GlobalController
{
    public function indexAction()
    {
        //view
        $this->head->setTitle("Api");
        return new ViewModel($this->view);
    }
}