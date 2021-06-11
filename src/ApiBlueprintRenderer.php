<?php

namespace Laminas\ApiTools\Documentation\ApiBlueprint;

use ArrayAccess;
use Laminas\Uri\Http as Uri;
use Laminas\View\Renderer\RendererInterface as Renderer;
use Laminas\View\Resolver\ResolverInterface;

class ApiBlueprintRenderer implements Renderer
{
    /** @var Uri */
    private $requestUri;

    /** @return $this */
    public function getEngine()
    {
        return $this;
    }

    /**
     * @param Uri $uri
     * @return void
     */
    public function setRequestUri(Uri $requestUri)
    {
        $this->requestUri = $requestUri;
    }

    /**
     * @return void
     */
    public function setResolver(ResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @param  string|ModelInterface   $nameOrModel The script/resource process, or a view model
     * @param null|array|ArrayAccess $values Values to use during rendering
     * @return string The script output.
     */
    public function render($nameOrModel, $values = null)
    {
        $port  = $this->requestUri->getPort();
        $host  = $this->requestUri->getHost();
        $host .= $port ? ':' . $port : '';
        return $nameOrModel->getFormattedApiBlueprint($this->requestUri->getScheme(), $host);
    }
}
