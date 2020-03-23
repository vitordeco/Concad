<?php
namespace Application\Controller;
use Model\ModelPessoaFisica;
use Model\ModelPessoaJuridica;
use Zend\View\Model\ViewModel;
use Application\Classes\GlobalController;

class ResultadoController extends GlobalController
{
    public function indexAction()
    {
        $this->restrict();
        $doc = $this->params('slug');

        if( strlen($doc) == 14 )
        {
            return $this->redirect()->toUrl("/resultado/pj/" . $doc);
        } else {
            return $this->redirect()->toUrl("/resultado/pf/" . $doc);
        }

    }

    public function pfAction()
    {
        $this->restrict();
        $doc = $this->params('id');

        //selecionar PF
        $where = "(REPLACE(REPLACE(REPLACE(pessoa_fisica.cpf,'/',''),'-',''),'.','') = '" . $doc . "')";
        $order = "pessoa_fisica.criado DESC";
        $model = new ModelPessoaFisica($this->tb, $this->adapter);
        $this->view['result'] = $model->where($where)->order($order)->result();
//        echo'<pre>'; print_r($this->view['result']); exit;

        //view
        $this->head->setTitle("Resultado");
        return new ViewModel($this->view);
    }

    public function pjAction()
    {
        $this->restrict();
        $doc = $this->params('id');

        //selecionar PJ
        $where = "(REPLACE(REPLACE(REPLACE(pessoa_juridica.cnpj,'/',''),'-',''),'.','') = '" . $doc . "')";
        $order = "pessoa_juridica.criado DESC";
        $model = new ModelPessoaJuridica($this->tb, $this->adapter);
        $this->view['result'] = $model->where($where)->order($order)->result();
//        echo'<pre>'; print_r($this->view['result']); exit;

        //view
        $this->head->setTitle("Resultado");
        return new ViewModel($this->view);
    }
}