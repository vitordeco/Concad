<?php
namespace Tropaframework\Buy\Model\People;

class ModelPeople extends ModelAddress
{
    protected $nome;
    protected $documento;
    protected $ie;
    protected $telefone;
    protected $email;
    protected $nascimento;
    
    public function getNome(bool $format = false)
    {
        if( $format )
        {
            $this->nome = \Tropaframework\Helper\Convert::removeEspecialChars($this->nome, true);
            $this->nome = preg_replace('/\d/', '', $this->nome);
            $this->nome = preg_replace('/[\n\t\r]/', ' ', $this->nome);
            $this->nome = preg_replace('/\s(?=\s)/', '', $this->nome);
            $this->nome = trim($this->nome);
        }
        
        return $this->nome;
    }
    public function setNome($value)
    {
        $this->nome = $value;
    }
    
    public function getDocumentoTipo()
    {
        $document = str_replace([".","/","-"], "", $this->documento);
        $length = strlen($document);
        return ($length == 14) ? "CNPJ" : "CPF";
    }
    public function getDocumento(bool $number_only = false)
    {
        if( $number_only )
        {
            $this->documento = str_replace([".","/","-"], "", $this->documento);
        }
    
        return $this->documento;
    }
    public function setDocumento($value)
    {
        $this->documento = $value;
    }
    
    public function getIe()
    {
        return $this->ie;
    }
    public function setIe($value)
    {
        $this->ie = $value;
    }
    
    public function getTelefoneDDD()
    {
        $result = str_replace(['(',')','-',' '], '', $this->telefone);
        return (int)substr($result, 0, 2);
    }
    public function getTelefoneNumber()
    {
        $result = str_replace(['(',')','-',' '], '', $this->telefone);
        return (int)substr($result, 2);
    }
    public function getTelefone($number_only = false)
    {
        if( $number_only )
        {
            $this->telefone = str_replace(['(',')','-',' '], '', $this->telefone);
        }
    
        return $this->telefone;
    }
    public function setTelefone($value)
    {
        $this->telefone = $value;
    }
    
    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($value)
    {
        $this->email = $value;
    }
    
    public function getNascimento()
    {
        return !empty($this->nascimento) ? $this->nascimento : "16/07/1990";
    }
    public function setNascimento($value)
    {
        $this->nascimento = $value;
//        $this->nascimento = implode("-", array_reverse(explode("/", $value)));
    }
}