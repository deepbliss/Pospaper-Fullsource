<?php
namespace Magento\Search\Controller\Adminhtml\Synonyms\MassDelete;

/**
 * Interceptor class for @see \Magento\Search\Controller\Adminhtml\Synonyms\MassDelete
 */
class Interceptor extends \Magento\Search\Controller\Adminhtml\Synonyms\MassDelete implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Ui\Component\MassAction\Filter $filter, \Magento\Search\Model\ResourceModel\SynonymGroup\CollectionFactory $collectionFactory, \Magento\Search\Api\SynonymGroupRepositoryInterface $synGroupRepository)
    {
        $this->___init();
        parent::__construct($context, $filter, $collectionFactory, $synGroupRepository);
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
