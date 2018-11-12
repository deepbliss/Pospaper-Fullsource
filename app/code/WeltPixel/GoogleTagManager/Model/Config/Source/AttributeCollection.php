<?php

namespace WeltPixel\GoogleTagManager\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * @package WeltPixel\GoogleTagManager\Model\Config\Source
 */
class AttributeCollection implements ArrayInterface
{

	/**
	 * @var \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory
	 */
	private $_attributeCollectionFactory;

	/**
	 * @param  \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $attributeCollectionFactory
	 */
	public function __construct(\Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $attributeCollectionFactory)
	{
		$this->_attributeCollectionFactory = $attributeCollectionFactory;
	}

	/**
	 * Return list of Attributes
	 *
	 * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
	 */
	public function toOptionArray()
	{
		$arr = [];
		$attributesCollection = $this->_attributeCollectionFactory->create()
			->addFieldToFilter('used_in_product_listing', 1);
		foreach ($attributesCollection as $attribute) {
			$arr[] = array(
				'value' => $attribute->getData('attribute_code'),
				'label' => $attribute->getData('frontend_label')
			);
		}
		return $arr;
	}
}
