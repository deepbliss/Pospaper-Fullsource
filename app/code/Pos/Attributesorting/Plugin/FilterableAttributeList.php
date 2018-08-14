<?php

namespace Pos\Attributesorting\Plugin;
class FilterableAttributeList 
    {

        protected $collectionFactory;


        protected $storeManager;

        /**
         * FilterableAttributeList constructor
         *
         * @param \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $collectionFactory
         * @param \Magento\Store\Model\StoreManagerInterface $storeManager
         */
        public function __construct(
            \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $collectionFactory,
            \Magento\Store\Model\StoreManagerInterface $storeManager
        ) {
            $this->collectionFactory = $collectionFactory;
            $this->storeManager = $storeManager;
        }
     public function afterGetList(\Magento\Catalog\Model\Layer\Category\FilterableAttributeList $subject, $collectionOld)
        {
          $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

          
            $coll = \Magento\Framework\App\ObjectManager::getInstance()->create(\Magento\Eav\Model\ResourceModel\Entity\Attribute\Collection::class);
            $coll->addFieldToFilter(\Magento\Eav\Model\Entity\Attribute\Set::KEY_ENTITY_TYPE_ID, 4);
            $attrAll = $coll->load()->getItems();
            foreach ($attrAll as $value) {
              $att_id = $value->getAttributeId();
              $attributeModel = \Magento\Framework\App\ObjectManager::getInstance()->get(\Magento\Eav\Model\Attribute::class)->load($att_id);
              $attributeModel->setData('position',0);
              $attributeModel->save();
            }


            $category = $objectManager->get('Magento\Framework\Registry')->registry('current_category');//get current category
            if(isset($category)):

                $showfilter = $category->getData('att_sorting');
                
            	  if(isset($showfilter)):
                $myArray = explode(',', $showfilter);
                $i=100;
                foreach ($myArray as $att_id) {
                $attributeModel = \Magento\Framework\App\ObjectManager::getInstance()->get(\Magento\Eav\Model\Attribute::class)->load($att_id);
                $attributeModel->setData('position',$i);
                $attributeModel->save();
                $i--;
                }
			endif;

            endif;

         /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection */
            $collection = $this->collectionFactory->create();
            $collection->setItemObjectClass(\Magento\Catalog\Model\ResourceModel\Eav\Attribute::class)
                ->addStoreLabel($this->storeManager->getStore()->getId())
                ->setOrder('position', 'DESC');
            $collection = $this->_prepareAttributeCollection($collection);
            $collection->load();

            return $collection;
        }

      protected function _prepareAttributeCollection($collection)
      {
         $collection->addIsFilterableFilter();
         return $collection;
      }
    }