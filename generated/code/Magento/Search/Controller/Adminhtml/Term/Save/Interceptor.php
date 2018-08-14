<?php
namespace Magento\Search\Controller\Adminhtml\Term\Save;

/**
 * Interceptor class for @see \Magento\Search\Controller\Adminhtml\Term\Save
 */
class Interceptor extends \Magento\Search\Controller\Adminhtml\Term\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Search\Model\QueryFactory $queryFactory)
    {
        $this->___init();
        parent::__construct($context, $queryFactory);
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
