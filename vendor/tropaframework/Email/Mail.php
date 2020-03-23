<?php
namespace Tropaframework\Email;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Mime;
use Zend\Mime\Part as MimePart;

/**
 * @NAICHE | Vitor Deco
 * Class to send mail
 * 
 * Example: 
 * $email = new Email(['addTo'=>'email@domain.com']);
 * $email->send('message');
 */
abstract class Mail
{
    //config
	public $addTo		 = null;
    public $addCc        = null;
    public $addBcc       = null;
    public $addReplyTo   = null;
    public $addFrom      = null;
    public $setSubject   = null;
    public $setTitle   	 = null;
    public $setBody      = null;
    public $attach       = array();

    //ambiente de teste
    public $is_sandbox 	 = false;
    public $addTest 	 = null;
    
    //config SMTP
    private $name        = null;
    private $host        = null;
    private $port        = null;
    private $connClass   = null;
    private $username    = null;
    private $password    = null;
    private $ssl         = null;
    
    //debug
    protected $debug	 = false;
    
    public function __construct($config = array())
    {
    	//configurações do smtp
    	foreach( $config as $k => $v )
    	{
    		$this->{$k} = $v;
    	}
    }

    public function addAttach(array $attach)
    {
        $this->attach = $attach;
        return $this;
    }

	protected function send($msg) 
    {
		try {
			
			//trim
			$msg = trim($msg);
			
			//debug
			if( $this->debug === true )
			{
				echo $this->createHTML($msg); 
				exit;
			}
			
			//ambiente
			if( $this->is_sandbox )
			{
				$this->addTo = $this->addTest;
				$this->addCc = null;
				$this->addBcc = null;
			}
			
	        //set message with HTML
	        $this->setMessage($msg);

	        //zend mail
	        $message = new Message();
	        
	        //zend mail - set configs
	        $message->addTo( $this->addTo );
	        $message->addFrom( $this->addFrom, $this->name );
	        $message->setSubject( $this->setSubject );
	        $message->setBody( $this->setBody );
			$message->setEncoding('UTF-8'); //Encoding da mensagem

	        //zend mail - optionals
	        if( !empty($this->addCc) )
	            $message->addCc( $this->addCc );
	        
	        if( !empty($this->addBcc) )
	            $message->addBcc( $this->addBcc );
	        
	        if( !empty($this->addReplyTo) )
	            $message->addReplyTo( $this->addReplyTo );
	        
	        //SMTP transport - with login authentication
	        $transport = new SmtpTransport();
	        $options   = new SmtpOptions(array(
	        	'name'              => $this->name,
	        	'host'              => $this->host,
	        	'port'		        => $this->port,
	        	'connection_class'  => $this->connClass,
	        	'connection_config' => array(
	        		'username'      => $this->username,
	        		'password'      => $this->password,
	        		'ssl'           => $this->ssl,
	        	),
	        ));
	        $transport->setOptions($options);
	        $transport->send($message);
	    
	        return true;
	        
		} catch (\Exception $e){
	    	return $e->getMessage();
	    }
    }

	protected function setMessage($msg = null, $type = 'html')
    {
    	//Somente em html
		if ( $type == 'html' )
		{
	    	$content = new MimePart($this->createHTML($msg));
	    	$content->type = "text/html";
	    	$content->setCharset('utf8');    

	    //Em texto
		} else {
	    	$StripTags = new \Zend\Filter\StripTags();
	    	$content = new MimePart($StripTags->filter($msg));
	    	$content->type = "text/plain";
	    	$content->setCharset('utf8');
		}

		//add attach
        if( !empty($this->attach['tmp_name']) && !empty($this->attach['type']) )
        {
            $attachment = new MimePart(fopen($this->attach['tmp_name'], 'r'));
            $attachment->setFileName($this->attach['name']);
            $attachment->type = $this->attach['type'];
            $attachment->encoding    = Mime::ENCODING_BASE64;
            $attachment->disposition = Mime::DISPOSITION_ATTACHMENT;

            $body = new MimeMessage();
            $body->setParts(array($content, $attachment));
            return $this->setBody = $body;
        }

    	$body = new MimeMessage();
    	$body->setParts(array($content));
    	return $this->setBody = $body;
    }
    
    abstract protected function createHTML($msg);
}