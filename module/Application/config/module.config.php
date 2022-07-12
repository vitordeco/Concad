<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Api'                    => 'Application\Controller\ApiController',
            'Application\Controller\Cadastro'               => 'Application\Controller\CadastroController',
            'Application\Controller\CompletaCpf'            => 'Application\Controller\CompletaCpfController',
            'Application\Controller\ComprarCredito'         => 'Application\Controller\ComprarCreditoController',
            'Application\Controller\ConsultaEServicos'      => 'Application\Controller\ConsultaEServicosController',
            'Application\Controller\Conta'                  => 'Application\Controller\ContaController',
            'Application\Controller\FaleConosco'            => 'Application\Controller\FaleConoscoController',
            'Application\Controller\Index'                  => 'Application\Controller\IndexController',
            'Application\Controller\Login'                  => 'Application\Controller\LoginController',
            'Application\Controller\Planos'                 => 'Application\Controller\PlanosController',
            'Application\Controller\RecuperarSenha'         => 'Application\Controller\RecuperarSenhaController',
            'Application\Controller\Resultado'              => 'Application\Controller\ResultadoController',
        ),
    ),
    'router' => array(
        'routes' => array(

            //home
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),

            //default
            'application' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '[]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/CONCAD/public/[:controller[/:action][/:id][/]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z0-9_-]*',
                                'id'         => '[0-9]+',
                            ),
                        ),
                    ),
                ),
            ),

            //resultado
            'resultado' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/resultado[/:slug][/]',
                    'constraints' => array(
                        'slug' => '[a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' 	=> 'Resultado',
                        'action'     	=> 'index',
                    ),
                ),
            ),

//            //edicoes
//            'edicoes' => array(
//                'type' => 'Segment',
//                'options' => array(
//                    'route'    => '/edicoes[/:slug][/]',
//                    'constraints' => array(
//                        'slug' => '[a-zA-Z0-9_-]*',
//                    ),
//                    'defaults' => array(
//                        '__NAMESPACE__' => 'Application\Controller',
//                        'controller' 	=> 'Edicoes',
//                        'action'     	=> 'index',
//                    ),
//                ),
//            ),
        ),
	),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Db\Adapter\AdapterAbstractServiceFactory',
        ),
    ),
	'view_manager' => array(
		'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'layout/layout_alt'       => __DIR__ . '/../view/layout/layout_alt.phtml',
		    'error/404'               => __DIR__ . '/../view/error/404.phtml',
	        'error/index'             => __DIR__ . '/../view/error/index.phtml',
	        'error/404/debug'         => __DIR__ . '/../view/error/404_debug.phtml',
	        'error/index/debug'       => __DIR__ . '/../view/error/index_debug.phtml',
		    'pagination'              => __DIR__ . '/../view/layout/partials/pagination.phtml',
	    ),
		'template_path_stack' => array(
			__DIR__ . '/../view',
		),
	),
	'view_helpers' => array(
		'invokables'=> array(
			'pagination' => 'Application\View\Helper\Pagination',
		)
	),
);