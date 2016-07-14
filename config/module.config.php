<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2015-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @copyright Copyright (c) 2015 Apiary Ltd. <support@apiary.io>
 */

namespace ZF\Apigility\Documentation\ApiBlueprint;

use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\View\Model\ViewModel;
use ZF\Apigility\Documentation\JsonModel;

return [
    'router' => [
        'routes' => [
            'zf-apigility' => [
                'child_routes' => [
                    'blueprint' => [
                        'type' => 'segment',
                        'options' => [
                            'route'    => '/blueprint',
                            'defaults' => [
                                'controller' => Controller::class,
                                'action'     => 'list',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'api' => [
                                'type' => 'segment',
                                'options' => [
                                    'route' => '/:api',
                                    'defaults' => [
                                        'action' => 'show',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            ApiBlueprintViewRenderer::class => InvokableFactory::class,
            ApiBlueprintViewStrategy::class => ApiBlueprintViewStrategyFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller::class => ControllerFactory::class,
        ],
    ],
    'zf-content-negotiation' => [
        'controllers' => [
            Controller::class => 'Documentation',
        ],
        'accept_whitelist' => [
            Controller::class => [
                'text/vnd.apiblueprint+markdown',
            ],
        ],
        'selectors' => [
            'Documentation' => [
                ViewModel::class => [
                    'text/html',
                    'application/xhtml+xml',
                ],
                JsonModel::class => [
                    'application/json',
                ],
                ApiBlueprintModel::class => [
                    'text/vnd.apiblueprint+markdown',
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'zf-apigility-documentation-blueprint' => __DIR__ . '/../view',
        ],
        'strategies' => [
            ApiBlueprintViewStrategy::class,
        ],
    ],
];
