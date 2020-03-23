<?php
namespace Model\Result;

class ResultPessoaJuridica extends \Model\Result\PessoaJuridica\ResultPessoaJuridica
{
    public function getRazaoSocial()
    {
        return !empty($this->getNome()) ? $this->getNome() : $this->getNomeEmpresarial();
    }

    public function getEnderecoCompleto()
    {
        return $this->getLogradouro() . ', ' . $this->getNumero() . ' ' . $this->getComplemento() . ', ' . $this->getBairro() . ', ' . $this->getMunicipio() . '/' . $this->getUf();
    }
}