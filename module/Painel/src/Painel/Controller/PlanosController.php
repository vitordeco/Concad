<?php
namespace Painel\Controller;
use Model\ModelInscricao;
use Model\ModelInscricaoTipo;
use Model\ModelPessoaFisica;
use Model\ModelPessoaJuridica;
use Zend\View\Model\ViewModel;
use Painel\Classes\GlobalController;
use Model\ModelProduto;
use Painel\Validate\ValidateAbstract;
use Model\ModelUsuario;
use Model\ModelEndereco;

class PlanosController extends GlobalController
{
    public function indexAction()
    {
        $this->init();
        
        //selecionar tipos
        $model = new ModelPessoaJuridica($this->tb, $this->adapter);
        $this->view["result"] = $model->page($this->get['page'])->limit(3)->get();
//        echo'<pre>'; print_r($this->view["result"]); exit;

        //view
        $this->setTitle("Planos");
        return new ViewModel($this->view);
    }
    
    public function formAction()
    {
        $this->init();
        
        //selecionar tipo
        $model = new ModelPessoaJuridica($this->tb, $this->adapter);
        $this->view['result'] = $model->current()->get();
//        echo '<pre>'; print_r($this->view['result']); exit;
        
        //view
        $this->setTitle("Planos");
        return new ViewModel($this->view);
    }
    
    protected function save()
    {
        try
        {
            //validar
            \Painel\Validate\ValidateTipo::validate($this->post);

            //salvar
            $model = new ModelInscricaoTipo($this->tb, $this->adapter);
            $model->save($this->post, $this->get['id']);
            
            //mensagem
            $this->flashmessenger()->addSuccessMessage(\Application\Validate\ValidateAbstract::SUCCESS_DEFAULT);
            
            //redirecionamento
            $response = array(
                'redirect' => "/painel/tipo"
            );
            
        } catch ( \Exception $e ) {
            
            $response = array(
                'error' => $e->getMessage()
            );
            
        }
        
        echo json_encode($response); exit;
    }
}