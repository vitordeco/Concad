<?php
namespace Tropaframework\Buy\Model\People;
use Tropaframework\Buy\Model\ModelAbstract;

class ModelAddress extends ModelAbstract
{
    protected $cep;
    protected $logradouro;
    protected $numero;
    protected $complemento;
    protected $bairro;
    protected $cidade;
    protected $estado;
    protected $pais;
    protected $referencia;
	
    public function getCep()
    {
    	return $this->cep;
    }
    public function setCep($value)
    {
    	$value = str_replace('-', '', $value);
    	$this->cep = $value;
    }
    
    public function getLogradouro(bool $format = true)
    {
        return $format ? \Tropaframework\Helper\Convert::removeEspecialChars($this->logradouro, true) : $this->logradouro;
    }
    public function setLogradouro($value)
    {
        $this->logradouro = $value;
    }

    public function getNumero()
    {
        return $this->numero;
    }
    public function setNumero($value)
    {
        $this->numero = $value;
    }
    
	public function getComplemento()
    {
    	return $this->complemento;
    }
    public function setComplemento($value)
    {
    	$this->complemento = $value;
    }
    
    public function getBairro(bool $format = true)
    {
    	return $format ? \Tropaframework\Helper\Convert::removeEspecialChars($this->bairro, true) : $this->bairro;
    }
    public function setBairro($value)
    {
    	$this->bairro = $value;
    }
    
    public function getCidade(bool $format = true)
    {
        return $format ? \Tropaframework\Helper\Convert::removeEspecialChars($this->cidade, true) : $this->cidade;
    }
    public function setCidade($value)
    {
    	$this->cidade = $value;
    }
    
    public function getEstado()
    {
    	return $this->estado;
    }
    public function setEstado($value)
    {
    	$this->estado = $value;
    }

    public function getPais()
    {
    	return $this->pais;
    }
    public function setPais($value)
    {
    	$this->pais = $value;
    }
    
    public function getReferencia()
    {
    	return $this->referencia;
    }
    public function setReferencia($value)
    {
    	$this->referencia = $value;
    }
}