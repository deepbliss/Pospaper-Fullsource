<?php
namespace Magento\Cms\Model\Page\DataProvider;

/**
 * Interceptor class for @see \Magento\Cms\Model\Page\DataProvider
 */
class Interceptor extends \Magento\Cms\Model\Page\DataProvider implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct($name, $primaryFieldName, $requestFieldName, \Magento\Cms\Model\ResourceModel\Page\CollectionFactory $pageCollectionFactory, \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor, array $meta = array(), array $data = array())
    {
        $this->___init();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $pageCollectionFactory, $dataPersistor, $meta, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function prepareMeta(array $meta)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'prepareMeta');
        if (!$pluginInfo) {
            return parent::prepareMeta($meta);
        } else {
            return $this->___callPlugins('prepareMeta', func_get_args(), $pluginInfo);
        }
    }
}
