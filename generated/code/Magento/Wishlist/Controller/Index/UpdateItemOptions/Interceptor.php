<?php
namespace Magento\Wishlist\Controller\Index\UpdateItemOptions;

/**
 * Interceptor class for @see \Magento\Wishlist\Controller\Index\UpdateItemOptions
 */
class Interceptor extends \Magento\Wishlist\Controller\Index\UpdateItemOptions implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Customer\Model\Session $customerSession, \Magento\Wishlist\Controller\WishlistProviderInterface $wishlistProvider, \Magento\Catalog\Api\ProductRepositoryInterface $productRepository, \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator)
    {
        $this->___init();
        parent::__construct($context, $customerSession, $wishlistProvider, $productRepository, $formKeyValidator);
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
