<?php
namespace Magento\Framework\Controller\Result\Json;

/**
 * Interceptor class for @see \Magento\Framework\Controller\Result\Json
 */
class Interceptor extends \Magento\Framework\Controller\Result\Json implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Translate\InlineInterface $translateInline)
    {
        $this->___init();
        parent::__construct($translateInline);
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
