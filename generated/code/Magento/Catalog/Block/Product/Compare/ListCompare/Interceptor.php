<?php
namespace Magento\Catalog\Block\Product\Compare\ListCompare;

/**
 * Interceptor class for @see \Magento\Catalog\Block\Product\Compare\ListCompare
 */
class Interceptor extends \Magento\Catalog\Block\Product\Compare\ListCompare implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Block\Product\Context $context, \Magento\Framework\Url\EncoderInterface $urlEncoder, \Magento\Catalog\Model\ResourceModel\Product\Compare\Item\CollectionFactory $itemCollectionFactory, \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility, \Magento\Customer\Model\Visitor $customerVisitor, \Magento\Framework\App\Http\Context $httpContext, \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $urlEncoder, $itemCollectionFactory, $catalogProductVisibility, $customerVisitor, $httpContext, $currentCustomer, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getReviewsSummaryHtml(\Magento\Catalog\Model\Product $product, $templateType = false, $displayIfNoReviews = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getReviewsSummaryHtml');
        if (!$pluginInfo) {
            return parent::getReviewsSummaryHtml($product, $templateType, $displayIfNoReviews);
        } else {
            return $this->___callPlugins('getReviewsSummaryHtml', func_get_args(), $pluginInfo);
        }
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
