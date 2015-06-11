<?php 
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @copyright Copyright (c) 2015 Apiary Ltd. <support@apiary.io>
 */

namespace ZF\Apigility\Documentation\ApiBlueprint;

use Zend\View\Model\ViewModel;

class ApiBlueprintModel extends ViewModel {

    const FORMAT = '1A';
    const CODE_BLOCK_INDENT = '        '; // 8 spaces, cannot use tabs (\t)

    /**
     * @var string
     */
    private $apiBlueprint = '';

    /**
     * @var string
     */
    private static $EMPTY_ROW;

    public function __construct() {
        parent::__construct();

        // PHP < 5.6 is unable to concatenate constants into new constant
        self::$EMPTY_ROW = PHP_EOL . PHP_EOL;
    }

    public function terminate()
    {
        return true;
    }

    /**
     * @return  string
     */
    public function getFormattedApiBlueprint() {
        $model = new Api($this->variables['documentation']);
        $this->apiBlueprint = 'FORMAT: ' . self::FORMAT . PHP_EOL;
        $this->apiBlueprint .= 'HOST: ' . $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . self::$EMPTY_ROW; 
        $this->apiBlueprint .= '# ' . $model->getName() . PHP_EOL;
        $this->apiBlueprint .= $this->writeFormattedResourceGroups($model->getResourceGroups());

        return $this->apiBlueprint;
    }

    /**
     * @param Blueprint\ResourceGroup[] $resourceGroups
     */
    private function writeFormattedResourceGroups(array $resourceGroups) {
        foreach ($resourceGroups as $resourceGroup) {
            $this->apiBlueprint .= '# Group ' . $resourceGroup->getName() . PHP_EOL;
            $this->writeFormattedResources($resourceGroup->getResources());
        }
    }

    /**
     * @param Blueprint\Resource[] $resources
     */
    private function writeFormattedResources(array $resources) {
        foreach ($resources as $resource) {
            // don't display resources with no actions
            if(count($resource->getActions())) {
                $this->apiBlueprint .= '## ' . $resource->getName() . ' ';
                $this->apiBlueprint .= '[' . $resource->getUri() . ']' . PHP_EOL;
                if ($resource->getResourceType() !== Resource::RESOURCE_TYPE_COLLECTION) {
                    $this->writeBodyProperties($resource->getbodyProperties());
                }
                $this->writeUriParameters($resource);
                $this->writeFormattedActions($resource->getActions());
            }
        }
    }

    /**
     * @param Blueprint\Action[] $resources
     */
    private function writeFormattedActions(array $actions) {
        foreach ($actions as $action) {
            $this->apiBlueprint .= '### ' . $action->getDescription() . ' ';
            $this->apiBlueprint .= '[' . $action->getHttpMethod() . ']' . self::$EMPTY_ROW;
            $this->writeBodyProperties($action->getBodyProperties());
            $requestDescription = $action->getRequestDescription();
            if($action->isEntityChanging() && !empty($requestDescription)){
                $this->apiBlueprint .= '+ Request' . self::$EMPTY_ROW;
                $this->apiBlueprint .= $this->getFormattedCodeBlock($action->getRequestDescription())  . self::$EMPTY_ROW;
            }
            $this->writeFormattedResponses($action);
        }
    }

    /**
     * @param Blueprint\Action $action
     */
    private function writeFormattedResponses(Action $action) {
        foreach ($action->getPossibleResponses() as $response) {
            $this->apiBlueprint .= '+ Response ' . $response['code']  . self::$EMPTY_ROW;
            if ($response['code'] == 200) {
                $this->apiBlueprint .= $this->getFormattedCodeBlock($action->getResponseDescription()) . self::$EMPTY_ROW;
            }
        }
    }

    /**
     * @param array $bodyProperties
     */
    private function writeBodyProperties(array $bodyProperties) {
        foreach ($bodyProperties as $property) {
            $this->apiBlueprint .= "+ " . $this->getFormattedProperty($property) . PHP_EOL;
        }
        $this->apiBlueprint .= self::$EMPTY_ROW;
    }

    /**
     * @var \Blueprint\Resource $resource
     */
    private function writeUriParameters(\ZF\Apigility\Documentation\ApiBlueprint\Resource $resource) {
        $resourceType = $resource->getResourceType();
        if ($resourceType !== Resource::RESOURCE_TYPE_RPC) {
            $this->apiBlueprint .= '+ Parameters' . PHP_EOL;
            if ($resourceType === Resource::RESOURCE_TYPE_ENTITY) {
                $this->apiBlueprint .= " + " . $resource->getParameter() . self::$EMPTY_ROW;
            } else {
                // Apigility provides pagination results for collections automatically, so page parameter will be available
                $this->apiBlueprint .= " + " . 'page' . self::$EMPTY_ROW;
            }
        }
    }

    /**
     * @var string $codeBlock
     * @return string
     */
    private function getFormattedCodeBlock($codeBlock) {
        return self::CODE_BLOCK_INDENT . \str_replace("\n", "\n" . self::CODE_BLOCK_INDENT, $codeBlock);
    }

    /**
     * @var \ZF\Apigility\Documentation\Field $property
     * @return string
     */
    private function getFormattedProperty(\ZF\Apigility\Documentation\Field $property) {
        $output = $property->getName(); 
        $description = $property->getDescription();
        if (\strlen($description)) {
            $output .= ' - ' . $description;
        }
        
        return $output;
    }
}
