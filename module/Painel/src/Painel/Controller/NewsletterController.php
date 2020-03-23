<?php
namespace Painel\Controller;
use Zend\View\Model\ViewModel;
use Painel\Classes\GlobalController;
use Model\ModelProduto;
use Painel\Validate\ValidateAbstract;
use Model\ModelUsuario;
use Model\ModelEndereco;
use Model\ModelFaq;
use Model\ModelNewsletter;

class NewsletterController extends GlobalController
{
    public function indexAction()
    {
        $this->init();
        
        //filter
        $this->view['filter'] = $this->params()->fromQuery();
        
        //selecionar produtos
        $model = new ModelNewsletter($this->tb, $this->adapter);
        $this->view["news"] = $model->page($this->get['page'])->limit(20)->get();
        //echo'<pre>'; print_r($this->view["news"]); exit;
        
        $where = null;
        //verificar se é pra exportar os resultados
        if( $this->view['filter']['export'] == 'csv' )
        {
            $this->exportCSV($where);
        }
        
        //view
        $this->setTitle("Dúvidas Frequentes");
        return new ViewModel($this->view);
    }
    
    public function formAction()
    {
        $this->init();
        
        $where = "(id_faq = '" . $this->get['id'] . "')";
        $model = new ModelFaq($this->tb, $this->adapter);
        $this->view['result'] = $model->where($where)->current()->get();
        //echo '<pre>'; print_r($this->view['result']); exit;
        
        
        //view
        $this->head->setJs("helpers/upload.js");
        $this->setTitle("Dúvidas Frequentes");
        return new ViewModel($this->view);
    }

    private function exportCSV($where)
    {
        //selecionar os usuários
        $model = new ModelNewsletter($this->tb, $this->adapter);
        $result = $model->get();
        
        //echo '<pre>'; print_r($result); exit;
        
        //colunas
        $columns = ['Data de cadastro', 'E-mail'];
        
        //escrever o CSV
        $csv = null;
        $csv .= implode(';', $columns);
        $csv .= PHP_EOL;
        
        foreach( $result as $r )
        {
            //linha do CSV
            $row = array();
            $row[] = \Tropaframework\Helper\Convert::date($r['criado']);
            $row[] = $r['email'];
            $csv .= '"' . implode('";"', $row) . '"';
            $csv .= PHP_EOL;
        }
        
        //nome do arquivo
        $filename = "NEWSLETTER.csv";
        
        //headers para download do CSV
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=" . $filename);
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $csv; exit;
    }
    
}