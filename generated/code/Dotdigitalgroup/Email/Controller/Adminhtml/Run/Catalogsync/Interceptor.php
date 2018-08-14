<?php
namespace Dotdigitalgroup\Email\Controller\Adminhtml\Run\Catalogsync;

/**
 * Interceptor class for @see \Dotdigitalgroup\Email\Controller\Adminhtml\Run\Catalogsync
 */
class Interceptor extends \Dotdigitalgroup\Email\Controller\Adminhtml\Run\Catalogsync implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Dotdigitalgroup\Email\Model\Sync\CatalogFactory $catalogFactory, \Magento\Backend\App\Action\Context $context)
    {
        $this->___init();
        parent::__construct($catalogFactory, $context);
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
