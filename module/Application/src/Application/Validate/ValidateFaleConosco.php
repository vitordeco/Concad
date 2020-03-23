<?php
namespace Application\Validate;

class ValidateFaleConosco extends ValidateAbstract
{
	public static function validate($params)
	{
		$validate = new \Tropaframework\Helper\Validate();
        
		try
		{
		    //validar campos obrigatórios
    	    $required = ["nome", "email", "telefone", "assunto", "mensagem"];
    	    if( !$validate::isArrayNotEmpty($params, $required) )
    	    {
    	        throw new \Exception(self::ERROR_REQUIRED);
    	    }

    	    //validar e-mail
    	    if( !$validate::isEmail($params['email']) )
    	    {
    	        throw new \Exception(self::ERROR_EMAIL);
    	    }

		} catch( \Exception $e ){
			throw new \Exception($e->getMessage());
		}
        
		return true;
	}
}