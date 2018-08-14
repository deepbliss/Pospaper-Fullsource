<?php
namespace Amasty\Checkout\Controller\Adminhtml\Field\Save;

/**
 * Interceptor class for @see \Amasty\Checkout\Controller\Adminhtml\Field\Save
 */
class Interceptor extends \Amasty\Checkout\Controller\Adminhtml\Field\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Amasty\Checkout\Model\ResourceModel\Field $fieldResource, \Amasty\Checkout\Model\FieldFactory $fieldFactory, \Amasty\Checkout\Model\ResourceModel\Field\Store $storeResource, \Amasty\Checkout\Model\ResourceModel\Field\Store\CollectionFactory $storeCollectionFactory)
    {
        $this->___init();
        parent::__construct($context, $fieldResource, $fieldFactory, $storeResource, $storeCollectionFactory);
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
