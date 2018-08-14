<?php
namespace Aheadworks\RewardPoints\Controller\Adminhtml\Customers\Upload;

/**
 * Interceptor class for @see \Aheadworks\RewardPoints\Controller\Adminhtml\Customers\Upload
 */
class Interceptor extends \Aheadworks\RewardPoints\Controller\Adminhtml\Customers\Upload implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Aheadworks\RewardPoints\Model\FileUploader $fileUploader)
    {
        $this->___init();
        parent::__construct($context, $fileUploader);
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
