<?php
namespace Model;
use Model\ModelTableGateway;

class ModelLogin extends ModelTableGateway
{
    const NIVEL_FRONTEND = "1";
    const NIVEL_BACKEND = "2";
    const SESSION_TOKEN = "AuthFront";

	public function __construct($tb, $adapter)
	{
		$this->tb = $tb;
		parent::__construct($this->tb->login, $adapter);
	}
	
	public function get()
	{
	    $qry = $this->sql->select();
	    $qry->from(["login" => $this->tableName]);
	
	    return $this->execute($qry);
	}
	
	public function save($set, $id=null)
	{
		$set = $this->saveBefore($set);
		return parent::save($set, $id);
	}
	
	private function saveBefore($set)
	{
	    if( !empty($set['senha']) )
	    {
	        $set['senha'] = md5($set['senha']);
	    }
	
	    return $set;
	}

    public static function login($login, $tb, $adapter)
    {
        $result = self::getUserData($login, $tb, $adapter);

        if( !empty($result) )
        {
            $session = new \Zend\Session\Container(self::SESSION_TOKEN);
            $session->offsetSet('me', $result);

            //verificar cookie
//            if( !empty($_POST['remember']) && ($_POST['remember'] == 'true') )
//            {
//                $auth = \Tropaframework\Security\Security::encrypt($result['login'], 'AuthCookie');
//                setcookie("auth", $auth, strtotime('+30 days'));
//            }

            return true;

        } else {
            return false;
        }
    }

    /**
     * verificar se usuário está logado
     * @param $me
     * @return bool
     */
    public static function loginCheck($me)
    {
        return ( $me->nivel == ModelLogin::NIVEL_FRONTEND ) ? true : false;
    }

    public static function logout()
    {
        //destroy session
        $session = new \Zend\Session\Container(self::SESSION_TOKEN);
        $session->offsetUnset('me');
        setcookie("auth", '', 1, '/');
        return true;
    }

    public static function getUserData($login, $tb, $adapter)
    {
        //selecionar usuario
        $where = "(login.login = '" . $login . "')";
        $model = new ModelUsuario($tb, $adapter);
        $result = $model->where($where)->current()->get();
        if( empty($result) ) return false;

        //definir apelido
        $result->apelido = \Tropaframework\Helper\Convert::breakText($result->nome);

        //selecionar plano
        //@todo

        return $result;
    }
}