<?php
return array(
    'db' => array(
		'driver'	=> 'Pdo',
		'dsn'		=> 'mysql:dbname=db_guerreiro;host=127.0.0.1:3306',
		'username'	=> 'guerreiro',
		'password'	=> 'Merege@7193',
		'driver_options' => array(
//            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
            '1002' => 'SET NAMES \'UTF8\''
		),
	),
    
	'tb' => array(
        'login'				        => 'cc_login',
        'pessoa_fisica'		        => 'cc_pessoa_fisica',
        'pessoa_juridica'		    => 'cc_pessoa_juridica',
        'usuario'				    => 'cc_usuario',
	),
	
    'service_manager' => array(
        'factories' => array(
            'db' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ),
	
	'view_manager' => array(
	    'display_not_found_reason'	=> false,
	    'display_exceptions'		=> false,
// 	    'display_not_found_reason'  => true,
// 	    'display_exceptions'        => true,
	    
	    'base_path' 				=> '/',
	    'doctype'                  	=> 'HTML5',
	    'not_found_template'       	=> 'error/404',
	    'exception_template'       	=> 'error/index',
    ),
    
    'phpSettings'   => array(
		'display_startup_errors'    => false,
		'display_errors'            => false,
		'error_reporting'           => 0,
	    'max_execution_time'        => 60,
//        'display_startup_errors'    => true,
//        'display_errors'            => true,
//        'error_reporting'           => E_ALL & ~E_NOTICE,
//        'max_execution_time'        => 60,
        
	    'date.timezone'             => 'America/Sao_Paulo',
	    'default_charset'           => 'UTF-8',
    ),
	
 	'config_host' => array(
 		'env' => 'production',
 	),
 	
	'config_smtp'   => array(
	    'name' 			    => 'CONCAD',
	    'host' 			    => 'srv258.prodns.com.br',
	    'port' 			    => 465,
	    'connClass'		    => 'login',
	    'username' 		    => 'no-reply@tropa.digital',
	    'password' 		    => 'maguilamaneiro',
	    'ssl'			    => 'ssl',
	    'addFrom' 		    => 'no-reply@tropa.digital',
	    'setSubject'	    => '[CONCAD]',
        'emailTo'           => 'vitordeco@gmail.com',
	),

);