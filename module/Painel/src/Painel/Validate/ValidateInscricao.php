<?php
namespace Painel\Validate;

class ValidateInscricao extends ValidateAbstract
{
	public static function validate($params)
	{
		$validate = new \Tropaframework\Helper\Validate();
        
		try
		{
		    //validar campos obrigatórios
    	    $required = ["nome", "telefone", "email", "cpf", "nascimento", "cep", "logradouro", "numero", "bairro", "cidade", "estado"];
    	    if( !$validate::isArrayNotEmpty($params, $required) )
    	    {
    	        throw new \Exception(self::ERROR_REQUIRED);
    	    }

    	    //validar e-mail
    	    if( !$validate::isEmail($params['email']) )
    	    {
    	        throw new \Exception(self::ERROR_EMAIL);
    	    }
    	    
		    //validar CPF
    	    if( !$validate::isCPF($params['cpf']) )
    	    {
    	        throw new \Exception(self::ERROR_CPF);
    	    }

		} catch( \Exception $e ){
			throw new \Exception($e->getMessage());
		}
        
		return true;
	}
}