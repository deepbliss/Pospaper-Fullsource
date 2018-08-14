<?php
namespace Dotdigitalgroup\Email\Block\Review;

/**
 * Interceptor class for @see \Dotdigitalgroup\Email\Block\Review
 */
class Interceptor extends \Dotdigitalgroup\Email\Block\Review implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Block\Product\Context $context, \Magento\Sales\Model\ResourceModel\Order $orderResource, \Dotdigitalgroup\Email\Model\ResourceModel\Review $review, \Magento\Sales\Model\OrderFactory $orderFactory, \Dotdigitalgroup\Email\Helper\Data $helper, \Magento\Framework\Pricing\Helper\Data $priceHelper, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $orderResource, $review, $orderFactory, $helper, $priceHelper, $data);
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
