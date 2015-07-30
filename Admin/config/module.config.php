<?php

    namespace Admin;

return array(
    'controllers' => array(
        'invokables' => array(
            'Admin\Controller\Vehicle' => 'Admin\Controller\VehicleController',
             'Admin\Controller\Geography' => 'Admin\Controller\GeographyController',
            'Admin\Controller\Bank' => 'Admin\Controller\BankController',
            'Admin\Controller\User' => 'Admin\Controller\UserController',
             'Admin\Controller\Tarriff' => 'Admin\Controller\TarriffController',
        ),
    ),
    
    'router' => array(
        'routes' => array(
            'admin' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/admin',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller' => 'Vehicle',
                        'action' => 'add',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action[/:id]]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                '__NAMESPACE__' => 'Admin\Controller',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    
    
      // Doctrine config
     'doctrine' => array(
                 'connection' => array(
                  'orm_alternative'=> array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host'     => 'localhost',
                    'port'     => '3306',
                    'user'     => 'root',
                    'password' => '',
                    'dbname'   => 'cabsaasadmin',
                    'driver' => 'pdo_mysql',
                )
            ),
            ),
                  'entitymanager' => array(
         
            'orm_alternative' => array(
                'connection'    => 'orm_alternative',
                'configuration' => 'orm_alternative',
            ),
        ),
                  'configuration' => array(
            
            'orm_alternative' => array(
                'metadata_cache' => 'array',
                'query_cache' => 'array',
                'result_cache' => 'array',
                'hydration_cache' => 'array',
                'generate_proxies' => true,
            ),
        ),
//        'driver' => array(
//            __NAMESPACE__ . '_driver_alternative' => array(
//                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
//                'cache' => 'array',
//                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
//            ),
//            'orm_alternative' => array(
//                'class'   => 'Doctrine\ORM\Mapping\Driver\DriverChain',
//                'drivers' => array(
//                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
//                )
//            )
//        ),
          'driver' => array(    
                      __NAMESPACE__ => array(
            'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
        ),
                     'orm_default' => array(
            'drivers' => array(
                __NAMESPACE__ . '\Entity' => __NAMESPACE__
            ),
        ), 
            ),         
    )
    
);