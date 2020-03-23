<?php
namespace Tropaframework\Buy\Payment\Model\Result;
use Tropaframework\Buy\Payment\Model\ModelAbstract;
use Tropaframework\Buy\Payment\Model\ModelCreditCard;

class ModelRequestPayment extends ModelAbstract
{
	protected $id;
	protected $status;
	protected $detail;
    
	/**
	 * @var ModelCreditCard
	 */
	protected $creditcard;
	
	public function getId()
	{
	    return $this->id;
	}
	public function setId($value)
	{
	    $this->id = $value;
	}
    
	public function getStatus()
	{
	    return $this->status;
	}
	public function setStatus($value)
	{
	    $this->status = $value;
	}
    
	public function getDetail()
	{
	    return $this->detail;
	}
	public function setDetail($value)
	{
	    $this->detail = $value;
	}
	
	public function hasCreditcard()
	{
	    return !empty($this->getCreditcard()->getHash()) ? true : false;
	}
	public function getCreditcard()
	{
	    if( empty($this->creditcard) )
	    {
	        return new ModelCreditCard();
	    }
	
	    return $this->creditcard;
	}
	public function setCreditcard(ModelCreditCard $value)
	{
	    $this->creditcard = $value;
	}
	
	public function toArray()
	{
	    $array = parent::toArray();
	    $array['creditcard'] = $this->getCreditcard()->toArray();
	    
	    return $array;
	}
}