<?php
namespace Tropaframework\Buy\Model;
use Tropaframework\Buy\Model\ModelItem;
use Tropaframework\Buy\Shipment\Model\Result\ModelCalcularFreteItem;

class ModelCart extends \Tropaframework\Buy\Model\Cart\ModelCart
{
    public function __construct(bool $session_start = true)
    {
        if( $session_start )
        {
            $cart = \Tropaframework\Session\Session::get('cart');
            $this->populate($cart);
        }
    }
    
    public function save()
    {
        $cart = $this->toArrayClean();
        return \Tropaframework\Session\Session::set('cart', $cart);
    }
    
    public function destroy()
    {
        return \Tropaframework\Session\Session::unset('cart');
    }
    
    /**
     * deletar item
     * @param string $uid
     * @return boolean
     */
    public function deleteItemByUid($uid)
    {
        foreach( $this->itens as $key => $item )
        {
            if( $item->getUid() == $uid )
            {
                unset($this->itens[$key]);
                $this->save();
                return true;
            }
        }
        
        return false;
    }
    
    public function ___getItemById($value)
    {
        foreach( $this->itens as $item )
        {
            if( $item->getId() == $value )
            {
                return $item;
            }
        }
         
        $item = new Item();
        return $item;
    }
    
    public function ___getItemIds()
    {
        $ids = array();
    
        foreach( $this->itens as $item )
        {
            $ids[$item->getId()] = $item->getId();
        }
         
        return $ids;
    }
    
    /**
     * buscar item por posição no array
     * @param number $position
     * @return \Tropaframework\Buy\Model\ModelItem|\Tropaframework\Buy\Model\ModelItem
     */
    public function getItemByPosition($position = 0)
    {
        if( !empty($this->itens[$position]) )
        {
            return $this->itens[$position];
        }
         
        return new ModelItem();
    }
    
    /**
     * calcular o subtotal
     * @param bool $format
     * @return number
     */
    public function getSubtotal(bool $format = false)
    {
        $return = 0;
        foreach( $this->itens as $item )
        {
            $return += $item->getSubitensTotal();
        }
    
        return $format ? \Tropaframework\Helper\Convert::toReal($return) : $return;
    }
    
    /**
     * calcular o total com descontos e frete
     * @param string $format
     * @return number
     */
    public function getTotal(bool $format = false)
    {
        $return = $this->getSubtotal() + $this->getFrete()->getValor() - $this->getDesconto();
        
        return $format ? \Tropaframework\Helper\Convert::toReal($return) : $return;
    }
    
    /**
     * calcular o total parcelado
     * @param int $parcelas
     * @param bool $format
     * @return number
     */
    public function getParcelado(int $parcelas = 6, bool $format = false)
    {
        $return = $this->getTotal() / $parcelas;
    
        return $format ? \Tropaframework\Helper\Convert::toReal($return) : $return;
    }
    
    /**
     * definir altura de todos os itens
     * @return number
     */
    public function getAltura()
    {
        $return = 0;
        foreach( $this->itens as $item )
        {
            if( $item->getAltura() > $return )
            {
                $return = $item->getAltura();
            }
        }
        return $return;
    }
    
    /**
     * definir largura de todos os itens
     * @return number
     */
    public function getLargura()
    {
        $return = 0;
        foreach( $this->itens as $item )
        {
        if( $item->getLargura() > $return )
            {
                $return = $item->getLargura();
            }
        }
        return $return;
    }
    
    /**
     * definir comprimento de todos os itens
     * @return number
     */
    public function getComprimento()
    {
        $return = 0;
        foreach( $this->itens as $item )
        {
            $return += $item->getComprimento();
        }
        return $return;
    }
    
    /**
     * definir peso de todos os itens
     * @return number
     */
    public function getPeso()
    {
        $return = 0;
        foreach( $this->itens as $item )
        {
            $return += $item->getPeso();
        }
        return $return;
    }
     
    public function getShipmentItemByUid($uid)
    {
        foreach( $this->getShipmentItens() as $item )
        {
            if( $item->getUid() == $uid )
            {
                return $item;
            }
        }
         
        return new ModelCalcularFreteItem();
    }
    
    /**
     * validar item
     * @throws \Exception
     * @return boolean
     */
    public function validateItem(ModelItem $item)
    {
        try
        {
            if( empty($item->getCep()) )
            {
                throw new \Exception("Informe o CEP de destino!");
            }
            if( empty($item->getDataEvento()) )
            {
                throw new \Exception("Informe a data do evento!");
            }
            if( empty($item->getPeriodo()) )
            {
                throw new \Exception("Informe o período!");
            }
            foreach( $item->getSubitens() as $subitem )
            {
                if( empty($subitem->getId()) )
                {
                    throw new \Exception("Item inválido!");
                }
                if( empty($subitem->getTamanho()) )
                {
                    throw new \Exception("Você precisa definir o tamanho de todos os itens do traje antes de continuar!");
                }
            }
            
            return true;
    
        } catch( \Exception $e ) {
            throw new \Exception($e->getMessage());
        }
    }
    
    public function calcularFrete(array $config)
    {
        $package = new \Tropaframework\Buy\Shipment\Model\ModelPackage();
        $package->setAltura($this->getAltura());
        $package->setLargura($this->getLargura());
        $package->setComprimento($this->getComprimento());
        $package->setPeso($this->getPeso());
        
        $sender = new \Tropaframework\Buy\Shipment\Model\ModelSender();
        $sender->setCep($config['cep_origem']);
        
        $recipient = new \Tropaframework\Buy\Shipment\Model\ModelRecipient();
        $recipient->setCep($this->getCepDestino());
        
        $shipment = new \Tropaframework\Buy\Shipment\ShipmentCorreio($config);
        $result = $shipment->calcularFrete($package, $sender, $recipient);
        foreach( $result->getItens() as $item )
        {
            $this->addShipmentItem($item);
        }
        
        return $this;
    }
    
    public function toArrayClean()
    {
        $array = parent::toArrayClean();
        
        if( count($array['itens']) )
        {
            foreach( $array['itens'] as &$item )
            {
                $item = $item->toArrayClean();
    
                foreach( $item['subitens'] as &$subitem )
                {
                    $subitem = $subitem->toArrayClean();
                }
            }
        }
        
        return $array;
    }
}