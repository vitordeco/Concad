<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Painel\Controller\Banner'                      => 'Painel\Controller\BannerController',
            'Painel\Controller\Index'                       => 'Painel\Controller\IndexController',
            'Painel\Controller\Login'                       => 'Painel\Controller\LoginController',
            'Painel\Controller\Planos'                      => 'Painel\Controller\PlanosController',
        ),
    ),
    'router' => array(
        'routes' => array(
            
            //default
            'painel' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/painel',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Painel\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action][/:id]][/]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z0-9_-]*',
								'action'     => '[a-zA-Z0-9_-]*',
                                'id'         => '[a-zA-Z0-9_-]+',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'painel/layout'           => __DIR__ . '/../view/layout/layout.phtml',
        ),
        'template_path_stack' => array(
            'Painel' => __DIR__ . '/../view',
        ),
    ),
);
