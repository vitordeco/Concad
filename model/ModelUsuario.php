<?php
namespace Model;
use Model\ModelTableGateway;

class ModelUsuario extends ModelTableGateway
{
	public function __construct($tb, $adapter)
	{
		$this->tb = $tb;
		parent::__construct($this->tb->usuario, $adapter);
	}
	
	public function get()
	{
	    $qry = $this->sql->select();
	    $qry->from(["usuario" => $this->tableName]);

	    //selecionar login
        $qry->join(["login" => $this->tb->login],
            "usuario.id_login = login.id_login",
            ["login", "senha", "token", "nivel"],
            $qry::JOIN_INNER);

	    return $this->execute($qry);
	}
	
	public function save($set, $id=null)
	{
		$set = $this->saveBefore($set, $id);
		return parent::save($set, $id);
	}
	
	private function saveBefore($set, $id)
	{
	    //salvar login
        if( !empty($set['email']) && !empty($set['senha']) )
        {
            $data = array(
                'login' => $set['email'],
                'senha' => $set['senha'],
            );
            $id_login = !empty($set['id_login']) ? $set['id_login'] : null;
            $model = new ModelLogin($this->tb, $this->adapter);
            $set['id_login'] = $model->save($data, $id_login);
        }

	    return $set;
	}
}