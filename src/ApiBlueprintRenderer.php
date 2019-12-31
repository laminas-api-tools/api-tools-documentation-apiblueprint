<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\ApiTools\Documentation\ApiBlueprint;

use Laminas\Uri\Http as Uri;
use Laminas\View\Renderer\RendererInterface as Renderer;
use Laminas\View\Resolver\ResolverInterface;

class ApiBlueprintRenderer implements Renderer
{

    /**
     * Uri
     */
    private $requestUri;

    /**
     * @return mixed
     */
    public function getEngine()
    {
        return $this;
    }

    /**
     * @param Uri $uri
     */
    public function setRequestUri(Uri $requestUri)
    {
        $this->requestUri = $requestUri;
    }

    /**
     * @param  ResolverInterface $resolver
     * @return RendererInterface
     */
    public function setResolver(ResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @param  string|ModelInterface   $nameOrModel The script/resource process, or a view model
     * @param  null|array|\ArrayAccess $values      Values to use during rendering
     * @return string The script output.
     */
    public function render($nameOrModel, $values = null)
    {
        $port = $this->requestUri->getPort();
        $host = $this->requestUri->getHost();
        $host .= $port ? ':' . $port : '';
        return $nameOrModel->getFormattedApiBlueprint($this->requestUri->getScheme(), $host);
    }
}
