<?php
namespace Magento\Review\Block\Customer\View;

/**
 * Interceptor class for @see \Magento\Review\Block\Customer\View
 */
class Interceptor extends \Magento\Review\Block\Customer\View implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Block\Product\Context $context, \Magento\Catalog\Api\ProductRepositoryInterface $productRepository, \Magento\Review\Model\ReviewFactory $reviewFactory, \Magento\Review\Model\Rating\Option\VoteFactory $voteFactory, \Magento\Review\Model\RatingFactory $ratingFactory, \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $productRepository, $reviewFactory, $voteFactory, $ratingFactory, $currentCustomer, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function isRedirectToCartEnabled()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isRedirectToCartEnabled');
        if (!$pluginInfo) {
            return parent::isRedirectToCartEnabled();
        } else {
            return $this->___callPlugins('isRedirectToCartEnabled', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getImage($product, $imageId, $attributes = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getImage');
        if (!$pluginInfo) {
            return parent::getImage($product, $imageId, $attributes);
        } else {
            return $this->___callPlugins('getImage', func_get_args(), $pluginInfo);
        }
    }
}
