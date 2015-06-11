<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @copyright Copyright (c) 2015 Apiary Ltd. <support@apiary.io>
 */

namespace ZFTest\Apigility\Documentation\ApiBlueprint;

use PHPUnit_Framework_TestCase as TestCase;
use ZF\Apigility\Documentation\ApiBlueprint\Resource;

class ResourceTest extends TestCase
{
public function setUp () {
	$baseServiceMock = $this->getMockBuilder('ZF\Apigility\Documentation\Service')->getMock(); 
	$baseServiceMock->expects($this->any())->method('getName')->will($this->returnValue('Mock Service'));
	$baseServiceMock->expects($this->any())->method('getRouteIdentifierName')->will($this->returnValue('Mock parameter'));
	$this->resource = new Resource($baseServiceMock, array(), 'blueprint/test', 'entity');
}

	public function testResourceName() {
		$this->assertEquals($this->resource->getName(), 'Mock Service');
	}

	public function testResourceParameter() {
		$this->assertEquals($this->resource->getParameter(), 'Mock parameter');
	}

}
