<?php
namespace Emipro\Importexportattributeoption\Controller\Adminhtml\Index\Export;

/**
 * Interceptor class for @see \Emipro\Importexportattributeoption\Controller\Adminhtml\Index\Export
 */
class Interceptor extends \Emipro\Importexportattributeoption\Controller\Adminhtml\Index\Export implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory $attrOptionCollectionFactory, \Magento\Store\Model\ResourceModel\Store\Collection $store, \Magento\Catalog\Model\ResourceModel\Eav\Attribute $eavAttribute, \Magento\Framework\App\Response\Http\FileFactory $fileFactory)
    {
        $this->___init();
        parent::__construct($context, $attrOptionCollectionFactory, $store, $eavAttribute, $fileFactory);
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
