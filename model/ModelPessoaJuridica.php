<?php
namespace Model;
use Model\ModelTableGateway;
use Model\Result\ResultPessoaJuridica;

class ModelPessoaJuridica extends ModelTableGateway
{
	public function __construct($tb, $adapter)
	{
		$this->tb = $tb;
		parent::__construct($this->tb->pessoa_juridica, $adapter);
	}
	
	public function get()
	{
	    $qry = $this->sql->select();
	    $qry->from(["pessoa_juridica" => $this->tableName]);

	    return $this->execute($qry);
	}

    /**
     * @return ResultPessoaJuridica
     */
	public function result()
    {
        $data = $this->current()->get();
        $data = array_merge((array)json_decode($data->suframa), (array)json_decode($data->sintegra), (array)json_decode($data->simples_nacional), (array)json_decode($data->receita_federal));
        $result = new ResultPessoaJuridica();
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
        if( !empty($set['simples_nacional']) )
        {
            $set['simples_nacional'] = json_encode((array)$set['simples_nacional']);
        }
        if( !empty($set['sintegra']) )
        {
            $set['sintegra'] = json_encode((array)$set['sintegra']);
        }
        if( !empty($set['suframa']) )
        {
            $set['suframa'] = json_encode((array)$set['suframa']);
        }

        return $set;
    }
}