<?php
namespace Tropaframework\Buy\Model;
use Tropaframework\Buy\Model\Item\ModelSubitem;

class ModelItem extends \Tropaframework\Buy\Model\Item\ModelItem
{
    /**
     * verificar se há subitens
     * @return boolean
     */
    public function hasSubitens()
    {
        return count($this->getSubitens()) ? true : false;
    }
    
    /**
     * verificar se id_categoria existe em subitens
     * @return bool
     */
    public function hasSubitensIdCategoria($id)
    {
        foreach( $this->subitens as $subitem )
        {
            if( $subitem->getIdCategoria() == $id ) return true;
        }
    
        return false;
    }
    
    /**
     * buscar imagem por posição no array
     * @param number $position
     * @return string
     */
    public function getImagemFromPosition($position = 0)
    {
        return $this->imagens[$position];
    }
    
    /**
     * calcular data da devolução
     * @param string $format
     * @return string
     */
    public function getDataDevolucao($format = "d/m/Y")
    {
        return date($format, strtotime($this->data_evento . " + " . $this->periodo . " DAYS"));
    }
    
    /**
     * buscar subitem por id
     * @param int $id
     * @return \Tropaframework\Buy\Model\Item\ModelSubitem
     */
    public function getSubitemFromId($id)
    {
        foreach( $this->subitens as $subitem )
        {
            if( $subitem->getId() == $id )
            {
                return $subitem;
            }
        }
        
        return new ModelSubitem();
    }
    
    /**
     * buscar subitem por id da categoria
     * @param int $id
     * @return \Tropaframework\Buy\Model\Item\ModelSubitem
     */
    public function getSubitemFromCategory($id)
    {
        foreach( $this->subitens as $subitem )
        {
            if( $subitem->getIdCategoria() == $id )
            {
                return $subitem;
            }
        }
    
        return new ModelSubitem();
    }
    
	/**
	 * retornar todas as categorias dos subitens separando por vírgula
	 * @return string
	 */
	public function getSubitensCategorias(bool $format = false)
	{
	    $return = array();
	    foreach( $this->subitens as $subitem )
	    {
	        $return[] = $subitem->getCategoria();
	    }
	    
	    $return = implode(", ", $return);
	    return $format ? \Tropaframework\Helper\Convert::removeEspecialChars($return, true) : $return;
	}
	
	/**
	 * retornar valor total dos subitens
	 * @return string
	 */
	public function getSubitensTotal(array $only = array(), bool $format = false)
	{
	    $return = 0;
	    foreach( $this->subitens as $subitem )
	    {
	        if( empty($only) || in_array($subitem->getIdCategoria() ,$only) )
	        {
	           $return += $subitem->getSubtotal();
	        }
	    }
	     
	    return $format ? \Tropaframework\Helper\Convert::toReal($return) : $return;
	}
	
	/**
	 * retornar ids dos subitens separados por vírgula
	 * @return string
	 */
	public function getSubitensIds(array $only = array())
	{
	    $return = $this->getSubitensIdsInArray($only);
	    return implode(", ", $return);
	}
	
	/**
	 * retornar ids dos subitens em array
	 * @return array
	 */
	public function getSubitensIdsInArray(array $only = array())
	{
	    $return = array();
	    foreach( $this->subitens as $subitem )
	    {
	        if( empty($only) || in_array($subitem->getIdCategoria() ,$only) )
	        {
	            $return[] = $subitem->getId();
	        }
	    }
	
	    return $return;
	}
	
	/**
	 * retornar subitens criptografados
	 * @param array $only
	 * @return string
	 */
	public function getSubitensEncode(array $only = array())
	{
	    $array = array();
	    foreach( $this->subitens as $subitem )
	    {
	        if( empty($only) || in_array($subitem->getIdCategoria() ,$only) )
	        {
	            $array[] = array(
	                "id" => $subitem->getId(),
	                "tamanho" => $subitem->getTamanho(),
	            );
	        }
	    }
	
	    return $this->encode($array);
	}
	
	/**
	 * calcular o subtotal multiplicando o preço pela quantidade
	 * @return number
	 */
	public function getSubtotal($format = false)
	{
	    $value = $this->getPreco() * $this->getQuantidade();
	    return $format ? \Tropaframework\Helper\Convert::toReal($value) : $value;
	}
	
	/**
	 * definir o tamanho de todos os subitens com base no tamanho modelo
	 * @param string $tamanho_modelo
	 * @return \Tropaframework\Buy\Model\ModelItem
	 */
	public function setSubitensTamanhos($tamanho_modelo=null)
	{
	    if( empty($tamanho_modelo) ) return $this;
	    
	    foreach( $this->subitens as $subitem )
	    {
	        if( $subitem->getIdCategoria() == \Model\ModelFiltro::CATEGORIA_BLAZER )
	        {
	            $subitem->setTamanho($tamanho_modelo - 2);
	        }
	        if( $subitem->getIdCategoria() == \Model\ModelFiltro::CATEGORIA_CALCA )
	        {
	            $subitem->setTamanho($tamanho_modelo);
	        }
	        if( $subitem->getIdCategoria() == \Model\ModelFiltro::CATEGORIA_CAMISA )
	        {
	            $subitem->setTamanho("G");
	        }
	        if( $subitem->getIdCategoria() == \Model\ModelFiltro::CATEGORIA_COLETE )
	        {
	            $subitem->setTamanho("G");
	        }
	        if( $subitem->getIdCategoria() == \Model\ModelFiltro::CATEGORIA_SUSPENSORIO )
	        {
	            $subitem->setTamanho($tamanho_modelo);
	        }
	        if( $subitem->getIdCategoria() == \Model\ModelFiltro::CATEGORIA_GRAVATA )
	        {
	            $subitem->setTamanho("U");
	        }
	        if( $subitem->getIdCategoria() == \Model\ModelFiltro::CATEGORIA_MEIA )
	        {
	            $subitem->setTamanho("U");
	        }
	        if( $subitem->getIdCategoria() == \Model\ModelFiltro::CATEGORIA_SAPATO )
	        {
	            $subitem->setTamanho("42");
	        }
	    }
	
	    return $this;
	}
	
	/**
	 * adicionar subitens com base no HASH
	 * @param string $hash
	 * @return \Tropaframework\Buy\Model\ModelItem
	 */
	public function setSubitensFromHash($hash)
	{
	    $result = $this->decode($hash);
	    
	    foreach( $result as $row )
	    {
	        $subitem = new \Tropaframework\Buy\Model\Item\ModelSubitem();
	        $subitem->populate($row);
	        $this->addSubitem($subitem);
	    }
	
	    return $this;
	}
	
	/**
	 * deletar subitem por id
	 * @param int $id
	 * @return bool
	 */
	public function deleteSubitemFromId($id)
	{
	    foreach( $this->subitens as $key => $subitem )
	    {
	        if( $subitem->getId() == $id )
	        {
	            unset($this->subitens[$key]);
	            return true;
	        }
	    }
	
	    return false;
	}
	
	/**
	 * deletar subitem por id categoria
	 * @param int $id_categoria
	 * @return bool
	 */
	public function deleteSubitemFromIdCategoria($id_categoria)
	{
	    foreach( $this->subitens as $key => $subitem )
	    {
	        if( $subitem->getIdCategoria() == $id_categoria )
	        {
	            unset($this->subitens[$key]);
	            return true;
	        }
	    }
	
	    return false;
	}
}