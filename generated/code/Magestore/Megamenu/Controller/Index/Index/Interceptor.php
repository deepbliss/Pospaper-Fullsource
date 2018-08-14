<?php
namespace Magestore\Megamenu\Controller\Index\Index;

/**
 * Interceptor class for @see \Magestore\Megamenu\Controller\Index\Index
 */
class Interceptor extends \Magestore\Megamenu\Controller\Index\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magestore\Megamenu\Model\MegamenuFactory $megamenuFactory)
    {
        $this->___init();
        parent::__construct($context, $megamenuFactory);
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
