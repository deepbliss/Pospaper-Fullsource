<?php
namespace Magento\Search\Controller\Adminhtml\Synonyms\Delete;

/**
 * Interceptor class for @see \Magento\Search\Controller\Adminhtml\Synonyms\Delete
 */
class Interceptor extends \Magento\Search\Controller\Adminhtml\Synonyms\Delete implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Search\Api\SynonymGroupRepositoryInterface $synGroupRepository, \Psr\Log\LoggerInterface $logger)
    {
        $this->___init();
        parent::__construct($context, $synGroupRepository, $logger);
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
