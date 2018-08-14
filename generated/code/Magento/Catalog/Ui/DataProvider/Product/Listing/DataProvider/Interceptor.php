<?php
namespace Magento\Catalog\Ui\DataProvider\Product\Listing\DataProvider;

/**
 * Interceptor class for @see \Magento\Catalog\Ui\DataProvider\Product\Listing\DataProvider
 */
class Interceptor extends \Magento\Catalog\Ui\DataProvider\Product\Listing\DataProvider implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct($name, \Magento\Framework\View\Element\UiComponent\DataProvider\Reporting $reporting, \Magento\Framework\Api\Search\SearchCriteriaBuilder $searchCriteriaBuilder, \Magento\Framework\App\RequestInterface $request, \Magento\Framework\Api\FilterBuilder $filterBuilder, \Magento\Store\Model\StoreManager $storeManager, array $meta = array(), array $data = array())
    {
        $this->___init();
        parent::__construct($name, $reporting, $searchCriteriaBuilder, $request, $filterBuilder, $storeManager, $meta, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getData');
        if (!$pluginInfo) {
            return parent::getData();
        } else {
            return $this->___callPlugins('getData', func_get_args(), $pluginInfo);
        }
    }
}
