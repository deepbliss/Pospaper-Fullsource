<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_AdvancedLayeredNavigation
 * @author    Webkul
 * @copyright Copyright (c) 2010-2018 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace Webkul\AdvancedLayeredNavigation\Model\Layer\Filter;

use Magento\CatalogSearch\Model\Layer\Filter\Attribute as CoreAttribute;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Layer attribute filter
 */
class Attribute extends CoreAttribute
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $_request;
    protected $filterValue = true;
    /**
     * @var \Magento\Framework\Filter\StripTags
     */
    private $tagFilter;

    protected $_coreSession;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\Layer $layer
     * @param \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder
     * @param \Magento\Framework\Filter\StripTags $tagFilter
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Layer $layer,
        \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder,
        \Magento\Framework\Filter\StripTags $tagFilter,
        \Magento\Framework\Session\SessionManagerInterface $coreSession,
        ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        parent::__construct(
            $filterItemFactory,
            $storeManager,
            $layer,
            $itemDataBuilder,
            $tagFilter,
            $data
        );
        $this->_coreSession = $coreSession;
        $this->scopeConfig = $scopeConfig;
        $this->tagFilter = $tagFilter;
    }

    /**
     * @return \Magento\Framework\App\RequestInterface
     */
    protected function _getRequest()
    {
        return $this->_request;
    }

    /**
     * Apply attribute option filter to product collection
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function apply(\Magento\Framework\App\RequestInterface $request)
    {
        $this->_request = $request;
        if (empty($request->getParam($this->_requestVar))) {
            $this->filterValue = false;
            return $this;
        }

        /** @var \Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection $productCollection */
        $productCollection = $this->getLayer()
            ->getProductCollection();
            
        $attribute = $this->getAttributeModel();
        $attributeValues = $this->getValueAsArray();

        if (!empty($attributeValues)) {
            $isElasticSearchEnable = $this->scopeConfig->getValue('catalog/search/elasticsearch_enable_auth', ScopeInterface::SCOPE_STORE);
            if ($isElasticSearchEnable) {
                $productCollection->addFieldToFilter($attribute->getAttributeCode(), $attributeValues);
            } else {
                $productCollection->addFieldToFilter($attribute->getAttributeCode(), ['in' => $attributeValues]);
            }
        }
        foreach ($attributeValues as $value) {
            $label = $this->getOptionText($value);
            $this->getLayer()
                ->getState()
                ->addFilter($this->_createItem($label, $value));
        }
        return $this;
    }


    public function getId()
    {
        return $this->getAttributeModel()->getId();
    }

    /**
     * Get filter values
     *
     * @return array
     */
    public function getValueAsArray()
    {
        $paramValue = $this->_getRequest()->getParam($this->_requestVar);
        if (!$paramValue) {
            return [];
        }
        $requestValue = $this->_getRequest()->getParam($this->_requestVar);
        return explode('_', $requestValue);
    }


    /**
     * Get filter value for reset current filter state
     *
     * @param string $value
     * @return string
     */
    public function getResetOptionValue($value)
    {
        $attributeValues = $this->getValueAsArray();
        $key = array_search($value, $attributeValues);
        unset($attributeValues[$key]);
        return implode('_', $attributeValues);
    }

    /**
     * Get data array for building attribute filter items
     *
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _getItemsData()
    {
        $attribute = $this->getAttributeModel();
        $productCollection = $this->getLayer()
            ->getProductCollection();
        
        $query = '';
        if ($this->_getRequest()->getParam('q')) {
            $query = $this->_getRequest()->getParam('q');
        }
        
        $categoryId = $this->getLayer()->getCurrentCategory()->getId();
        $optionsFacetedData = [];
        if ($this->_getRequest()->getParam($this->_requestVar)) {
            $optionsFacetedData = $this->_coreSession->getData('facted_data_'.$categoryId.$query.$attribute->getAttributeCode());
        }

        $attribute = $this->getAttributeModel();
        if (!$optionsFacetedData || !$productCollection->getSize()) {
            $optionsFacetedData = $productCollection->getFacetedData($attribute->getAttributeCode());
            if (!$this->_getRequest()->getParam($this->_requestVar)) {
                $this->_coreSession->setData('facted_data_'.$categoryId.$query.$attribute->getAttributeCode(), $optionsFacetedData);
            }
        }

        $options = $attribute->getFrontend()
            ->getSelectOptions();

        foreach ($options as $option) {
            // Check filter type
            if (empty($optionsFacetedData[$option['value']]['count'])) {
                continue;
            }
            $this->itemDataBuilder->addItemData(
                $this->tagFilter->filter($option['label']),
                $option['value'],
                isset($optionsFacetedData[$option['value']]['count']) ?
                    $optionsFacetedData[$option['value']]['count'] : 0
            );
        }

        return $this->itemDataBuilder->build();
    }
}
