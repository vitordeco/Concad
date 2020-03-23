<?php
namespace Tropaframework\Buy\Model\Cart;
use Tropaframework\Buy\Model\ModelAbstract;
use Tropaframework\Buy\Model\ModelItem;
use Tropaframework\Buy\Shipment\Model\Result\ModelCalcularFreteItem;
use Tropaframework\Buy\Model\ModelRecipient;
use Tropaframework\Buy\Payment\Model\ModelCreditCard;

class ModelCart extends ModelAbstract
{
    protected $referencia;
    protected $cep_destino;
    protected $data_evento;
    protected $periodo;
    protected $desconto;

    /**
     * @var ModelCreditCard
     */
    protected $creditcard;
    
    /**
     * @var ModelRecipient
     */
    protected $comprador;
    
    /**
     * @var ModelCalcularFreteItem
     */
    protected $frete;
    
    /**
     * @var ModelItem
     */
	protected $itens = array();

	/**
	 * @var ModelCalcularFreteItem
	 */
	protected $shipment_itens = array();

    public function getReferencia()
    {
        return $this->referencia;
    }
    public function setReferencia($value)
    {
        $this->referencia = $value;
    }

	public function getCepDestino()
	{
	    return $this->cep_destino;
	}
	public function setCepDestino($value)
	{
	    $this->cep_destino = $value;
	}
	
	public function hasDataEvento()
	{
	    return !empty($this->data_evento) ? true : false;
	}
	public function getDataEvento($format = "d/m/Y")
	{
	    return !empty($this->data_evento) ? date($format, strtotime($this->data_evento)) : null;
	}
	public function setDataEvento($value)
	{
	    $value = implode('-', array_reverse(explode("/", $value)));
	    $this->data_evento = $value;
	}
	
	public function getPeriodo()
	{
	    return $this->periodo;
	}
	public function setPeriodo($value)
	{
	    $this->periodo = $value;
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
	
	public function getComprador()
	{
	    if( empty($this->comprador) )
	    {
	        return new ModelRecipient();
	    }
	    
	    return $this->comprador;
	}
	public function setComprador(ModelRecipient $value)
	{
	    $this->comprador = $value;
	}
	
	public function hasFrete()
	{
	    return !empty($this->getFrete()->getUid()) ? true : false;
	}
	public function getFrete()
	{
	    if( empty($this->frete) )
	    {
	        return new ModelCalcularFreteItem();
	    }
	    
	    return $this->frete;
	}
	public function setFrete(ModelCalcularFreteItem $value)
	{
	    $this->frete = $value;
	}
	
	public function getDesconto(bool $format = false)
	{
	    return $format ? \Tropaframework\Helper\Convert::toReal($this->desconto) : $this->desconto;
	}
	public function setDesconto($value)
	{
	    $this->desconto = $value;
	}
	
    public function getItens()
    {
        return $this->itens;
    }
    public function addItem(ModelItem $item)
    {
        $this->itens[] = $item;
    }
    public function hasItens()
    {
        return !empty($this->itens) ? true : false;
    }
    public function countItens()
    {
    	return count($this->itens);
    }
    
    public function getShipmentItens()
    {
        return $this->shipment_itens;
    }
    public function addShipmentItem(ModelCalcularFreteItem $item)
    {
        $this->shipment_itens[] = $item;
    }
    
    public function populate($array)
    {
        $array = (array)$array;
        
	    foreach( $array as $key=>$value )
	    {
	        //add itens
            if( ($key == "itens") && is_array($value) )
	        {
	            foreach( $value as $row )
	            {
	                $item = new ModelItem();
	                $item->populate($row);
	                $this->addItem($item);
	            }
	        }
	    }
        
	    return parent::populate($array);
    }
}