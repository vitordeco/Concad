<?php
namespace Tropaframework\Concad\Sintegra;

use Requests;
use Tropaframework\Concad\ConcadAbstract;
include($_SERVER['DOCUMENT_ROOT'] . '/../vendor/tropaframework/Requests/Requests.php');

abstract class ConcadSintegra extends ConcadAbstract
{
    protected $url = null;
    protected $token = null;

    /**
     * @var \Requests_Session
     */
	private $request;

	public function __construct(array $config = array())
	{
	    //definir configurações
        $this->url = 'https://www.sintegraws.com.br';
        $this->token = 'AE7C2F49-F5F9-46F5-818B-27029223245F';
        foreach( $config as $key => $value )
	    {
	        $this->$key = $value;
	    }

	    //iniciar o autoloader
        Requests::register_autoloader();

	    //definir o request
        $this->request = new \Requests_Session($this->url);
        $this->request->headers['Content-Type'] = 'application/json';
        $this->request->options = array('timeout' => 99999);

        //definir ambiente
        if( ($config['env'] == 'sandbox') )
        {
            $this->setSandbox();
        }

	    //definir tipo de pagamento
	    parent::__construct("CONCAD");
	}
	
	public function apiConsultaSaldo()
	{
		try 
		{
            $this->request->data = array(
                'token' => $this->token,
            );

            $url = "api/v1/consulta-saldo.php";
            $response = $this->request->get($url);
            $result = json_decode($response->body);
            if( $result->status !== 'OK' ) throw new \Exception(self::MESSAGE_ERROR_DEFAULT);

			return $result;

        } catch ( \Exception $e ) {
            throw new \Exception($e->getMessage());
        }    
	}

    /**
     * @param string $document 99999999999999
     */
    public function apiReceitaFederalCNPJ($document)
    {
        try
        {
            $this->request->data = array(
                'token' => $this->token,
                'plugin' => 'RF',
                'cnpj' => $document,
            );

            $url = "api/v1/execute-api.php";
            $response = $this->request->get($url);
//            echo '<pre>'; print_r($response); exit;
            $result = json_decode($response->body);
//            echo '<pre>'; print_r($result); exit;
            if( $result->status !== 'OK' ) throw new \Exception(self::MESSAGE_ERROR_DEFAULT);

            return $result;

        } catch ( \Exception $e ) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param string $document 99999999999
     * @param string $birthday DDMMYYYY
     */
    public function apiReceitaFederalCPF($document, $birthday)
    {
        try
        {
            $this->request->data = array(
                'token' => $this->token,
                'plugin' => 'CPF',
                'cpf' => $document,
                'data-nascimento' => $birthday,
            );

            $url = "api/v1/execute-api.php";
            $response = $this->request->get($url);
//            echo '<pre>'; print_r($response); exit;
            $result = json_decode($response->body);
//            echo '<pre>'; print_r($result); exit;
            if( $result->status !== 'OK' ) throw new \Exception(self::MESSAGE_ERROR_DEFAULT);

            return $result;

        } catch ( \Exception $e ) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param string $document 99999999999999
     */
    public function apiSintegra($document)
    {
        try
        {
            $this->request->data = array(
                'token' => $this->token,
                'plugin' => 'ST',
                'cnpj' => $document,
            );

            $url = "api/v1/execute-api.php";
            $response = $this->request->get($url);
//            echo '<pre>'; print_r($response); exit;
            $result = json_decode($response->body);
//            echo '<pre>'; print_r($result); exit;
            if( $result->status !== 'OK' ) throw new \Exception(self::MESSAGE_ERROR_DEFAULT);

            return $result;

        } catch ( \Exception $e ) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param string $document 99999999999999
     */
    public function apiSimplesNacional($document)
    {
        try
        {
            $this->request->data = array(
                'token' => $this->token,
                'plugin' => 'SN',
                'cnpj' => $document,
            );

            $url = "api/v1/execute-api.php";
            $response = $this->request->get($url);
//            echo '<pre>'; print_r($response); exit;
            $result = json_decode($response->body);
//            echo '<pre>'; print_r($result); exit;
            if( $result->status !== 'OK' ) throw new \Exception(self::MESSAGE_ERROR_DEFAULT);

            return $result;

        } catch ( \Exception $e ) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param string $document 99999999999999
     */
    public function apiSuframa($document)
    {
        try
        {
            $this->request->data = array(
                'token' => $this->token,
                'plugin' => 'SF',
                'cnpj' => $document,
            );

            $url = "api/v1/execute-api.php";
            $response = $this->request->get($url);
//            echo '<pre>'; print_r($response); exit;
            $result = json_decode($response->body);
//            echo '<pre>'; print_r($result); exit;
            if( $result->status !== 'OK' ) throw new \Exception(self::MESSAGE_ERROR_DEFAULT);

            return $result;

        } catch ( \Exception $e ) {
            throw new \Exception($e->getMessage());
        }
    }
}