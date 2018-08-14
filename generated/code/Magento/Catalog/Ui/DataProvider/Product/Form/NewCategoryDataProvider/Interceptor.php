<?php
namespace Magento\Catalog\Ui\DataProvider\Product\Form\NewCategoryDataProvider;

/**
 * Interceptor class for @see \Magento\Catalog\Ui\DataProvider\Product\Form\NewCategoryDataProvider
 */
class Interceptor extends \Magento\Catalog\Ui\DataProvider\Product\Form\NewCategoryDataProvider implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct($name, $primaryFieldName, $requestFieldName, \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory, \Magento\Framework\UrlInterface $urlBuilder, array $meta = array(), array $data = array())
    {
        $this->___init();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $collectionFactory, $urlBuilder, $meta, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getMeta');
        if (!$pluginInfo) {
            return parent::getMeta();
        } else {
            return $this->___callPlugins('getMeta', func_get_args(), $pluginInfo);
        }
    }
}
