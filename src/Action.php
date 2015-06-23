<?php 
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @copyright Copyright (c) 2015 Apiary Ltd. <support@apiary.io>
 */

namespace ZF\Apigility\Documentation\ApiBlueprint;

use ZF\Apigility\Documentation\Operation as BaseOperation;

class Action {

	/**
	 * @var BaseOperation
	 */
	private $operation;

	/**
	 * @var \ZF\Apigility\Documentation\Field[]
	 */
	private $bodyProperties = array();

	/**
	 * @var array
	 */
	private static $entityChangingMethods = array('PUT', 'POST', 'PATCH');

	/**
	 * @param BaseOperation $operations
	 */
	public function __construct(BaseOperation $operation) {
		$this->operation = $operation;
	}

	/**
	 * @return string 
	 */
	public function getDescription() {
		return $this->operation->getDescription();
	}

	/**
	 * @return string 
	 */
	public function getHttpMethod() {
		return $this->operation->getHttpMethod();
	}

	/**
	 * @return array  
	 */
	public function getPossibleResponses() {
		return $this->operation->getResponseStatusCodes();
	}

	/**
	 * @return \ZF\Apigility\Documentation\Field[]  
	 */
	public function getBodyProperties() {
		return $this->bodyProperties;
	}

	/**
	 * @var \ZF\Apigility\Documentation\Field[] $properties
	 */
	public function setBodyProperties(array $properties) {
		$this->bodyProperties = $properties;
	}

	/**
	 * @return string
	 */
	public function getRequestDescription() {
		return $this->operation->getRequestDescription();
	}

	/**
	 * @return string
	 */
	public function getResponseDescription() {
		return $this->operation->getResponseDescription();
	}

	/**
	 * @return boolean
	 */
	public function allowsChangingEntity() {
		return in_array($this->getHttpMethod(),self::$entityChangingMethods);
	}
}