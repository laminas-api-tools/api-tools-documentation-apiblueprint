<?php

declare(strict_types=1);

namespace Laminas\ApiTools\Documentation\ApiBlueprint;

use Laminas\ApiTools\Documentation\JsonModel;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Laminas\View\Model\ViewModel;

return [
    'router'                        => [
        'routes' => [
            'api-tools' => [
                'child_routes' => [
                    'blueprint' => [
                        'type'          => 'segment',
                        'options'       => [
                            'route'    => '/blueprint',
                            'defaults' => [
                                'controller' => Controller::class,
                                'action'     => 'list',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'api' => [
                                'type'          => 'segment',
                                'options'       => [
                                    'route'    => '/:api',
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
    'service_manager'               => [
        'factories' => [
            ApiBlueprintRenderer::class     => InvokableFactory::class,
            ApiBlueprintViewStrategy::class => ApiBlueprintViewStrategyFactory::class,
        ],
    ],
    'controllers'                   => [
        'factories' => [
            Controller::class => ControllerFactory::class,
        ],
    ],
    'api-tools-content-negotiation' => [
        'controllers'      => [
            Controller::class => 'Documentation',
        ],
        'accept_whitelist' => [
            Controller::class => [
                'text/vnd.apiblueprint+markdown',
            ],
        ],
        'selectors'        => [
            'Documentation' => [
                ViewModel::class         => [
                    'text/html',
                    'application/xhtml+xml',
                ],
                JsonModel::class         => [
                    'application/json',
                ],
                ApiBlueprintModel::class => [
                    'text/vnd.apiblueprint+markdown',
                ],
            ],
        ],
    ],
    'view_manager'                  => [
        'template_path_stack' => [
            'api-tools-documentation-blueprint' => __DIR__ . '/../view',
        ],
        'strategies'          => [
            ApiBlueprintViewStrategy::class,
        ],
    ],
];
