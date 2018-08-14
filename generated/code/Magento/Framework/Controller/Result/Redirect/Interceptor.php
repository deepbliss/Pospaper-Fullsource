<?php
namespace Magento\Framework\Controller\Result\Redirect;

/**
 * Interceptor class for @see \Magento\Framework\Controller\Result\Redirect
 */
class Interceptor extends \Magento\Framework\Controller\Result\Redirect implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Response\RedirectInterface $redirect, \Magento\Framework\UrlInterface $urlBuilder)
    {
        $this->___init();
        parent::__construct($redirect, $urlBuilder);
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
