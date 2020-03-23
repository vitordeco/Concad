<?php
namespace Painel\Controller;
use Zend\View\Model\ViewModel;
use Painel\Classes\GlobalController;

class IndexController extends GlobalController
{
	public function indexAction()
	{
		//view
		$this->setTitle("Bem-vindo");
		return new ViewModel($this->view);
	}
}