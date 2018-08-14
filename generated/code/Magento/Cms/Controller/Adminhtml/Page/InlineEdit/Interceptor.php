<?php
namespace Magento\Cms\Controller\Adminhtml\Page\InlineEdit;

/**
 * Interceptor class for @see \Magento\Cms\Controller\Adminhtml\Page\InlineEdit
 */
class Interceptor extends \Magento\Cms\Controller\Adminhtml\Page\InlineEdit implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Cms\Controller\Adminhtml\Page\PostDataProcessor $dataProcessor, \Magento\Cms\Api\PageRepositoryInterface $pageRepository, \Magento\Framework\Controller\Result\JsonFactory $jsonFactory)
    {
        $this->___init();
        parent::__construct($context, $dataProcessor, $pageRepository, $jsonFactory);
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
