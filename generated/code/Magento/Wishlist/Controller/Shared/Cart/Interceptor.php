<?php
namespace Magento\Wishlist\Controller\Shared\Cart;

/**
 * Interceptor class for @see \Magento\Wishlist\Controller\Shared\Cart
 */
class Interceptor extends \Magento\Wishlist\Controller\Shared\Cart implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Checkout\Model\Cart $cart, \Magento\Wishlist\Model\Item\OptionFactory $optionFactory, \Magento\Wishlist\Model\ItemFactory $itemFactory, \Magento\Checkout\Helper\Cart $cartHelper, \Magento\Framework\Escaper $escaper)
    {
        $this->___init();
        parent::__construct($context, $cart, $optionFactory, $itemFactory, $cartHelper, $escaper);
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
