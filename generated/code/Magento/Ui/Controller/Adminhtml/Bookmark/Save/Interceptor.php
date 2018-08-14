<?php
namespace Magento\Ui\Controller\Adminhtml\Bookmark\Save;

/**
 * Interceptor class for @see \Magento\Ui\Controller\Adminhtml\Bookmark\Save
 */
class Interceptor extends \Magento\Ui\Controller\Adminhtml\Bookmark\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\View\Element\UiComponentFactory $factory, \Magento\Ui\Api\BookmarkRepositoryInterface $bookmarkRepository, \Magento\Ui\Api\BookmarkManagementInterface $bookmarkManagement, \Magento\Ui\Api\Data\BookmarkInterfaceFactory $bookmarkFactory, \Magento\Authorization\Model\UserContextInterface $userContext, \Magento\Framework\Json\DecoderInterface $jsonDecoder)
    {
        $this->___init();
        parent::__construct($context, $factory, $bookmarkRepository, $bookmarkManagement, $bookmarkFactory, $userContext, $jsonDecoder);
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
