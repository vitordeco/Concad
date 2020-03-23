<?php
namespace Application\Validate;

use Model\ModelLogin;
use Model\ModelTableGateway;
use Model\ModelUsuario;

class ValidateLogin extends ValidateAbstract
{
    public static function validate($params, $tb, $adapter)
    {
        try
        {
            //validar login
            $where = "(login.status = '" . ModelTableGateway::STATUS_ATIVO . "')";
            $where .= " AND (usuario.status = '" . ModelTableGateway::STATUS_ATIVO . "')";
            $where .= " AND (login.nivel = '" . ModelLogin::NIVEL_FRONTEND . "')";
            $where .= " AND (login.login = '" . $params['lg'] . "')";
            $where .= " AND (login.senha = '" . md5($params['pw']) . "')";
            $model = new ModelUsuario($tb, $adapter);
            $result = $model->where($where)->current()->get();
            if( !$result )
            {
                throw new \Exception(ValidateAbstract::ERROR_LOGIN);
            }

        } catch( \Exception $e ){
            throw new \Exception($e->getMessage());
        }

        return true;
    }
}