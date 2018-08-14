<?php
namespace Magehit\Bestsellerproducts\Block\BestsellerList;

/**
 * Interceptor class for @see \Magehit\Bestsellerproducts\Block\BestsellerList
 */
class Interceptor extends \Magehit\Bestsellerproducts\Block\BestsellerList implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Block\Product\Context $context, \Magento\Framework\Data\Helper\PostHelper $postDataHelper, \Magento\Catalog\Model\Layer\Resolver $layerResolver, \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository, \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollection, \Magento\Framework\Url\Helper\Data $urlHelper, \Magento\Catalog\Model\ResourceModel\Product\Collection $collection, \Magento\Catalog\Model\Category $category, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Catalog\Helper\Image $imageHelper, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $postDataHelper, $layerResolver, $categoryRepository, $categoryCollection, $urlHelper, $collection, $category, $scopeConfig, $imageHelper, $data);
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
    public function getProductDetailsHtml(\Magento\Catalog\Model\Product $product)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getProductDetailsHtml');
        if (!$pluginInfo) {
            return parent::getProductDetailsHtml($product);
        } else {
            return $this->___callPlugins('getProductDetailsHtml', func_get_args(), $pluginInfo);
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
