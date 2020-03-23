<?php
namespace Tropaframework\Buy\Model\Item;
use Tropaframework\Buy\Model\ModelAbstract;

class ModelItem extends ModelAbstract
{
    protected $uid;
    protected $id;
	protected $codigo;
	protected $ean;
	protected $descricao;
	protected $imagens = array();
	protected $preco;
	protected $quantidade = 1;
	protected $largura;
	protected $altura;
	protected $comprimento;
	protected $peso;
	protected $tamanho;
	protected $tamanhos_disponiveis = array();
	protected $id_categoria;
	protected $categoria;
	protected $id_marca;
	protected $marca;
	protected $cep;
	protected $data_evento;
	protected $periodo;
	
	/**
	 * @var ModelSubitem
	 */
	protected $subitens = array();
	
	public function getUid()
	{
	    return $this->uid;
	}
	public function setUid($value)
	{
	    $this->uid = $value;
	}
	
	public function hasId()
	{
	    return !empty($this->id) ? true : false;
	}
	public function getId()
	{
		return $this->id;
	}
	public function setId($value)
	{
		$this->id = $value;
	}

	public function getCodigo()
	{
	    return $this->codigo;
	}
	public function setCodigo($value)
	{
	    $this->codigo = $value;
	}
	
	public function getEan()
	{
		return $this->ean;
	}
	public function setEan($value)
	{
		$this->ean = $value;
	}

	public function getDescricao()
	{
	    return $this->descricao;
	}
	public function setDescricao($value)
	{
	    $this->descricao = $value;
	}

	public function getImagens()
	{
	    return array_filter($this->imagens);
	}
	public function setImagens(array $value)
	{
	    $this->imagens = $value;
	}
	
	public function getPreco(bool $format = false)
	{
		return $format ? \Tropaframework\Helper\Convert::toReal($this->preco) : $this->preco;
	}
	public function setPreco($value)
	{
		$this->preco = $value;
	}

	public function getQuantidade()
	{
	    $return = !empty($this->quantidade) ? $this->quantidade : 1;
		return (int)$return;
	}
	public function setQuantidade($value)
	{
		$this->quantidade = $value;
	}
	
	public function getLargura()
	{
	    return $this->largura;
	}
	public function setLargura($value)
	{
	    $this->largura = $value;
	}
	
	public function getAltura()
	{
	    return $this->altura;
	}
	public function setAltura($value)
	{
	    $this->altura = $value;
	}
	
	public function getComprimento()
	{
	    return $this->comprimento;
	}
	public function setComprimento($value)
	{
	    $this->comprimento = $value;
	}
	
	public function getPeso()
	{
		return (int)$this->peso;
	}
	public function setPeso($value)
	{
		$this->peso = $value;
	}

	public function getTamanho()
	{
	    return $this->tamanho;
	}
	public function setTamanho($value)
	{
	    $this->tamanho = $value;
	}
	
	public function getTamanhosDisponiveis()
	{
	    return $this->tamanhos_disponiveis;
	}
	public function setTamanhosDisponiveis(array $value)
	{
	    $this->tamanhos_disponiveis = $value;
	}
	
	public function getIdCategoria()
	{
	    return $this->id_categoria;
	}
	public function setIdCategoria($value)
	{
	    $this->id_categoria = $value;
	}
	
	public function getCategoria()
	{
	    return $this->categoria;
	}
	public function setCategoria($value)
	{
	    $this->categoria = $value;
	}
	
	public function getIdMarca()
	{
	    return $this->id_marca;
	}
	public function setIdMarca($value)
	{
	    $this->id_marca = $value;
	}
	
	public function getMarca()
	{
	    return $this->marca;
	}
	public function setMarca($value)
	{
	    $this->marca = $value;
	}
	
	public function getCep()
	{
	    return $this->cep;
	}
	public function setCep($value)
	{
	    $this->cep = $value;
	}
	
	public function getDataEvento($format = "d/m/Y")
	{
	    return date($format, strtotime($this->data_evento));
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
	
	public function getSubitens()
	{
	    return $this->subitens;
	}
	public function addSubitem(ModelSubitem $value)
	{
	    $this->subitens[] = $value;
	}
	
	public function populate($array)
	{
	    $array = (array)$array;
	    
	    if( !empty($array['id_produto']) )
	    {
            $array['id'] = $array['id_produto'];
	    }
	    
	    if( !empty($array['produto']) )
	    {
	       $array['descricao'] = $array['produto'];
	    }
	    
	    if( !empty($array['imagem_1']) )
	    {
	       $array['imagens'] = [$array['imagem_1'], $array['imagem_2'], $array['imagem_3'], $array['imagem_4'], $array['imagem_5']];
	    }
	    
	    if( !empty($array['dimensoes']) )
	    {
	        $dimensoes = json_decode($array['dimensoes'], true);
	        $array['largura'] = $dimensoes['largura'];
	        $array['altura'] = $dimensoes['altura'];
	        $array['comprimento'] = $dimensoes['comprimento'];
	        $array['peso'] = $dimensoes['peso'];
	    }
	    
	    foreach( $array as $key=>$value )
	    {
	        //add subitens
	        if( $key == "subitens" )
	        {
	            foreach( $value as $row )
	            {
	                $subitem = new ModelSubitem();
	                $subitem->populate($row);
	                $this->addSubitem($subitem);
	            }
	        }
	    }
	    
	    return parent::populate($array);
	}
}