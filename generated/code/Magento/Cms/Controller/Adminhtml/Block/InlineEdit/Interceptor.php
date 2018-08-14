<?php
namespace Magento\Cms\Controller\Adminhtml\Block\InlineEdit;

/**
 * Interceptor class for @see \Magento\Cms\Controller\Adminhtml\Block\InlineEdit
 */
class Interceptor extends \Magento\Cms\Controller\Adminhtml\Block\InlineEdit implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Cms\Api\BlockRepositoryInterface $blockRepository, \Magento\Framework\Controller\Result\JsonFactory $jsonFactory)
    {
        $this->___init();
        parent::__construct($context, $blockRepository, $jsonFactory);
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
