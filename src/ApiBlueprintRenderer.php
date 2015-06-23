<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @copyright Copyright (c) 2015 Apiary Ltd. <support@apiary.io>
 */

namespace ZF\Apigility\Documentation\ApiBlueprint;

use Zend\View\Renderer\RendererInterface as Renderer;
use Zend\View\Resolver\ResolverInterface;
use Zend\Uri\Http as Uri;

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
