<?php
namespace Magento\Framework\Webapi\ErrorProcessor;

/**
 * Interceptor class for @see \Magento\Framework\Webapi\ErrorProcessor
 */
class Interceptor extends \Magento\Framework\Webapi\ErrorProcessor implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Json\Encoder $encoder, \Magento\Framework\App\State $appState, \Psr\Log\LoggerInterface $logger, \Magento\Framework\Filesystem $filesystem, \Magento\Framework\Serialize\Serializer\Json $serializer = null)
    {
        $this->___init();
        parent::__construct($encoder, $appState, $logger, $filesystem, $serializer);
    }

    /**
     * {@inheritdoc}
     */
    public function maskException(\Exception $exception)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'maskException');
        if (!$pluginInfo) {
            return parent::maskException($exception);
        } else {
            return $this->___callPlugins('maskException', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function renderException(\Exception $exception, $httpCode = 500)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'renderException');
        if (!$pluginInfo) {
            return parent::renderException($exception, $httpCode);
        } else {
            return $this->___callPlugins('renderException', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function renderErrorMessage($errorMessage, $trace = 'Trace is not available.', $httpCode = 500)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'renderErrorMessage');
        if (!$pluginInfo) {
            return parent::renderErrorMessage($errorMessage, $trace, $httpCode);
        } else {
            return $this->___callPlugins('renderErrorMessage', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function registerShutdownFunction()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'registerShutdownFunction');
        if (!$pluginInfo) {
            return parent::registerShutdownFunction();
        } else {
            return $this->___callPlugins('registerShutdownFunction', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function apiShutdownFunction()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'apiShutdownFunction');
        if (!$pluginInfo) {
            return parent::apiShutdownFunction();
        } else {
            return $this->___callPlugins('apiShutdownFunction', func_get_args(), $pluginInfo);
        }
    }
}
