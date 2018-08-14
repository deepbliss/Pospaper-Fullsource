<?php
namespace Magento\Signifyd\Controller\Webhooks\Handler;

/**
 * Interceptor class for @see \Magento\Signifyd\Controller\Webhooks\Handler
 */
class Interceptor extends \Magento\Signifyd\Controller\Webhooks\Handler implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Signifyd\Model\SignifydGateway\Response\WebhookRequest $webhookRequest, \Psr\Log\LoggerInterface $logger, \Magento\Signifyd\Model\SignifydGateway\Response\WebhookMessageReader $webhookMessageReader, \Magento\Signifyd\Model\CaseServices\UpdatingServiceFactory $caseUpdatingServiceFactory, \Magento\Signifyd\Model\SignifydGateway\Response\WebhookRequestValidator $webhookRequestValidator, \Magento\Signifyd\Api\CaseRepositoryInterface $caseRepository, \Magento\Signifyd\Model\Config $config)
    {
        $this->___init();
        parent::__construct($context, $webhookRequest, $logger, $webhookMessageReader, $caseUpdatingServiceFactory, $webhookRequestValidator, $caseRepository, $config);
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
