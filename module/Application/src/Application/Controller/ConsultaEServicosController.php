<?php
namespace Application\Controller;
use Model\ModelPessoaFisica;
use Model\ModelPessoaJuridica;
use Tropaframework\Concad\ConcadSintegra;
use Zend\View\Model\ViewModel;
use Application\Classes\GlobalController;

class ConsultaEServicosController extends GlobalController
{
    public function indexAction()
    {
        $this->init();

        //view
        $this->head->setTitle("Consulta e Serviços");
        return new ViewModel($this->view);
    }

    public function cpfAction()
    {
        $this->restrict();
        $this->init();

        //view
        $this->head->setTitle("Completa CPF");
        return new ViewModel($this->view);
    }

    public function cnpjAction()
    {
        $this->restrict();
        $this->init();

        //view
        $this->head->setTitle("Completa CNPJ");
        return new ViewModel($this->view);
    }

    protected function consultarCpf()
    {
        try
        {
            //validar
            \Application\Validate\ValidateConsulta::cpf($this->post);
            \Tropaframework\Security\NoCSRF::valid('content');

            //formatar dados
            $cpf = str_replace(['.','-'], '', $this->post['cpf']);
            $nascimento = str_replace(['/'], '', $this->post['nascimento']);

            //consultar
            $concadSintegra = new ConcadSintegra();
            $resultReceitaFederalCPF = $concadSintegra->apiReceitaFederalCPF($cpf, $nascimento);
//            echo'<pre>'; print_r($resultReceitaFederalCPF); exit;

            //salvar
            $data = array();
            $data['cpf'] = $resultReceitaFederalCPF->cpf;
            $data['nascimento'] = $resultReceitaFederalCPF->data_nascimento;
            $data['receita_federal'] = $resultReceitaFederalCPF;
            $model = new ModelPessoaFisica($this->tb, $this->adapter);
            $model->save($data);

            //redirecionar
            $response = array(
                'redirect' => '/resultado/' . $cpf,
            );

        } catch ( \Exception $e ) {

            $response = array(
                'error' => $e->getMessage()
            );

        }

        echo json_encode($response); exit;
    }

    protected function consultarCnpj()
    {
        try
        {
            //validar
            $this->post['cnpj'] = str_replace(['.','-','/'], '', $this->post['cnpj']);
            \Application\Validate\ValidateConsulta::cnpj($this->post);
            \Tropaframework\Security\NoCSRF::valid('content');

            //consultar
            $concadSintegra = new ConcadSintegra();
            $resultReceitaFederalCNPJ = $concadSintegra->apiReceitaFederalCNPJ($this->post['cnpj']);
            $resultSimplesNacional = $concadSintegra->apiSimplesNacional($this->post['cnpj']);
            $resultSintegra = $concadSintegra->apiSintegra($this->post['cnpj']);
//            $resultSuframa = $concadSintegra->apiSuframa($this->post['cnpj']);

            //salvar
            $data = array();
            $data['cnpj'] = $resultReceitaFederalCNPJ->cnpj;
            $data['razao_social'] = $resultReceitaFederalCNPJ->nome;
            $data['descricao'] = null;
            $data['telefone'] = $resultReceitaFederalCNPJ->telefone;
            $data['email'] = $resultReceitaFederalCNPJ->email;
            $data['imagem'] = null;
            $data['receita_federal'] = $resultReceitaFederalCNPJ;
            $data['simples_nacional'] = $resultSimplesNacional;
            $data['sintegra'] = $resultSintegra;
//            $data['suframa'] = $resultSuframa;
            $model = new ModelPessoaJuridica($this->tb, $this->adapter);
            $model->save($data);

            //redirecionar
            $response = array(
                'redirect' => '/resultado/' . $this->post['cnpj'],
            );

        } catch ( \Exception $e ) {

            $response = array(
                'error' => $e->getMessage()
            );

        }

        echo json_encode($response); exit;
    }
}