<?php

/**
 * @codingStandardsIgnoreStart Generic.Files.LineLength.TooLong
 * @see       https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint/blob/master/LICENSE.md New BSD License
 * @codingStandardsIgnoreEnd Generic.Files.LineLength.TooLong
 */

namespace Laminas\ApiTools\Documentation\ApiBlueprint;

use Laminas\ApiTools\Documentation\Field;
use Laminas\View\Model\ViewModel;

class ApiBlueprintModel extends ViewModel
{

    const FORMAT = '1A';
    const CODE_BLOCK_INDENT = '        '; // 8 spaces, cannot use tabs (\t)
    const EMPTY_ROW = "\n\n";

    /**
     * @var string
     */
    private $apiBlueprint = '';

    public function terminate()
    {
        return true;
    }

    /**
     * @return  string
     */
    public function getFormattedApiBlueprint($scheme, $host)
    {
        $model = new Api($this->variables['documentation']);
        $this->apiBlueprint = 'FORMAT: ' . self::FORMAT . PHP_EOL;
        $this->apiBlueprint .= 'HOST: ' . $scheme . "://" . $host . self::EMPTY_ROW;
        $this->apiBlueprint .= '# ' . $model->getName() . PHP_EOL;
        $this->apiBlueprint .= $this->writeFormattedResourceGroups($model->getResourceGroups());

        return $this->apiBlueprint;
    }

    /**
     * @param ResourceGroup[] $resourceGroups
     */
    private function writeFormattedResourceGroups(array $resourceGroups)
    {
        foreach ($resourceGroups as $resourceGroup) {
            $this->apiBlueprint .= '# Group ' . $resourceGroup->getName() . PHP_EOL;
            $this->writeFormattedResources($resourceGroup->getResources());
        }
    }

    /**
     * @param Resource[] $resources
     */
    private function writeFormattedResources(array $resources)
    {
        foreach ($resources as $resource) {
            // don't display resources with no actions
            if (count($resource->getActions())) {
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
     * @param Action[] $resources
     */
    private function writeFormattedActions(array $actions)
    {
        foreach ($actions as $action) {
            $this->apiBlueprint .= '### ' . $action->getDescription() . ' ';
            $this->apiBlueprint .= '[' . $action->getHttpMethod() . ']' . self::EMPTY_ROW;
            $this->writeBodyProperties($action->getBodyProperties());
            $requestDescription = $action->getRequestDescription();
            if ($action->allowsChangingEntity() && ! empty($requestDescription)) {
                $this->apiBlueprint .= '+ Request' . self::EMPTY_ROW;
                $this->apiBlueprint .= $this->getFormattedCodeBlock($action->getRequestDescription())
                    . self::EMPTY_ROW;
            }
            $this->writeFormattedResponses($action);
        }
    }

    /**
     * @param Action $action
     */
    private function writeFormattedResponses(Action $action)
    {
        foreach ($action->getPossibleResponses() as $response) {
            $this->apiBlueprint .= '+ Response ' . $response['code']  . self::EMPTY_ROW;
            if ($response['code'] == 200) {
                $this->apiBlueprint .= $this->getFormattedCodeBlock($action->getResponseDescription())
                    . self::EMPTY_ROW;
            }
        }
    }

    /**
     * @param array $bodyProperties
     */
    private function writeBodyProperties(array $bodyProperties)
    {
        foreach ($bodyProperties as $property) {
            $this->apiBlueprint .= "+ " . $this->getFormattedProperty($property) . PHP_EOL;
        }
        $this->apiBlueprint .= self::EMPTY_ROW;
    }

    /**
     * @var Resource $resource
     */
    private function writeUriParameters(Resource $resource)
    {
        $resourceType = $resource->getResourceType();
        if ($resourceType === Resource::RESOURCE_TYPE_RPC) {
            return;
        }

        $this->apiBlueprint .= '+ Parameters' . PHP_EOL;
        if ($resourceType === Resource::RESOURCE_TYPE_ENTITY) {
            $this->apiBlueprint .= " + " . $resource->getParameter() . self::EMPTY_ROW;
            return;
        }

        // Laminas API Tools provides pagination results for collections
        // automatically, so page parameter will be available.
        $this->apiBlueprint .= " + " . 'page' . self::EMPTY_ROW;
    }

    /**
     * @var string $codeBlock
     * @return string
     */
    private function getFormattedCodeBlock($codeBlock)
    {
        return self::CODE_BLOCK_INDENT . str_replace("\n", "\n" . self::CODE_BLOCK_INDENT, $codeBlock);
    }

    /**
     * @var Field $property
     * @return string
     */
    private function getFormattedProperty(Field $property)
    {
        $output = $property->getName();
        $description = $property->getDescription();
        if (strlen($description)) {
            $output .= ' - ' . $description;
        }

        return $output;
    }
}
