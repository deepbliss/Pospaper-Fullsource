<?php
namespace Dotdigitalgroup\Email\Controller\Adminhtml\Run\Catalogreset;

/**
 * Interceptor class for @see \Dotdigitalgroup\Email\Controller\Adminhtml\Run\Catalogreset
 */
class Interceptor extends \Dotdigitalgroup\Email\Controller\Adminhtml\Run\Catalogreset implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Dotdigitalgroup\Email\Model\ResourceModel\Catalog $catalog, \Magento\Backend\App\Action\Context $context, \Dotdigitalgroup\Email\Helper\Data $data, \Magento\Framework\Escaper $escaper)
    {
        $this->___init();
        parent::__construct($catalog, $context, $data, $escaper);
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
