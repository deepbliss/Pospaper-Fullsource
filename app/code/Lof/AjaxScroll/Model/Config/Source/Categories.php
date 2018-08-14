<?php
/**
 * Landofcoder
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the landofcoder.com license that is
 * available through the world-wide-web at this URL:
 * http://landofcoder.com/license
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category   Landofcoder
 * @package    Lof_AjaxScroll
 * @copyright  Copyright (c) 2016 Landofcoder (http://www.landofcoder.com/)
 * @license    http://www.landofcoder.com/LICENSE-1.0.html
 */

namespace Lof\AjaxScroll\Model\Config\Source;

class Categories implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Magento\User\Model\UserFactory
     */
	protected $_categoryFactory;
    
    /**
     * @param \Magento\Catalog\Model\CategoryFactory $categoryFactory
     */
	public function __construct(
        \Magento\Catalog\Model\CategoryFactory $categoryFactory
	){
        $this->_categoryFactory = $categoryFactory;
	}

    public function toOptionArray()
    {   
        $result = [];
        $items = $this->_categoryFactory->create()->getCollection()->addAttributeToSelect(
            'name'
        )->addAttributeToSort(
            'entity_id',
            'ASC'
        )->load()->getItems();
        foreach ($items as $item) {
            $result[] = [
                'label' => $item->getName(),
                'value' => $item->getEntityId()
                ];
        }  
        return $result;
    }
}