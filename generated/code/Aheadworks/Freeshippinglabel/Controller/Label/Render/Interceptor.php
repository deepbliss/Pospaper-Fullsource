<?php
namespace Aheadworks\Freeshippinglabel\Controller\Label\Render;

/**
 * Interceptor class for @see \Aheadworks\Freeshippinglabel\Controller\Label\Render
 */
class Interceptor extends \Aheadworks\Freeshippinglabel\Controller\Label\Render implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Aheadworks\Freeshippinglabel\Api\LabelRepositoryInterface $labelRepository)
    {
        $this->___init();
        parent::__construct($context, $labelRepository);
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
