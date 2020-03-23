<?php
namespace Model;
use Model\ModelTableGateway;
use Model\Result\ResultPessoaFisica;
use Model\Result\ResultPessoaJuridica;

class ModelPessoaFisica extends ModelTableGateway
{
	public function __construct($tb, $adapter)
	{
		$this->tb = $tb;
		parent::__construct($this->tb->pessoa_fisica, $adapter);
	}
	
	public function get()
	{
	    $qry = $this->sql->select();
	    $qry->from(["pessoa_fisica" => $this->tableName]);

	    return $this->execute($qry);
	}

    /**
     * @return ResultPessoaFisica
     */
	public function result()
    {
        $data = $this->current()->get();
        $data = (array)json_decode($data->receita_federal);
        $result = new ResultPessoaFisica();
        return $result->populate($data);
    }

    public function save($set, $id=null)
    {
        $set = $this->saveBefore($set, $id);
        $set[$this->primary_key] = parent::save($set, $id);
        return $set[$this->primary_key];
    }

    private function saveBefore($set)
    {
        //array to json
        if( !empty($set['receita_federal']) )
        {
            $set['receita_federal'] = json_encode((array)$set['receita_federal']);
        }

        return $set;
    }
}