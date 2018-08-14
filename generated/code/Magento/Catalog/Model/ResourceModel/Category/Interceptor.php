<?php
namespace Magento\Catalog\Model\ResourceModel\Category;

/**
 * Interceptor class for @see \Magento\Catalog\Model\ResourceModel\Category
 */
class Interceptor extends \Magento\Catalog\Model\ResourceModel\Category implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Eav\Model\Entity\Context $context, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Catalog\Model\Factory $modelFactory, \Magento\Framework\Event\ManagerInterface $eventManager, \Magento\Catalog\Model\ResourceModel\Category\TreeFactory $categoryTreeFactory, \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory, $data = array(), \Magento\Framework\Serialize\Serializer\Json $serializer = null)
    {
        $this->___init();
        parent::__construct($context, $storeManager, $modelFactory, $eventManager, $categoryTreeFactory, $categoryCollectionFactory, $data, $serializer);
    }

    /**
     * {@inheritdoc}
     */
    public function changeParent(\Magento\Catalog\Model\Category $category, \Magento\Catalog\Model\Category $newParent, $afterCategoryId = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'changeParent');
        if (!$pluginInfo) {
            return parent::changeParent($category, $newParent, $afterCategoryId);
        } else {
            return $this->___callPlugins('changeParent', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete($object)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'delete');
        if (!$pluginInfo) {
            return parent::delete($object);
        } else {
            return $this->___callPlugins('delete', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function save(\Magento\Framework\Model\AbstractModel $object)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'save');
        if (!$pluginInfo) {
            return parent::save($object);
        } else {
            return $this->___callPlugins('save', func_get_args(), $pluginInfo);
        }
    }
}
