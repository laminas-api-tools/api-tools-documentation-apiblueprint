<?php

namespace Laminas\ApiTools\Documentation\ApiBlueprint;

use Laminas\ApiTools\ContentNegotiation\ViewModel;
use Laminas\ApiTools\Documentation\ApiFactory;
use Laminas\Mvc\Controller\AbstractActionController;

class Controller extends AbstractActionController
{
    /** @var ApiFactory */
    protected $apiFactory;

    public function __construct(ApiFactory $apiFactory)
    {
        $this->apiFactory = $apiFactory;
    }

    /**
     * @return ViewModel
     */
    public function listAction()
    {
        $apis      = $this->apiFactory->createApiList();
        $viewModel = new ViewModel(['apis' => $apis]);
        $viewModel->setTemplate('api-tools-documentation-blueprint/api-list');
        return $viewModel;
    }

    /**
     * @return ViewModel
     */
    public function showAction()
    {
        $apiName    = $this->params()->fromRoute('api');
        $apiVersion = $this->params()->fromRoute('version', '1');

        $viewModel = new ViewModel(['api' => $apiName]);
        $viewModel->setTemplate('api-tools-documentation-blueprint/api');
        $viewModel->setTerminal(true);

        $api = $this->apiFactory->createApi($apiName, $apiVersion);
        $viewModel->setVariable('documentation', $api);
        $viewModel->setVariable('type', 'api');

        return $viewModel;
    }
}
