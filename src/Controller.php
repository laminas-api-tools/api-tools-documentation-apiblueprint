<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @copyright Copyright (c) 2015 Apiary Ltd. <support@apiary.io>
 */

namespace ZF\Apigility\Documentation\ApiBlueprint;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\Apigility\Documentation\ApiFactory;

class Controller extends AbstractActionController
{
    /**
     * @var ApiFactory
     */
    protected $apiFactory;

    /**
     * @param ApiFactory $apiFactory
     */
    public function __construct(ApiFactory $apiFactory)
    {
        $this->apiFactory = $apiFactory;
    }

    /**
     * @return ViewModel
     */
    public function listAction()
    {
        $apis = $this->apiFactory->createApiList();
        $viewModel = new ViewModel(['apis' => $apis]);
        $viewModel->setTemplate('zf-apigility-documentation-blueprint/api-list');
        return $viewModel;
    }

    /**
     * @return ViewModel
     */
    public function showAction()
    {
        $apiName = $this->params()->fromRoute('api');
        $apiVersion = $this->params()->fromRoute('version', '1');

        $viewModel = new ViewModel(['api' => $apiName]);
        $viewModel->setTemplate('zf-apigility-documentation-blueprint/api');
        $viewModel->setTerminal(true);

        $api = $this->apiFactory->createApi($apiName, $apiVersion);
        $viewModel->setVariable('documentation', $api);
        $viewModel->setVariable('type', 'api');

        return $viewModel;
    }
}
