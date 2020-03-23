<?php
namespace Tropaframework\Concad;

/**
 * @author Vitor Deco
 */
abstract class ConcadAbstract
{
    const MESSAGE_ERROR_DEFAULT = "Houve um problema na consulta com a SINTEGRA, tente novamente mais tarde!";

	/**
	 * identificar ambiente
	 * @var boolean
	 */
    protected $is_sandbox = false;

    /**
     * identificar tipo
     * @var string
     */
    protected $type = null;
	
	/**
	 * construtor
	 * @param string $type
	 */
	public function __construct($type)
	{
		$this->type = $type;
	}
	
	/**
	 * alterar ambiente
	 * @param boolean $bool
	 * @return $this
	 */
	public function setSandbox($bool=true)
	{
		$this->is_sandbox = (bool)$bool;
		return $this;
	}

    /**
     * recuperar o tipo
     * @return string
     */
	public function getType()
	{
	    return $this->type;
	}
	
}