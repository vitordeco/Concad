<?php
namespace Painel\Validate;

class ValidatePalestrante extends ValidateAbstract
{
	public static function validate($params)
	{
		$validate = new \Tropaframework\Helper\Validate();
        
		try
		{
		    //validar campos obrigatórios
    	    $required = ["foto", "nome", "descricao"];
    	    if( !$validate::isArrayNotEmpty($params, $required) )
    	    {
    	        throw new \Exception(self::ERROR_REQUIRED);
    	    }

		} catch( \Exception $e ){
			throw new \Exception($e->getMessage());
		}
        
		return true;
	}
}