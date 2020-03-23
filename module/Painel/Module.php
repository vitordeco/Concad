<?php
namespace Painel;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\ModuleManager;

class Module implements AutoloaderProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function init(ModuleManager $manager)
    {
    	$events = $manager->getEventManager();
    	$sharedEvents = $events->getSharedManager();
    	 
    	$sharedEvents->attach(__NAMESPACE__, 'dispatch', function($e){
    		$controller = $e->getTarget();
   			$controller->layout('painel/layout');
    	}, 100);
    }
    
}
