<?php
namespace Magento\Wishlist\Controller\Index\Update;

/**
 * Interceptor class for @see \Magento\Wishlist\Controller\Index\Update
 */
class Interceptor extends \Magento\Wishlist\Controller\Index\Update implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator, \Magento\Wishlist\Controller\WishlistProviderInterface $wishlistProvider, \Magento\Wishlist\Model\LocaleQuantityProcessor $quantityProcessor)
    {
        $this->___init();
        parent::__construct($context, $formKeyValidator, $wishlistProvider, $quantityProcessor);
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
