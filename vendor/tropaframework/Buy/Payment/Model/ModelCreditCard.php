<?php
namespace Tropaframework\Buy\Payment\Model;

class ModelCreditCard extends ModelAbstract
{
    protected $hash;
    protected $nome;
    protected $tipo;
    protected $parcela;
    
    public function getHash()
    {
        return $this->hash;
    }
    public function setHash($value)
    {
        $this->hash = $value;
    }
    
    public function getNome()
    {
        return $this->nome;
    }
    public function setNome($value)
    {
        $this->nome = $value;
    }
    
    public function getTipo()
    {
        return $this->tipo;
    }
    public function setTipo($value)
    {
        $this->tipo = $value;
    }
    
    public function getParcela()
    {
        return (int)$this->parcela;
    }
    public function setParcela(int $value)
    {
        $this->parcela = $value;
    }
}