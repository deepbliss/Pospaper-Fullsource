<?php
namespace Emipro\Importexportattributeoption\Controller\Adminhtml\Index\Checkvalidation;

/**
 * Interceptor class for @see \Emipro\Importexportattributeoption\Controller\Adminhtml\Index\Checkvalidation
 */
class Interceptor extends \Emipro\Importexportattributeoption\Controller\Adminhtml\Index\Checkvalidation implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory, \Magento\Framework\Controller\ResultFactory $resultFactory, \Magento\Eav\Model\Config $attribute, \Magento\Framework\Registry $registry, \Magento\Framework\Filesystem\DirectoryList $directoryList, \Magento\Eav\Model\Entity\Attribute $entityAttribute, \Magento\Catalog\Model\ResourceModel\Eav\Attribute $eavAttribute, \Magento\Eav\Model\Entity\Attribute\Source\Table $sourceTable, \Magento\Framework\Filesystem $filesystem, \Magento\Framework\File\Csv $csvWriter, \Magento\Framework\Controller\Result\RawFactory $resultRawFactory, \Magento\Framework\App\Response\Http\FileFactory $fileFactory, \Magento\Framework\Filesystem\Io\File $file)
    {
        $this->___init();
        parent::__construct($context, $uploaderFactory, $resultFactory, $attribute, $registry, $directoryList, $entityAttribute, $eavAttribute, $sourceTable, $filesystem, $csvWriter, $resultRawFactory, $fileFactory, $file);
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
