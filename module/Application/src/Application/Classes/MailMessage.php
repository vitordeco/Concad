<?php
namespace Application\Classes;

use Tropaframework\Email\Mail;

/**
 * @author Vitor Deco
 * Class with all messages
 */
class MailMessage extends \Tropaframework\Email\Mail
{
	//url base
	private $url = null;
	
	public function __construct($config=array(), $debug=false)
	{
		//define se é pra debugar
		$this->debug = $debug;
		
		//define a url base
		$this->url = 'http://'.$_SERVER["HTTP_HOST"];
		
		//construct parent
		parent::__construct($config);
	}
    
	public function recuperarSenha($to, $replace)
	{
	    $message = $this->render('email/cadastro-recuperar-senha', $replace);
	
	    $this->setTitle = 'Recuperar senha';
	    $this->setSubject = $this->setSubject . ' ' . $this->setTitle;
	    $this->addTo = $to;
	    return $this->send($message);
	}

    public function contato($to, $replace)
    {
        $message = $this->render('email/contato', $replace);

        $this->setTitle = 'Contato';
        $this->setSubject = $this->setSubject . ' ' . $this->setTitle;
        $this->addTo = $to;
        return $this->send($message);
    }

    protected function send($msg)
    {
        try
        {
            $response = parent::send($msg);
    
            if( $response !== true )
            {
                throw new \Exception($response);
            }
    
            return true;
            
        } catch( \Exception $e ){
    
            \Tropaframework\Log\Log::error("Erro no envio de e-mail", ["exception" => $e->getMessage()]);
    
        }
    }
    
    private function sendReplace($message, $replace)
    {
    	//array replace add items
    	$replace['url'] = $this->url;
    	
    	//array search
	    $search = array();
	    foreach( array_keys($replace) as $value ) $search[] = '{' . $value . '}';
	    
	    //replace
	    $message = str_replace($search, $replace, $message);
    	
	    //send message
    	return $this->send($message);
    }
    
    private function render($layout, $vars=array())
    {
    	$resolver = new \Zend\View\Resolver\TemplateMapResolver();
    	$resolver->add('template', __DIR__ . '/../../../../Application/view/' . $layout . '.phtml');
    	
    	$view = new \Zend\View\Renderer\PhpRenderer();
    	$view->setResolver($resolver);
    	
    	$viewLayout = new \Zend\View\Model\ViewModel();
    	$viewLayout->setTemplate('template');
    	
    	if( !empty($vars) )
    	{
    		foreach( $vars as $key=>$value )
    		{
    			$viewLayout->setVariable($key, $value);
    		}
    	}
    	
    	return $view->render($viewLayout);
    }
    
    protected function createHTML($content)
    {
    	return $this->render('email/layout', ['content'=>$content, 'title'=>$this->setTitle]);
    }
}