<?php
namespace Painel\Validate;

class ValidateBanner extends ValidateAbstract
{
	public static function validate($params)
	{
		$validate = new \Tropaframework\Helper\Validate();
        
		try
		{
		    //validar campos obrigatórios
    	    $required = ["imagem", "imagem_mobile", "banner"];
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