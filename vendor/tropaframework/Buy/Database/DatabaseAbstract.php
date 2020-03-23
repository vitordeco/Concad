<?php
namespace Tropaframework\Buy\Database;

class DatabaseAbstract
{
    protected $tb;
    protected $adapter;
    
    public function __construct($tb, $adapter)
    {
        $this->tb = $tb;
        $this->adapter = $adapter;
    }
    
}