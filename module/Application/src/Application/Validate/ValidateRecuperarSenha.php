<?php
namespace Application\Validate;

use Model\ModelLogin;

class ValidateRecuperarSenha extends ValidateAbstract
{
	public static function validate($params)
	{
		$validate = new \Tropaframework\Helper\Validate();
        
		try
		{
		    //validar campos obrigatórios
    	    $required = ["senha", "senha_confirmar"];
    	    if( !$validate::isArrayNotEmpty($params, $required) )
    	    {
    	        throw new \Exception(self::ERROR_REQUIRED);
    	    }

            //validar força de senha
            if( !$validate::passwordForce($params['senha']) )
            {
                throw new \Exception(self::ERROR_PASSWORD_FORCE);
            }

            //validar senha
            if( $params['senha'] != $params['senha_confirmar'] )
            {
                throw new \Exception(self::ERROR_SENHA_CONFIRMAR);
            }

		} catch( \Exception $e ){
			throw new \Exception($e->getMessage());
		}
        
		return true;
	}
}