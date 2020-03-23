<?php
namespace Application\Validate;

class ValidateConsulta extends ValidateAbstract
{
    public static function cpf($params)
    {
        $validate = new \Tropaframework\Helper\Validate();

        try
        {
            //validar CPF
            if( !$validate::isCPF($params['cpf'], true) )
            {
                throw new \Exception(self::ERROR_CPF);
            }

            //validar Data de Nascimento
            if( !$validate::isDate($params['nascimento'], true) )
            {
                throw new \Exception(self::ERROR_DATE);
            }

        } catch( \Exception $e ){
            throw new \Exception($e->getMessage());
        }

        return true;
    }

	public static function cnpj($params)
	{
		$validate = new \Tropaframework\Helper\Validate();
        
		try
		{
		    //validar CNPJ
    	    if( !$validate::isCNPJ($params['cnpj'], true) )
    	    {
    	        throw new \Exception(self::ERROR_CNPJ);
    	    }

		} catch( \Exception $e ){
			throw new \Exception($e->getMessage());
		}
        
		return true;
	}
	
}