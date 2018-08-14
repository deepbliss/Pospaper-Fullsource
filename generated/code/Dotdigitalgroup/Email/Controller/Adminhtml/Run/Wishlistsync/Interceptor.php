<?php
namespace Dotdigitalgroup\Email\Controller\Adminhtml\Run\Wishlistsync;

/**
 * Interceptor class for @see \Dotdigitalgroup\Email\Controller\Adminhtml\Run\Wishlistsync
 */
class Interceptor extends \Dotdigitalgroup\Email\Controller\Adminhtml\Run\Wishlistsync implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Dotdigitalgroup\Email\Model\Sync\WishlistFactory $wishlistFactory, \Magento\Backend\App\Action\Context $context)
    {
        $this->___init();
        parent::__construct($wishlistFactory, $context);
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
