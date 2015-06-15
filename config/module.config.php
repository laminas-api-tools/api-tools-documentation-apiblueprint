<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @copyright Copyright (c) 2015 Apiary Ltd. <support@apiary.io>
 */

return array(
    'router' => array(
        'routes' => array(
            'zf-apigility' => array(
                'child_routes' => array(
                    'blueprint' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route'    => '/blueprint',
                            'defaults' => array(
                                'controller' => 'ZF\Apigility\Documentation\ApiBlueprint\Controller',
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
            'ZF\Apigility\Documentation\ApiBlueprint\ApiBlueprintViewStrategy' => 'ZF\Apigility\Documentation\ApiBlueprint\ApiBlueprintViewStrategyFactory',
        ),
        'services' => array(
            'ZF\Apigility\Documentation\ApiBlueprint\ApiBlueprintViewRenderer' => new ZF\Apigility\Documentation\ApiBlueprint\ApiBlueprintRenderer(),
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'ZF\Apigility\Documentation\ApiBlueprint\Controller' => 'ZF\Apigility\Documentation\ApiBlueprint\ControllerFactory',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'ZF\Apigility\Documentation\ApiBlueprint\Controller' => 'Documentation',
        ),
        'accept_whitelist' => array(
            'ZF\Apigility\Documentation\ApiBlueprint\Controller' => array(
                'text/vnd.apiblueprint+markdown',
            ),
        ),
        'selectors' => array(
            'Documentation' => array(
                'Zend\View\Model\ViewModel' => array(
                    'text/html',
                    'application/xhtml+xml',
                ),
                'ZF\Apigility\Documentation\JsonModel' => array(
                    'application/json',
                ),
                'ZF\Apigility\Documentation\ApiBlueprint\ApiBlueprintModel' => array(
                    'text/vnd.apiblueprint+markdown',
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'zf-apigility-documentation-blueprint' => __DIR__ . '/../view',
        ),
        'strategies' => array(
           'ZF\Apigility\Documentation\ApiBlueprint\ApiBlueprintViewStrategy',
        ), 
    ),
);
