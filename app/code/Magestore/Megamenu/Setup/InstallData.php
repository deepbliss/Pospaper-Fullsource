<?php

/**
 * Magestore.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Magestore
 * @package     Magestore_Megamenu
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */
namespace Magestore\Megamenu\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * {@inheritdoc}
     */

    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $_categoryFactory;
    /**
     * @var MegamenuFactory
     */
    protected $_megamenuFatory;
    /**
     * @var State
     */
    protected $_state;

    public function __construct(
        \Magestore\Megamenu\Model\MegamenuFactory $factory,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Framework\App\State $state
    )
    {
        $this->_megamenuFatory = $factory;
        $this->_categoryFactory = $categoryFactory;
        $this->_state = $state;
    }
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $this->setAreaCode('adminhtml');
        $items = $this->getSampleData();
        $i=0;
        foreach($items as $item){
            if($i== 8) break;
            $this->createMegamenu()->setData($item)->save();
            $i++;
        }
        $installer->endSetup();
    }

    /**
     * @return Megamenu
     */
    public function createMegamenu()
    {
        return $this->_megamenuFatory->create();
    }
    /**
     * get category for sample data
     */
    public function getCategory(){
        $root = $this->_categoryFactory->create()->getCollection()->addAttributeToSelect('*')->getFirstItem();
        $childrendIds = $root->getAllChildren();
        $childrendIds = explode(',',$childrendIds);
        unset($childrendIds[0]);unset($childrendIds[1]);
        $categories = $this->_categoryFactory->create()->getCollection()
            ->addAttributeToSelect('*')
            ->addFieldToFilter('entity_id', array('in' => $childrendIds))
            ->addFieldToFilter('is_active', 1)
            ->addFieldToFilter('include_in_menu', 1)
            ->setOrder('position','ASC');
        $categoryIds = $categories->getAllIds();
        foreach($categories as $category){
            $parentIds = $category->getParentIds();
            if(count(array_intersect($parentIds, $categoryIds))== 0){
                $parents[] = $category;
            }

        }
        return $parents;
    }
    function setAreaCode($code){
        $this->_state->setAreaCode($code);
        return $this;
    }
    /**
     * Create Sample data
     */
    public function getSampleData(){
        $categories = $this->getCategory();
        $i= 1;
        $data[] = [
            'name_menu' =>'Home',
            'stores'=> 0,
            'link' => '#',
            'sort_order'=>0,
            'megamenu_type'=>0,
            'status'=>1,
            'menu_type'=>\Magestore\Megamenu\Model\Megamenu::ANCHOR_TEXT,
            'submenu_align'=>2,
            'submenu_width'=>20,
            'featured_type'=> 0,
        ];
        foreach($categories as $category){
            $childIds = $category ->getAllChildren();
            $childIds = explode(',',$childIds);
            unset($childIds[0]);
            if(count($childIds)> 0)
                $type = \Magestore\Megamenu\Model\Megamenu::CATEGORY_LEVEL;
            else
                $type = \Magestore\Megamenu\Model\Megamenu::ANCHOR_TEXT;
            $childIds = implode(', ',$childIds);
            $data[] = [
                'name_menu' =>$category->getName(),
                'stores'=> 0,
                'link' =>$category->getUrl(),
                'sort_order'=>$i,
                'megamenu_type'=>0,
                'status'=>1,
                'menu_type'=>$type,
                'categories'=>$childIds,
                'submenu_align'=>2,
                'submenu_width'=>20,
                'featured_type'=> 0,
            ];
            $i++;
        }
        return $data;
    }
}
