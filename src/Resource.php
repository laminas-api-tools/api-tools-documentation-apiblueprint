<?php 
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @copyright Copyright (c) 2015 Apiary Ltd. <support@apiary.io>
 */

namespace ZF\Apigility\Documentation\ApiBlueprint;

use ZF\Apigility\Documentation\Service as BaseService;
use ZF\Apigility\Documentation\Operation as BaseOperation;

class Resource {

	const RESOURCE_TYPE_ENTITY = 'entity';
	const RESOURCE_TYPE_COLLECTION = 'collection';
	const RESOURCE_TYPE_RPC = 'rpc';

	/**
	 * @var BaseOperation[]
	 */
	private $operations;

	/**
	 * @var BaseService[]
	 */
	private $service;

	/**
	 * @var string
	 */
	private $uri;

	/**
	 * @var Blueprint\Action[]
	 */
	private $actions = array();

	/**
	 * @var self::RESOURCE_TYPE_*
	 */
	private $resourceType;

	/**
	 * @param BaseService $service
	 * @param BaseOperations[] $operations
	 * @param string $uri
	 * @param string $resourceType self::RESOURCE_TYPE_*
	 */
	public function __construct(BaseService $service, array $operations = null, $uri, $resourceType) {
		$this->service = $service;
		$this->operations = $operations;
		$this->uri = $uri;
		$this->createActions();
		$this->resourceType = $resourceType;

		if ($this->getResourceType() == self::RESOURCE_TYPE_COLLECTION && $this->getParameter()) {
            $this->uri .= "?page={page}";
        }

    }

    /**
     * @return string
     */
    public function getName() {
    	return $this->service->getName();
    }

    /**
     * @return string
     */
    public function getUri() {
    	return $this->uri;
    }

    /**
     * @return string self::RESOURCE_TYPE_*
     */
    public function getResourceType() {
    	return $this->resourceType;
    }

    /**
     * @return string
     */
    public function getParameter() {
    	return $this->service->getRouteIdentifierName();
    }

    /**
     * @return \ZF\Apigility\Documentation\Field[]
     */
    public function getBodyProperties() {
    	return $this->service->getFields();
    }

    /**
     * @return Blueprint\Action[] 
     */
    public function getActions() {
    	return $this->actions;
    }

    private function createActions() {
    	foreach ($this->operations as $operation) {
    		$action = new Action($operation);
    		if ($action->isEntityChanging()) {
    			$action->setBodyProperties(array_filter($this->service->getFields(), function($field){
    				return $field->isRequired();
    			}));
    		}
    		$this->actions[] = $action;
    	}
    }
}
