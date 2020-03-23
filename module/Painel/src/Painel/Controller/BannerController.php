<?php
namespace Painel\Controller;
use Model\ModelBanner;
use Zend\View\Model\ViewModel;
use Painel\Classes\GlobalController;

class BannerController extends GlobalController
{
    public function indexAction()
    {
        $this->init();
        
        //filtrar por status
        $where = " (banner.status != '" . \Model\ModelTableGateway::STATUS_EXCLUIDO . "')";
        
        //filtrar por banner
        if( !empty($this->get["banner"]) )
        {
            $where .= " AND (banner.banner LIKE '%" . $this->get["banner"] . "%')";
        }

        //selecionar banners
        $order = "banner.banner ASC";
        $model = new ModelBanner($this->tb, $this->adapter);
        $this->view["result"] = $model->where($where)->order($order)->page($this->get['page'])->limit(50)->get();
//        echo'<pre>'; print_r($this->view["result"]); exit;
        
        //view
        $this->setTitle("Banners");
        return new ViewModel($this->view);
    }
    
    public function formAction()
    {
        $this->init();
        
        //selecionar banner
        $where = "(banner.id_banner = '" . $this->get['id'] . "')";
        $model = new ModelBanner($this->tb, $this->adapter);
        $this->view['result'] = $model->where($where)->current()->get();
        if( !empty($this->view['result']) )
        {
            $this->view['result']->imagem = "/assets/uploads/banners/" . $this->view['result']->imagem;
            $this->view['result']->imagem_mobile = "/assets/uploads/banners/" . $this->view['result']->imagem_mobile;
        }
//        echo '<pre>'; print_r($this->view['result']); exit;

        //view
        $this->head->setJs('helpers/upload.js');
        $this->setTitle("Banners");
        return new ViewModel($this->view);
    }
    
    protected function save()
    {
        try
        {
            //validar
            \Painel\Validate\ValidateBanner::validate($this->post);

            //salvar
            $model = new ModelBanner($this->tb, $this->adapter);
            $model->save($this->post, $this->get['id']);
            
            //mensagem
            $this->flashmessenger()->addSuccessMessage(\Application\Validate\ValidateAbstract::SUCCESS_DEFAULT);
            
            //redirecionamento
            $response = array(
                'redirect' => "/painel/" . $this->layout()->routes['controller']
            );
            
        } catch ( \Exception $e ) {
            
            $response = array(
                'error' => $e->getMessage()
            );
            
        }
        
        echo json_encode($response); exit;
    }

    protected function delete()
    {
        try
        {
            //salvar
            $set = array();
            $set['status'] = \Model\ModelTableGateway::STATUS_EXCLUIDO;
            $model = new ModelBanner($this->tb, $this->adapter);
            $model->save($set, $this->post['id']);

            //mensagem
            $this->flashmessenger()->addSuccessMessage(\Application\Validate\ValidateAbstract::SUCCESS_ACTION);

            //redirecionamento
            $response = array(
                'redirect' => "/painel/" . $this->layout()->routes['controller']
            );

        } catch ( \Exception $e ) {

            $response = array(
                'error' => $e->getMessage()
            );

        }

        echo json_encode($response); exit;
    }
}