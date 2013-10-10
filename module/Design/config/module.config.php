<?php
return array(

        'router' => array(
                'routes' => array(
                        'design' => array(
                                'type' => 'Zend\Mvc\Router\Http\Literal',
                                'options' => array(
                                        'route'    => '/design',
                                        'defaults' => array(
                                                'controller' => 'Design\Controller\Index',
                                                'action'     => 'index',
                                        ),
                                ),
                        ),
                        'design/challenge' => array(
                                'type'    => 'segment',
                                'options' => array(
                                        'route'    => '/design/challenge[/][:action][/:id]',
                                        'defaults' => array(
                                                'controller' => 'Design\Controller\Challenge',
                                                'action'     => 'index',
                                        ),
                                ),
                        ),
                ),
        ),
        'controllers' => array(
                'invokables' => array(
                        'Design\Controller\Challenge' => 'Design\Controller\ChallengeController',
                        'Design\Controller\Index' => 'Design\Controller\IndexController',
                ),
        ),
        'view_manager' => array(
                'template_path_stack' => array(
                        'album' => __DIR__ . '/../view',
                ),
        ),
        'doctrine' => array(
                'driver' => array(
                        'entities' => array(
                                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                                'cache' => 'array',
                                'paths' => array(
                                        __DIR__ . '/../../Application/src/Application/Entity',
                                        __DIR__ . '/../src/Design/Entity',
                                )

                        ),
                        'orm_default' => array(
                                'drivers' => array(
                                        'Application\Entity' => 'entities',
                                        'Design\Entity' => 'entities',
                                )
                        )
                )
        )
);