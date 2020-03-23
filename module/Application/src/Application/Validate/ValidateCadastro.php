<?php
namespace Application\Validate;

use Model\ModelLogin;

class ValidateCadastro extends ValidateAbstract
{
	public static function validate($params, $tb, $adapter)
	{
		$validate = new \Tropaframework\Helper\Validate();
        
		try
		{
		    //validar campos obrigatórios
    	    $required = ["nome", "cpf_cnpj", "email", "senha"];
    	    if( !$validate::isArrayNotEmpty($params, $required) )
    	    {
    	        throw new \Exception(self::ERROR_REQUIRED);
    	    }

    	    //validar e-mail
    	    if( !$validate::isEmail($params['email']) )
    	    {
    	        throw new \Exception(self::ERROR_EMAIL);
    	    }

    	    //validar e-mail duplicado
            if( !self::validateEmailDuplicado($params['email'], $tb, $adapter) )
            {
                throw new \Exception(self::ERROR_EMAIL_DUPLICIDADE);
            }

		    //validar CPF ou CNPJ
    	    if( !$validate::isCPF($params['cpf_cnpj']) && !$validate::isCNPJ($params['cpf_cnpj']) )
    	    {
    	        throw new \Exception(self::ERROR_CPF_CNPJ);
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
        $where = "(login.status = '1')";
        $where .= " AND (login.login = '$email')";
        $model = new ModelLogin($tb, $adapter);
        $result = $model->where($where)->get();
        return count($result) ? false : true;
	}
}