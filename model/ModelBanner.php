<?php
namespace Model;
use Model\ModelTableGateway;

class ModelBanner extends ModelTableGateway
{
	public function __construct($tb, $adapter)
	{
		$this->tb = $tb;
		parent::__construct($this->tb->banner, $adapter);
	}
	
	public function get()
	{
	    $qry = $this->sql->select();
	    $qry->from(["banner" => $this->tableName]);

	    return $this->execute($qry);
	}

    public function save($set, $id=null)
    {
        $set = $this->saveBefore($set, $id);
        $set[$this->primary_key] = parent::save($set, $id);
        return $set[$this->primary_key];
    }

    private function saveBefore($set)
    {
        //mover imagem
        if( !empty($set['imagem']) && (strpos($set['imagem'], "tmp") !== false) )
        {
            $oldname = $_SERVER["DOCUMENT_ROOT"] . $set['imagem'];
            $newname = str_replace("/tmp", "/banners", $oldname);
            @rename($oldname, $newname);
        }
        $set['imagem'] = basename($set['imagem']);

        //mover imagem mobile
        if( !empty($set['imagem_mobile']) && (strpos($set['imagem_mobile'], "tmp") !== false) )
        {
            $oldname = $_SERVER["DOCUMENT_ROOT"] . $set['imagem_mobile'];
            $newname = str_replace("/tmp", "/banners", $oldname);
            @rename($oldname, $newname);
        }
        $set['imagem_mobile'] = basename($set['imagem_mobile']);

        return $set;
    }
}