<?php

/**
 * @codingStandardsIgnoreStart Generic.Files.LineLength.TooLong
 * @see       https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint/blob/master/LICENSE.md New BSD License
 * @codingStandardsIgnoreEnd Generic.Files.LineLength.TooLong
 */

namespace Laminas\ApiTools\Documentation\ApiBlueprint;

use Laminas\ApiTools\Documentation\JsonModel;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Laminas\View\Model\ViewModel;

return [
    'router' => [
        'routes' => [
            'api-tools' => [
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
        // Legacy Zend Framework aliases
        'aliases' => [
            \ZF\Apigility\Documentation\ApiBlueprint\ApiBlueprintRenderer::class => ApiBlueprintRenderer::class,
            \ZF\Apigility\Documentation\ApiBlueprint\ApiBlueprintViewStrategy::class => ApiBlueprintViewStrategy::class,
        ],
        'factories' => [
            ApiBlueprintRenderer::class     => InvokableFactory::class,
            ApiBlueprintViewStrategy::class => ApiBlueprintViewStrategyFactory::class,
        ],
    ],
    'controllers' => [
        // Legacy Zend Framework aliases
        'aliases' => [
            \ZF\Apigility\Documentation\ApiBlueprint\Controller::class => Controller::class,
        ],
        'factories' => [
            Controller::class => ControllerFactory::class,
        ],
    ],
    'api-tools-content-negotiation' => [
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
            'api-tools-documentation-blueprint' => __DIR__ . '/../view',
        ],
        'strategies' => [
            ApiBlueprintViewStrategy::class,
        ],
    ],
];
