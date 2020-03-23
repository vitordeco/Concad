<?php
namespace Application\Validate;

use Model\ModelLogin;

class ValidateInscricao extends ValidateAbstract
{
	public static function validate($params)
	{
		$validate = new \Tropaframework\Helper\Validate();
        
		try
		{
		    //validar campos obrigatórios
    	    $required = ["nome", "sobrenome", "telefone", "email", "cpf", "nascimento", "cep", "logradouro", "numero", "bairro", "cidade", "estado"];
    	    if( !$validate::isArrayNotEmpty($params, $required) )
    	    {
    	        throw new \Exception(self::ERROR_REQUIRED);
    	    }

            //validar tipo
            if( empty($params['id_tipo']) || !in_array($params['id_tipo'], [1,2,3]) )
            {
                throw new \Exception("Escolha o tipo de inscrição!");
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

            //validar tipo 1
            if( ($params['id_tipo'] == 1) && empty($params['coren']) )
            {
                throw new \Exception("O campo COREN é obrigatório para enfermeiros!");
            }

            //validar tipo 2
            if( ($params['id_tipo'] == 2) && empty($params['numero_contrato']) && empty($params['numero_carteira_estudante']) )
            {
                throw new \Exception("O campo COREN é obrigatório para enfermeiros!");
            }

    	    //validar termos de uso
//    	    if( !isset($params['termos']) )
//    	    {
//    	        throw new \Exception(self::ERROR_TERMOS);
//    	    }
    	    
		} catch( \Exception $e ){
			throw new \Exception($e->getMessage());
		}
        
		return true;
	}
	
	public static function validateEmailDuplicado($email, $tb, $adapter)
	{
	    $validate = new \Tropaframework\Helper\Validate();
	
	    try
	    {
	        //validar email duplicado
	        $where = "(login.login = '$email')";
	        $model = new ModelLogin($tb, $adapter);
	        $result = $model->where($where)->get();
	        if( count($result) ) throw new \Exception(self::ERROR_EMAIL_DUPLICIDADE);
	        
	    } catch( \Exception $e ){
	        throw new \Exception($e->getMessage());
	    }
        
	    return true;
	}
}