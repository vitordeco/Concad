<?php
namespace Model;
use Zend\Db\TableGateway\TableGateway;

/**
 * @NAICHE - Vitor Deco
 */
class ModelTableGateway
{
    const STATUS_ATIVO      = "1";
    const STATUS_INATIVO    = "2";
    const STATUS_EXCLUIDO   = "3";
    
	/**
	 * tablename list
	 * @var stdclass
	 */
	protected $tb = null;
	
	/**
	 * sql build
	 * @var \Zend\Db\Sql\Sql
	 */
	protected $sql = null;
	
    /**
     * Table Gateway
     * @var \Zend\Db\TableGateway\TableGateway
     */
    protected $tableGateway = null;

    /**
     * Database adapter
     * @var \Zend\Db\Zend\Db\Adapter\AdapterInterface
     */
    protected $adapter = null;

    /**
     * Table Name
     * @var string
     */
    protected $tableName = null;

    /**
     * Primary key
     * @var int
     */
    protected $primary_key = null;

    /**
     * Fields list
     * @var array
     */
    protected $fields = array();
    
    /**
     * Fields required
     * @var array
     */
    protected $required = array();
    
    /**
     * Fields errors in array
     * @var array
     */
    protected $error_result = array();
    
    /**
     * definir as condições para executar a query
     * @var string
     */
    protected $where = null;
    
    /**
     * definir ORDER BY
     * @var string
     */
    protected $order = null;
    
    /**
     * definir PAGE
     * @var int
     */
    protected $page = null;
    
    /**
     * definir LIMIT
     * @var int
     */
    protected $limit = null;
    
    /**
     * definir OFFSET
     * @var int
     */
    protected $offset = null;
    
    /**
     * definir HAVING
     * @var string
     */
    protected $having = null;
    
    /**
     * definir GROUP
     * @var string
     */
    protected $group = null;
    
    /**
     * true para retornar apenas 1 resultado
     * @var bool
     */
    protected $current = false;

    /**
     * definir KEY do array retornado
     * @var int
     */
    protected $key = null;
    
    /**
     * define os valores das variaveis globais da class
     * @param	string $tableName
     * @param	string $adapter
     * @return	void
     */
    public function __construct($tableName, $adapter) 
    {
        $this->tableName = $tableName;
        $this->adapter = $adapter;
        $this->tableGateway = new TableGateway($tableName, $adapter);
        $this->sql = new \Zend\Db\Sql\Sql($this->adapter);
        
        //definir colunas e primary key
        if( !count($this->fields) )
        {
            $metadata = new \Zend\Db\Metadata\Metadata($adapter);
            $this->fields = $metadata->getColumnNames($tableName);
            $this->primary_key = $this->fields[0];
        }
    }
    
    public function where($where)
    {
        $this->where = $where;
        return $this;
    }
    
    public function order($order)
    {
        $this->order = $order;
        return $this;
    }
    
