<?php
namespace Application\Classes\Rotina;

class RotinaAbstract
{
    /**
     * @var bool
     */
    protected $sandbox = false;
    
    /**
     * @var unknown
     */
    protected $adapter;
    
    /**
     * @var unknown
     */
    protected $tb;

    /**
     * @var unknown
     */
    private $layout;
    
    public function __construct($adapter, $tb, $layout)
    {
        //definir vars
        $this->adapter = $adapter;
        $this->tb = $tb;
        $this->layout = $layout;
        
        //definir ambiente
        if( $this->layout->config_host['env'] == 'local' )
        {
            $this->sandbox = true;
        }
    }
    
    protected function layout()
    {
        return $this->layout;
    }
}