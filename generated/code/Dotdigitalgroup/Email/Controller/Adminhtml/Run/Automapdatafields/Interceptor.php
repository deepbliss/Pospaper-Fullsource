<?php
namespace Dotdigitalgroup\Email\Controller\Adminhtml\Run\Automapdatafields;

/**
 * Interceptor class for @see \Dotdigitalgroup\Email\Controller\Adminhtml\Run\Automapdatafields
 */
class Interceptor extends \Dotdigitalgroup\Email\Controller\Adminhtml\Run\Automapdatafields implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Dotdigitalgroup\Email\Helper\Data $data, \Dotdigitalgroup\Email\Model\Connector\Datafield $datafield, \Magento\Backend\App\Action\Context $context, \Magento\Framework\Escaper $escaper, \Magento\Framework\App\Config\ReinitableConfigInterface $config)
    {
        $this->___init();
        parent::__construct($data, $datafield, $context, $escaper, $config);
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
