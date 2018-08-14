<?php
namespace Aheadworks\RewardPoints\Controller\Unsubscribe\Index;

/**
 * Interceptor class for @see \Aheadworks\RewardPoints\Controller\Unsubscribe\Index
 */
class Interceptor extends \Aheadworks\RewardPoints\Controller\Unsubscribe\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Aheadworks\RewardPoints\Model\Service\PointsSummaryService $pointsSummaryService, \Magento\Framework\DataObject $dataObject, \Aheadworks\RewardPoints\Model\KeyEncryptor $keyEncryptor)
    {
        $this->___init();
        parent::__construct($context, $pointsSummaryService, $dataObject, $keyEncryptor);
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
