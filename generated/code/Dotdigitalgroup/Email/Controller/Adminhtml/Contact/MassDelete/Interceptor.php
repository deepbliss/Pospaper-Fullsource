<?php
namespace Dotdigitalgroup\Email\Controller\Adminhtml\Contact\MassDelete;

/**
 * Interceptor class for @see \Dotdigitalgroup\Email\Controller\Adminhtml\Contact\MassDelete
 */
class Interceptor extends \Dotdigitalgroup\Email\Controller\Adminhtml\Contact\MassDelete implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Dotdigitalgroup\Email\Model\ResourceModel\Contact $contactResource, \Magento\Backend\App\Action\Context $context, \Magento\Ui\Component\MassAction\Filter $filter, \Dotdigitalgroup\Email\Model\ResourceModel\Contact\CollectionFactory $collectionFactory)
    {
        $this->___init();
        parent::__construct($contactResource, $context, $filter, $collectionFactory);
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