    public function page($page)
    {
        $this->page = !empty($page) ? (int)$page : 1;
        return $this;
    }
    
    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }
    
    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }
    
    public function having($having)
    {
        $this->having = $having;
        return $this;
    }
    
    public function group($group)
    {
        $this->group = $group;
        return $this;
    }
    
    public function key($key)
    {
        $this->key = $key;        
        return $this;
    }
    
    /**
     * retorna o array de erros
     * @return array
     */
    public function getErrorResults()
    {
    	return $this->error_result;
    }
    
    /**
     * retorna todos os campos obrigatórios
     * @return array
     */
    public function getRequired()
    {
    	return $this->required;
    }
    
	/**
	 * retorna a coluna que foi definida como chave primária
	 * @return number
	 */
    public function getPrimaryKey()
    {
    	return $this->primary_key;
    }
    
    /**
     * retorna o table gateway
     * @return \Zend\Db\TableGateway\TableGateway
     */
    public function getTableGateway()
    {
    	return $this->tableGateway;
    }
    
    /**
     * verifica se há erros
     * @return boolean
     */
    public function isValidFields()
    {
    	return (count($this->error_result)) ? false : true;
    }
    
    /**
     * filtra todos os dados, retornando apenas os que foram definidos
     * @param array $fields
     * @param array $data
     * @return array
     */
    public function filter($fields, $data)
    {
    	$filter = array();
    	foreach( $data as $k => $v ) 
    	{
    		if( in_array($k, $fields) )
    		{
    			$filter[$k] = $v;
    		}
    	}
    
    	return $filter;
    }
    
    /**
     * valida campos, se todos os campos estiverem preenchidos
     * @param array $required
     * @param array $data
     * @return this
     */
    public function validate($required, $data)
    {
    	$this->error_result = array();
    	foreach( $required as $field ) 
    	{
    		if( !array_key_exists($field, $data) )
    		{
    			$this->error_result[$field] = 'Campo obrigatório';
    		}
    	}
    	return $this;
    }
    
    /**
     * retorna o ResultSet selecionando tudo no banco
     * @return ResultSet
     */
    public function fetchAll()
    {
    	return $this->tableGateway->select();
    }
    
    /**
     * executa uma query e retorna o resultado em um array
     * @param string $query
     * @return array
     */
    public function query($query)
    {
    	return $this->adapter->query($query, $this->adapter::QUERY_MODE_EXECUTE)->toArray();
    }
    
    /**
     * insere ou atualiza no banco de dados
     * @param array $set
     * @param string|int $id
     * @return int
     */
    public function save($set, $id=null)
    {
        
    	if( !empty($id) )
    	{
    		$set['modificado'] = date('Y-m-d H:i:s');
    		$set = $this->filter($this->fields, $set);
    		
    		$this->tableGateway->update($set, $this->primary_key . " = '" . $id . "'");
    		return $id;
    		
    	} else {
    	    
    		$set['criado'] = date('Y-m-d H:i:s');
    		$set = $this->filter($this->fields, $set);
    		
    		$this->tableGateway->insert($set);
    		return $this->tableGateway->lastInsertValue;
    	}
    }
    
    /**
     * atualiza no banco de dados
     * @param array $set
     * @param string $where
     */
    public function update($set, $where)
    {
    	$set['modificado'] = date('Y-m-d H:i:s');
    	$set = $this->filter($this->fields, $set);
    
    	return $this->tableGateway->update($set, $where);
    }
    
    /**
     * deleta no banco de dados
     * @param string|int $where
     * @return int
     */
    public function delete($where)
    {
    	if( is_numeric($where) )
    	{
    		return $this->tableGateway->delete($this->primary_key . " = '" . $where . "'");
    	
    	} else {
    		return $this->tableGateway->delete($where);
    	}
    }
    
    /**
     * paginação
     * @param string $qry
     * @param number $page_current
     * @param number $records_per_page
     * @return array
     */
    public function pagination($qry, $page_current = 1, $records_per_page = 1)
    {
        $records_per_page = ($records_per_page < 1) ? 1 : $records_per_page;
        
    	//executa a query
    	$sql = new \Zend\Db\Sql\Sql($this->adapter);
    	$records = $this->adapter->query($sql->getSqlStringForSqlObject($qry), $this->adapter::QUERY_MODE_EXECUTE)->count();
    	
    	//define offset
    	$page_current = ($page_current>ceil($records/$records_per_page) ? ceil($records/$records_per_page) : $page_current);
    	$page_current = ($page_current<=0) ? 1 : $page_current;
    	$offset = ($page_current-1) * $records_per_page;
    	
    	//define quantos estão sendo exibidos
    	$displaying = ($page_current*$records_per_page);
    	$displaying = ($displaying > $records) ? $records : $displaying;
    	
    	//set pagination vars
    	$pagination = array();
        $pagination['offset'] = $offset;
        $pagination['records'] = $records;
        $pagination['displaying'] = $displaying;
        $pagination['current'] = $page_current;
        $pagination['first'] = 1;
        $pagination['last'] = ceil($records/$records_per_page);
        $pagination['prev'] = $page_current > 1 ? $page_current - 1 : 1;
        $pagination['next'] = $page_current < $pagination['last'] ? $page_current + 1 : $pagination['last'];
        
        $pagination['pages'] = [];
        
        $count=0;
        for ($i = $page_current; $i >= 1; $i--) {
            
            if ( $count < 4 ){
                if ( $i >= 0 ){
                    $pagination['pages'][] = $i;
                }
            }
            
            $count++;
            
        }
        
        $count=0;
        for ($i = $page_current + 1; $i <= $page_current + 3; $i++) {
        
            if ( $i > $pagination['last'] ) break;
            
            if ( $count < 4 ){
                if ( $i >= 0 ){
                    $pagination['pages'][] = $i;
                }
            }
        
            $count++;
        
        }
        
        asort($pagination['pages']);
         
        return $pagination;
    }
    
    public function current()
    {
        $this->current = true;
        return $this;
    }
    
    public function execute($qry)
    {
        if ($this->order){
            $qry->order($this->order);
        }

        if ($this->where){
            $qry->where($this->where);
        }

        if ($this->having){
            $qry->having($this->having);
        }
        
        if ($this->group){
            $qry->group($this->group);
        }
        
        if ($this->page){
            $pagination = $this->pagination($qry, $this->page, $this->limit);
            $qry->offset($pagination['offset']);
        }
        
        if ($this->limit){
            $qry->limit($this->limit);
        }
         
        if ($this->offset){
            $qry->offset($this->offset);
        }
        
        $result = $this->sql->buildSqlString($qry);
        $result = $this->adapter->query($result, $this->adapter::QUERY_MODE_EXECUTE);
        
        if( $this->current )
        {
            $result = $result->current();
        } else {
            $result = $result->toArray();
            
            if( !empty($this->key) )
            {
                $result = array_column($result, null, $this->key);
            }
        }
        
        if( isset($pagination) ){
            
            return array('pagination' => $pagination, 'result' => $result);
            
        } else {
            
            return $result;
            
        }
        
    }
    
}