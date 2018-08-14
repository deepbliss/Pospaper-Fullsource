<?php
namespace Wyomind\SimpleGoogleShopping\Controller\Adminhtml\Feeds\Categories;

/**
 * Interceptor class for @see \Wyomind\SimpleGoogleShopping\Controller\Adminhtml\Feeds\Categories
 */
class Interceptor extends \Wyomind\SimpleGoogleShopping\Controller\Adminhtml\Feeds\Categories implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Model\Context $contextModel, \Magento\Framework\Registry $coreRegistry, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Wyomind\Core\Helper\Data $coreHelper, \Wyomind\SimpleGoogleShopping\Helper\Data $sgsHelper, \Wyomind\SimpleGoogleShopping\Model\Feeds $sgsModel, \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory, \Magento\Framework\Controller\Result\RawFactory $resultRawFactory, \Magento\Framework\Filesystem\Directory\ReadFactory $directoryRead, \Wyomind\SimpleGoogleShopping\Helper\Parser $parserHelper, \Magento\Eav\Api\AttributeRepositoryInterface $attributeRepository, \Magento\Framework\Module\Dir\Reader $directoryReader)
    {
        $this->___init();
        parent::__construct($context, $contextModel, $coreRegistry, $resultPageFactory, $coreHelper, $sgsHelper, $sgsModel, $resultForwardFactory, $resultRawFactory, $directoryRead, $parserHelper, $attributeRepository, $directoryReader);
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
