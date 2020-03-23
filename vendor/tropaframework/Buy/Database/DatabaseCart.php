<?php
namespace Tropaframework\Buy\Database;
use Tropaframework\Buy\Model\ModelCart;
use Model\ModelProduto;
use Tropaframework\Buy\Model\ModelItem;

class DatabaseCart extends DatabaseAbstract
{
    /**
     * selecionar produtos no banco de dados completando os parametros do model
     * @param ModelCart $cart
     * @return \Tropaframework\Buy\Model\ModelCart
     */
    public function selectFromId(ModelCart $cart)
    {
        //loop nos itens
        foreach( $cart->getItens() as $item )
        {
            //selecionar produtos
            $where = "(produto.status = '" . ModelProduto::STATUS_ATIVO . "')";
            $where .= " AND (produto.id_produto IN(" . $item->getSubitensIds() . "))";
            $model = new ModelProduto($this->tb, $this->adapter);
            $result = $model->where($where)->key("id_categoria")->addTamanhos()->get();
            
            //loop nos produtos
            foreach( $result as $row )
            {
                //adicionar subitem
                $subitem = $item->getSubitemFromId($row['id_produto']);
                $subitem->populate($row);
            }
            
            //definir item principal
            $itemFromCategory = $item->getSubitemFromCategory(\Model\ModelFiltro::CATEGORIA_BLAZER);
            if( empty($itemFromCategory->getId()) )
            {
                $itemFromCategory = $item->getSubitemFromCategory(\Model\ModelFiltro::CATEGORIA_CALCA);
            }
            $item->populate($itemFromCategory->toArray());
        }
        
        return $cart;
    }
    
    public function selectItemFromId(ModelItem $item)
    {
        //selecionar produtos
        $where = "(produto.status = '" . ModelProduto::STATUS_ATIVO . "')";
        $where .= " AND (produto.id_produto IN(" . $item->getSubitensIds() . "))";
        $model = new ModelProduto($this->tb, $this->adapter);
        $result = $model->where($where)->key("id_categoria")->addTamanhos()->get();
        
        //loop nos produtos
        foreach( $result as $row )
        {
            //adicionar subitem
            $subitem = $item->getSubitemFromId($row['id_produto']);
            $subitem->populate($row);
        }
        
        //definir item principal
        $itemFromCategory = $item->getSubitemFromCategory(\Model\ModelFiltro::CATEGORIA_BLAZER);
        if( empty($itemFromCategory->getId()) )
        {
            $itemFromCategory = $item->getSubitemFromCategory(\Model\ModelFiltro::CATEGORIA_CALCA);
        }
        $item->populate($itemFromCategory->toArray());
        
        return $item;
    }
}