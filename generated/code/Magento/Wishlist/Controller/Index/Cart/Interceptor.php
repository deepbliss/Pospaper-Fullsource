<?php
namespace Magento\Wishlist\Controller\Index\Cart;

/**
 * Interceptor class for @see \Magento\Wishlist\Controller\Index\Cart
 */
class Interceptor extends \Magento\Wishlist\Controller\Index\Cart implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Wishlist\Controller\WishlistProviderInterface $wishlistProvider, \Magento\Wishlist\Model\LocaleQuantityProcessor $quantityProcessor, \Magento\Wishlist\Model\ItemFactory $itemFactory, \Magento\Checkout\Model\Cart $cart, \Magento\Wishlist\Model\Item\OptionFactory $optionFactory, \Magento\Catalog\Helper\Product $productHelper, \Magento\Framework\Escaper $escaper, \Magento\Wishlist\Helper\Data $helper, \Magento\Checkout\Helper\Cart $cartHelper, \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator)
    {
        $this->___init();
        parent::__construct($context, $wishlistProvider, $quantityProcessor, $itemFactory, $cart, $optionFactory, $productHelper, $escaper, $helper, $cartHelper, $formKeyValidator);
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
