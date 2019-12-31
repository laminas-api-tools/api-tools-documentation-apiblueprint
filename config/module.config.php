<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint/blob/master/LICENSE.md New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'api-tools' => array(
                'child_routes' => array(
                    'blueprint' => array(
                        'type' => 'Laminas\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route'    => '/blueprint',
                            'defaults' => array(
                                'controller' => 'Laminas\ApiTools\Documentation\ApiBlueprint\Controller',
                                'action'     => 'list',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'api' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/:api',
                                    'defaults' => array(
                                        'action' => 'show',
                                    ),
                                ),
                                'may_terminate' => true,
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),

    'service_manager' => array(
        'factories' => array(
            'Laminas\ApiTools\Documentation\ApiBlueprint\ApiBlueprintViewStrategy' => 'Laminas\ApiTools\Documentation\ApiBlueprint\ApiBlueprintViewStrategyFactory',
        ),
        'invokables' => array(
            'Laminas\ApiTools\Documentation\ApiBlueprint\ApiBlueprintViewRenderer' => 'Laminas\ApiTools\Documentation\ApiBlueprint\ApiBlueprintRenderer',
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'Laminas\ApiTools\Documentation\ApiBlueprint\Controller' => 'Laminas\ApiTools\Documentation\ApiBlueprint\ControllerFactory',
        ),
    ),
    'api-tools-content-negotiation' => array(
        'controllers' => array(
            'Laminas\ApiTools\Documentation\ApiBlueprint\Controller' => 'Documentation',
        ),
        'accept_whitelist' => array(
            'Laminas\ApiTools\Documentation\ApiBlueprint\Controller' => array(
                'text/vnd.apiblueprint+markdown',
            ),
        ),
        'selectors' => array(
            'Documentation' => array(
                'Laminas\View\Model\ViewModel' => array(
                    'text/html',
                    'application/xhtml+xml',
                ),
                'Laminas\ApiTools\Documentation\JsonModel' => array(
                    'application/json',
                ),
                'Laminas\ApiTools\Documentation\ApiBlueprint\ApiBlueprintModel' => array(
                    'text/vnd.apiblueprint+markdown',
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'api-tools-documentation-blueprint' => __DIR__ . '/../view',
        ),
        'strategies' => array(
           'Laminas\ApiTools\Documentation\ApiBlueprint\ApiBlueprintViewStrategy',
        ), 
    ),
);
