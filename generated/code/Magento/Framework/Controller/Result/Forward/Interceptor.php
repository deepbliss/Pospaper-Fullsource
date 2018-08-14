<?php
namespace Magento\Framework\Controller\Result\Forward;

/**
 * Interceptor class for @see \Magento\Framework\Controller\Result\Forward
 */
class Interceptor extends \Magento\Framework\Controller\Result\Forward implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\RequestInterface $request)
    {
        $this->___init();
        parent::__construct($request);
    }

    /**
     * {@inheritdoc}
     */
    public function renderResult(\Magento\Framework\App\ResponseInterface $response)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'renderResult');
        if (!$pluginInfo) {
            return parent::renderResult($response);
        } else {
            return $this->___callPlugins('renderResult', func_get_args(), $pluginInfo);
        }
    }
}
