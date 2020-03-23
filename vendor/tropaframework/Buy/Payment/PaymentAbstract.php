<?php
namespace Tropaframework\Buy\Payment;

use Tropaframework\Buy\Model\ModelCart;
use Tropaframework\Buy\Payment\Model\Result\ModelRequestPayment;

/**
 * @author Vitor Deco
 */
abstract class PaymentAbstract
{
	/**
	 * @var int
	 */
	const STATUS_PENDING = 0;
	const STATUS_PAID = 1;
	const STATUS_CANCELED = 2;
	
	/**
	 * identifica o meio de pagamento utilizado 
	 * @var string
	 */
	private $payment_type = null;
	
	/**
	 * informações de pagamento
	 * @var array
	 */
	public $payment_info = array();
	
	/**
	 * código que identifica a transação
	 * @var string
	 */
	protected $payment_id = null;
	
	/**
	 * define a url de retorno
	 * @var string
	 */
	protected $payment_redirect_url = null;
	
	/**
	 * identifica em qual ambiente está
	 * @var boolean
	 */
	protected $is_sandbox = false;
	
	/**
	 * construtor definindo qual o gateway de pagamento será usado
	 * @param string $payment_type
	 */
	public function __construct($payment_type)
	{
		$this->payment_type = $payment_type;
	}
	
	/**
	 * define o ambiente
	 * @param boolean $bool
	 * @return \Tropaframework\Shopping\Payment\PaymentAbstract
	 */
	public function setSandbox($bool=true)
	{
		$this->is_sandbox = (bool)$bool;
		return $this;
	}
	
	/**
	 * define o tipo de pagamento
	 * @param string $payment_type
	 */
	public function setPaymentType($value)
	{
	    $this->payment_type = $value;
	}
	
	public function getPaymentType()
	{
	    return $this->payment_type;
	}
	
	/**
	 * define uma identificação para essa transação
	 * @param int|string $payment_id
	 */
	public function setPaymentId($payment_id)
	{
		$this->payment_id = $payment_id;
	}
	
	public function getPaymentId()
	{
		return $this->payment_id;
	}
	
	/**
	 * define uma URL para retorno após concluir o processo
	 * @param string $payment_redirect_url
	 */
	public function setRedirectUrl($payment_redirect_url)
	{
		$this->payment_redirect_url = $payment_redirect_url;
	}
	
	public function getRedirectUrl()
	{
		return $this->payment_redirect_url;
	}
	
	/**
	 * recuperar uma transação do gateway
	 * @param string $code
	 */
	abstract public function getTransaction($code);
	
	/**
	 * faz a requisição de pagamento
	 * @param ModelCart $cart
	 * @return ModelRequestPayment
	 */
	abstract public function requestPayment(ModelCart $cart);
	
}