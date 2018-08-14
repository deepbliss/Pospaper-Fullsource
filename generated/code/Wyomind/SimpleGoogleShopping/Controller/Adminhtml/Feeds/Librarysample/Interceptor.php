<?php
namespace Wyomind\SimpleGoogleShopping\Controller\Adminhtml\Feeds\Librarysample;

/**
 * Interceptor class for @see \Wyomind\SimpleGoogleShopping\Controller\Adminhtml\Feeds\Librarysample
 */
class Interceptor extends \Wyomind\SimpleGoogleShopping\Controller\Adminhtml\Feeds\Librarysample implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Eav\Api\AttributeRepositoryInterface $attributeRepository, \Magento\Catalog\Api\ProductRepositoryInterface $productRepository, \Magento\Catalog\Api\ProductAttributeOptionManagementInterface $productAttributeRepository, \Wyomind\Core\Helper\Data $coreHelper)
    {
        $this->___init();
        parent::__construct($context, $attributeRepository, $productRepository, $productAttributeRepository, $coreHelper);
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
