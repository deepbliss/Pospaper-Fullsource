<?php
namespace Dotdigitalgroup\Email\Controller\Adminhtml\Run\Reviewsreset;

/**
 * Interceptor class for @see \Dotdigitalgroup\Email\Controller\Adminhtml\Run\Reviewsreset
 */
class Interceptor extends \Dotdigitalgroup\Email\Controller\Adminhtml\Run\Reviewsreset implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Dotdigitalgroup\Email\Model\ResourceModel\Review $review, \Magento\Backend\App\Action\Context $context, \Dotdigitalgroup\Email\Helper\Data $data)
    {
        $this->___init();
        parent::__construct($review, $context, $data);
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
