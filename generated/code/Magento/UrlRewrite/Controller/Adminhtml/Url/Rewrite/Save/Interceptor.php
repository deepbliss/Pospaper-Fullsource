<?php
namespace Magento\UrlRewrite\Controller\Adminhtml\Url\Rewrite\Save;

/**
 * Interceptor class for @see \Magento\UrlRewrite\Controller\Adminhtml\Url\Rewrite\Save
 */
class Interceptor extends \Magento\UrlRewrite\Controller\Adminhtml\Url\Rewrite\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\CatalogUrlRewrite\Model\ProductUrlPathGenerator $productUrlPathGenerator, \Magento\CatalogUrlRewrite\Model\CategoryUrlPathGenerator $categoryUrlPathGenerator, \Magento\CmsUrlRewrite\Model\CmsPageUrlPathGenerator $cmsPageUrlPathGenerator, \Magento\UrlRewrite\Model\UrlFinderInterface $urlFinder)
    {
        $this->___init();
        parent::__construct($context, $productUrlPathGenerator, $categoryUrlPathGenerator, $cmsPageUrlPathGenerator, $urlFinder);
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        if (!$pluginInfo) {
            return parent::dispatch($request);
        } else {
            return $this->___callPlugins('dispatch', func_get_args(), $pluginInfo);
        }
    }
}
