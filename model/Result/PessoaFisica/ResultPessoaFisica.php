<?php
namespace Model\Result\PessoaFisica;
use Model\Result\ResultAbstract;

class ResultPessoaFisica extends ResultAbstract
{
    //==================
    //RECEITA FEDERAL
    //==================
    protected $nome;
    protected $cpf;
    protected $data_nascimento;
    protected $situacao_cadastral;
    protected $data_inscricao;
    protected $digito_verificador;
    protected $comprovante;

    /**
     * @return mixed
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param mixed $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * @return mixed
     */
    public function getCpf()
    {
        return $this->cpf;
    }

    /**
     * @param mixed $cpf
     */
    public function setCpf($cpf)
    {
        $this->cpf = $cpf;
    }

    /**
     * @return mixed
     */
    public function getDataNascimento()
    {
        return $this->data_nascimento;
    }

    /**
     * @param mixed $data_nascimento
     */
    public function setDataNascimento($data_nascimento)
    {
        $this->data_nascimento = $data_nascimento;
    }

    /**
     * @return mixed
     */
    public function getSituacaoCadastral()
    {
        return $this->situacao_cadastral;
    }

    /**
     * @param mixed $situacao_cadastral
     */
    public function setSituacaoCadastral($situacao_cadastral)
    {
        $this->situacao_cadastral = $situacao_cadastral;
    }

    /**
     * @return mixed
     */
    public function getDataInscricao()
    {
        return $this->data_inscricao;
    }

    /**
     * @param mixed $data_inscricao
     */
    public function setDataInscricao($data_inscricao)
    {
        $this->data_inscricao = $data_inscricao;
    }

    /**
     * @return mixed
     */
    public function getDigitoVerificador()
    {
        return $this->digito_verificador;
    }

    /**
     * @param mixed $digito_verificador
     */
    public function setDigitoVerificador($digito_verificador)
    {
        $this->digito_verificador = $digito_verificador;
    }

    /**
     * @return mixed
     */
    public function getComprovante()
    {
        return $this->comprovante;
    }

    /**
     * @param mixed $comprovante
     */
    public function setComprovante($comprovante)
    {
        $this->comprovante = $comprovante;
    }


}