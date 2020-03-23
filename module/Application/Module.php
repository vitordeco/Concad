<?php
namespace Application;

use Model\ModelLogin;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        //attach to an event manager
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        //register a render event
        $app = $e->getParam('application');
        $app->getEventManager()->attach('render', array($this, 'setLayoutTitle'));
        
        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, array(
            $this,
            'onDispatchError'
        ), 100);
        
        //called before any controller action called.
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array(
            $this,
            'beforeDispatch'
        ), 100);
        
        //called after any controller action called. Do any operation.
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array(
            $this,
            'afterDispatch'
        ), -100);
    }

    public function onDispatchError(MvcEvent $e) 
    {
        $viewModel = $e->getViewModel();
        $viewModel->setTemplate('layout/error');
    }
    
    public function beforeDispatch(MvcEvent $e)
    {
    	//definir variáveis globais
    	$viewModel = $e->getViewModel();
    	
    	//definir as configurações
    	$config = $e->getApplication()->getServiceManager()->get('config');
    	
    	//definir configurações do PHP para o ini_set
    	$phpSettings = $config['phpSettings'];
    	if( $phpSettings )
    	{
    	    foreach( $phpSettings as $key => $value ) ini_set($key, $value);
    	}
    	
    	//definir rotas nas variáveis globais
    	$matches 	= $e->getRouteMatch();
    	$module		= $matches->getParam('__NAMESPACE__');
    	$controller	= $matches->getParam('__CONTROLLER__');
    	$action		= $matches->getParam('action');
    	$id		    = $matches->getParam('id');
    	
    	$routes = array();
    	$routes['module']		= strtolower(substr($module, 0, strpos($module, '\\')));
    	$routes['controller']	= strtolower($controller);
    	$routes['action']		= strtolower($action);
    	$routes['id']		    = $id;
    	$viewModel->setVariable('routes', $routes);
    	
    	//definir configurações nas variáveis globais
    	$viewModel->setVariable('config_smtp', $config['config_smtp']);
        $viewModel->setVariable('config_host', $config['config_host']);
        $viewModel->setVariable('config_pagseguro', $config['config_pagseguro']);
    	
    	//definir sessão do login
    	$session = new \Zend\Session\Container(ModelLogin::SESSION_TOKEN);
    	if( $session->offsetExists('me') )
    	{
    	    $viewModel->setVariable('me', $session->offsetGet('me'));
    	}

        //definir sessão do login
        $session = new \Zend\Session\Container('AuthBackend');
        if( $session->offsetExists('me') )
        {
            $viewModel->setVariable('me', $session->offsetGet('me'));
        }
        
    	//inicializar logs
    	$this->logsInit($viewModel);
    }
    
    public function afterDispatch(MvcEvent $e)
    {
    }
    
    public function setLayoutTitle($e)
    {
    	//get service manager
    	$sm = $e->getApplication()->getServiceManager();
    
    	//get view model
    	$viewModel = $e->getViewModel();
    
    	//get view helper manager from the application service manager
    	$viewHelperManager = $sm->get('viewHelperManager');
    
    	//to view
    	$viewModel->setVariable('headTitle', $viewHelperManager->get('headTitle'));
    }
    
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

	public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                    'Tropaframework' => __DIR__ . '/../../vendor/tropaframework',
                    'Model' => __DIR__ . '/../../model',
                ),
            ),
        );
    }
    
    public function getViewHelperConfig()
    {
    	return array(
			'factories' => array(
				'message' => function($sm) {
					return new View\Helper\Message($sm->getServiceLocator()->get('ControllerPluginManager')->get('flashmessenger'));
				},
			)
    	);
    }
    
 	public function getServiceConfig()
    {
    	return array(
    		'factories' => array(
    			
    			'tb' => function ($sm) {
    				$tabelas = $sm->get('config');
    				return (object) $tabelas['tb'];
    			},
    			
			)
		);
    }
    
    /**
	 * log registrado sempre que uma página é carregada
	 */
	private function logsInit($viewModel)
	{
		//definir rotas
		$routes = $viewModel->getVariable('routes');
		
		//definir usuário logado
		$me = $viewModel->getVariable('me');
		$login = (!empty($me->id_login) ? $me->id_login : false);
		\Tropaframework\Log\Log::setUser($login);
		
		//definir diretório de destino
		$path = $_SERVER['DOCUMENT_ROOT'] . "/_logs/" . $routes['module'] . "/";
		\Tropaframework\Log\Log::setPath($path);
		
		//bloquear controllers
// 		$cond1 = !in_array($routes['controller'], ['img']);
		
		//registrar log de acesso
// 		if( $cond1 ) \Tropaframework\Log\Log::debug('acesso');
	}
}