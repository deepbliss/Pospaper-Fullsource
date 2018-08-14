<?php
namespace Magestore\Megamenu\Controller\Adminhtml\Megamenu\Index;

/**
 * Interceptor class for @see \Magestore\Megamenu\Controller\Adminhtml\Megamenu\Index
 */
class Interceptor extends \Magestore\Megamenu\Controller\Adminhtml\Megamenu\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magestore\Megamenu\Helper\Image $imageHelper, \Magento\Framework\App\CacheInterface $cacheInterface, \Magento\Framework\App\Cache\TypeListInterface $typeListInterface, \Magento\Config\Model\ResourceModel\Config $configResoure, \Magestore\Megamenu\Model\ResourceModel\Megamenu\CollectionFactory $collectionFactory)
    {
        $this->___init();
        parent::__construct($context, $imageHelper, $cacheInterface, $typeListInterface, $configResoure, $collectionFactory);
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
