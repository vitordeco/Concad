<?php
namespace Tropaframework\Buy\Payment\Pagseguro;

use mysql_xdevapi\Exception;
use Requests;
use Tropaframework\Buy\Payment\PaymentAbstract;
use Tropaframework\Buy\Model\ModelCart;
use Tropaframework\Buy\Payment\Model\Result\ModelRequestPayment;

include($_SERVER['DOCUMENT_ROOT'] . '/../vendor/tropaframework/Requests/Requests.php');

abstract class PaymentPagseguro extends PaymentAbstract
{
	protected $production = null;
	protected $sandbox = null;
	protected $email = null;
	protected $token = null;
	private $request;

	public function __construct(array $config)
	{
	    //definir configurações
	    foreach( $config as $key => $value )
	    {
	        $this->$key = $value;
	    }

	    //iniciar o autoloader
        Requests::register_autoloader();

	    //definir o request
        $this->request = new \Requests_Session($this->production);
        $this->request->headers['Content-Type'] = 'application/x-www-form-urlencoded';

        //definir ambiente
        if( ($config['env'] == 'sandbox') )
        {
            $this->setSandbox();
        }

	    //definir tipo de pagamento
	    parent::__construct("pagseguro");
	}
	
	public function setSandbox($bool=true)
	{
        //definir o request
        $this->request = new \Requests_Session($this->sandbox);
        $this->request->headers['Content-Type'] = 'application/x-www-form-urlencoded';

	    parent::setSandbox($bool);
	    return $this;
	}
	
	public function getTransaction($code)
	{
		try 
		{
            $this->request->data = array(
                'reference' => "CN12-" . $code,
            );

            $url = "v2/transactions?email=" . $this->email . "&token=" . $this->token;
            $response = $this->request->get($url);

            if( $response->status_code !== 200 )
            {
                throw new \Exception("Houve um problema no PAGSEGURO, tente novamente mais tarde!");
            }

            $xml = simplexml_load_string($response->body);
            $status = $xml->transactions->transaction->status;

			return ($status == '3') ? true : false;

        } catch ( \Exception $e ) {
            throw new \Exception($e->getMessage());
        }    
        
	}

    public function getTransactionDetail($transactionCode)
    {
        try
        {
            $url = "v3/transactions/" . $transactionCode . "?email=" . $this->email . "&token=" . $this->token;
            $response = $this->request->get($url);

            if( $response->status_code !== 200 )
            {
                throw new \Exception("Houve um problema no PAGSEGURO, tente novamente mais tarde!");
            }

            $xml = simplexml_load_string($response->body);
            return $xml;

        } catch ( \Exception $e ) {
            throw new \Exception($e->getMessage());
        }

    }
	
	public function requestPayment(ModelCart $cart)
	{
        try
        {
            $redirectURL = 'http://www.congressonursing.com.br/inscricao/sucesso/' . $cart->getReferencia();
//            echo $redirectURL; exit;

            $this->request->data = array(
                'currency' => 'BRL',
                'reference' => "CN12-" . $cart->getReferencia(),

                'itemId1' => $cart->getItemByPosition()->getId(),
                'itemDescription1' => $this->clean($cart->getItemByPosition()->getDescricao()),
                'itemAmount1' => $cart->getItemByPosition()->getPreco(),
                'itemQuantity1' => '1',
                'itemWeight1' => '1000',

                'senderName' => $cart->getComprador()->getNome(true),
                'senderAreaCode' => $cart->getComprador()->getTelefoneDDD(),
                'senderPhone' => $cart->getComprador()->getTelefoneNumber(),
                'senderCPF' => $cart->getComprador()->getDocumento(true),
                'senderBornDate' => $cart->getComprador()->getNascimento(),
                'senderEmail' => $cart->getComprador()->getEmail(),

                'shippingType' => '1',
                'shippingAddressPostalCode' => $cart->getComprador()->getCep(),
                'shippingAddressStreet' => $this->clean($cart->getComprador()->getLogradouro()),
                'shippingAddressNumber' => $cart->getComprador()->getNumero(),
                'shippingAddressComplement' => $cart->getComprador()->getComplemento(),
                'shippingAddressDistrict' => $this->clean($cart->getComprador()->getBairro()),
                'shippingAddressCity' => $cart->getComprador()->getCidade(),
                'shippingAddressState' => $this->clean($cart->getComprador()->getEstado()),
                'shippingAddressCountry' => 'BRA',

                'redirectURL' => $redirectURL,
//                'extraAmount'=>'-0.01',
//                'notificationURL'=>'https://url_de_notificacao.com',
//                'maxUses'=>'1',
//                'maxAge'=>'3000',
//                'shippingCost'=>'0.00',
            );
//            echo'<pre>'; print_r($this->request->data); exit;

            $url = "v2/checkout?email=" . $this->email . "&token=" . $this->token;
            $response = $this->request->post($url);
//            echo'<pre>'; print_r($response->body); exit;

            if( $response->status_code !== 200 )
            {
                throw new \Exception("Houve um problema no PAGSEGURO, tente novamente mais tarde!");
            }

            $xml = simplexml_load_string($response->body);
            $code = current($xml->code);
            if( empty($code) )
            {
                throw new \Exception("Houve um problema ao gerar o link de pagamento, tente novamente mais tarde!");
            }

            return $code;

        } catch ( \Exception $e) {
            throw new \Exception($e->getMessage());
        }
	    
	}

	public function getRedirectPayment($code)
    {
        $sandbox = $this->is_sandbox ? "sandbox." : null;
        return "https://" . $sandbox . "pagseguro.uol.com.br/v2/checkout/payment.html?code=$code";
    }

    private function clean($value)
    {
        $value = \Tropaframework\Helper\Convert::removeEspecialChars($value, true);
        $value = preg_replace('/\d/', '', $value);
        $value = preg_replace('/[\n\t\r]/', ' ', $value);
        $value = preg_replace('/\s(?=\s)/', '', $value);
        $value = trim($value);
        return $value;
    }
}