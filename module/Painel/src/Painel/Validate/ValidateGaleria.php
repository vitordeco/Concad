<?php
namespace Painel\Validate;

class ValidateGaleria extends ValidateAbstract
{
	public static function validate($params)
	{
		$validate = new \Tropaframework\Helper\Validate();
        
		try
		{
            //validar campos obrigatórios
            $required = ["galeria", "ordem"];
            if( !$validate::isArrayNotEmpty($params, $required) )
            {
                throw new \Exception(self::ERROR_REQUIRED);
            }

            //validar imagem
            if( !count(array_filter($params["images"])) )
            {
                throw new \Exception("Adicione no mínimo 1 imagem!");
            }

		} catch( \Exception $e ){
			throw new \Exception($e->getMessage());
		}
        
		return true;
	}
}