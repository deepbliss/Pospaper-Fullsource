<?php
namespace Magento\Customer\Model\ResourceModel\Customer\Indexer\Collection;

/**
 * Interceptor class for @see \Magento\Customer\Model\ResourceModel\Customer\Indexer\Collection
 */
class Interceptor extends \Magento\Customer\Model\ResourceModel\Customer\Indexer\Collection implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Data\Collection\EntityFactory $entityFactory, \Psr\Log\LoggerInterface $logger, \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy, \Magento\Framework\Event\ManagerInterface $eventManager, \Magento\Eav\Model\Config $eavConfig, \Magento\Framework\App\ResourceConnection $resource, \Magento\Eav\Model\EntityFactory $eavEntityFactory, \Magento\Eav\Model\ResourceModel\Helper $resourceHelper, \Magento\Framework\Validator\UniversalFactory $universalFactory, \Magento\Framework\Model\ResourceModel\Db\VersionControl\Snapshot $entitySnapshot, \Magento\Framework\DataObject\Copy\Config $fieldsetConfig, \Magento\Framework\DB\Adapter\AdapterInterface $connection = null, $modelName = 'Magento\\Customer\\Model\\Customer')
    {
        $this->___init();
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $eavConfig, $resource, $eavEntityFactory, $resourceHelper, $universalFactory, $entitySnapshot, $fieldsetConfig, $connection, $modelName);
    }

    /**
     * {@inheritdoc}
     */
    public function groupByEmail()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'groupByEmail');
        if (!$pluginInfo) {
            return parent::groupByEmail();
        } else {
            return $this->___callPlugins('groupByEmail', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addNameToSelect()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addNameToSelect');
        if (!$pluginInfo) {
            return parent::addNameToSelect();
        } else {
            return $this->___callPlugins('addNameToSelect', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSelectCountSql()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getSelectCountSql');
        if (!$pluginInfo) {
            return parent::getSelectCountSql();
        } else {
            return $this->___callPlugins('getSelectCountSql', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function fetchItem()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'fetchItem');
        if (!$pluginInfo) {
            return parent::fetchItem();
        } else {
            return $this->___callPlugins('fetchItem', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTable($table)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getTable');
        if (!$pluginInfo) {
            return parent::getTable($table);
        } else {
            return $this->___callPlugins('getTable', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setEntity($entity)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setEntity');
        if (!$pluginInfo) {
            return parent::setEntity($entity);
        } else {
            return $this->___callPlugins('setEntity', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getEntity');
        if (!$pluginInfo) {
            return parent::getEntity();
        } else {
            return $this->___callPlugins('getEntity', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getResource()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getResource');
        if (!$pluginInfo) {
            return parent::getResource();
        } else {
            return $this->___callPlugins('getResource', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setObject($object = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setObject');
        if (!$pluginInfo) {
            return parent::setObject($object);
        } else {
            return $this->___callPlugins('setObject', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addItem(\Magento\Framework\DataObject $object)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addItem');
        if (!$pluginInfo) {
            return parent::addItem($object);
        } else {
            return $this->___callPlugins('addItem', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAttribute($attributeCode)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAttribute');
        if (!$pluginInfo) {
            return parent::getAttribute($attributeCode);
        } else {
            return $this->___callPlugins('getAttribute', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addAttributeToFilter($attribute, $condition = null, $joinType = 'inner')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addAttributeToFilter');
        if (!$pluginInfo) {
            return parent::addAttributeToFilter($attribute, $condition, $joinType);
        } else {
            return $this->___callPlugins('addAttributeToFilter', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addFieldToFilter($attribute, $condition = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addFieldToFilter');
        if (!$pluginInfo) {
            return parent::addFieldToFilter($attribute, $condition);
        } else {
            return $this->___callPlugins('addFieldToFilter', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addAttributeToSort($attribute, $dir = 'ASC')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addAttributeToSort');
        if (!$pluginInfo) {
            return parent::addAttributeToSort($attribute, $dir);
        } else {
            return $this->___callPlugins('addAttributeToSort', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addAttributeToSelect($attribute, $joinType = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addAttributeToSelect');
        if (!$pluginInfo) {
            return parent::addAttributeToSelect($attribute, $joinType);
        } else {
            return $this->___callPlugins('addAttributeToSelect', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addEntityTypeToSelect($entityType, $prefix)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addEntityTypeToSelect');
        if (!$pluginInfo) {
            return parent::addEntityTypeToSelect($entityType, $prefix);
        } else {
            return $this->___callPlugins('addEntityTypeToSelect', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addStaticField($field)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addStaticField');
        if (!$pluginInfo) {
            return parent::addStaticField($field);
        } else {
            return $this->___callPlugins('addStaticField', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addExpressionAttributeToSelect($alias, $expression, $attribute)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addExpressionAttributeToSelect');
        if (!$pluginInfo) {
            return parent::addExpressionAttributeToSelect($alias, $expression, $attribute);
        } else {
            return $this->___callPlugins('addExpressionAttributeToSelect', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function groupByAttribute($attribute)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'groupByAttribute');
        if (!$pluginInfo) {
            return parent::groupByAttribute($attribute);
        } else {
            return $this->___callPlugins('groupByAttribute', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function joinAttribute($alias, $attribute, $bind, $filter = null, $joinType = 'inner', $storeId = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'joinAttribute');
        if (!$pluginInfo) {
            return parent::joinAttribute($alias, $attribute, $bind, $filter, $joinType, $storeId);
        } else {
            return $this->___callPlugins('joinAttribute', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function joinField($alias, $table, $field, $bind, $cond = null, $joinType = 'inner')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'joinField');
        if (!$pluginInfo) {
            return parent::joinField($alias, $table, $field, $bind, $cond, $joinType);
        } else {
            return $this->___callPlugins('joinField', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function joinTable($table, $bind, $fields = null, $cond = null, $joinType = 'inner')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'joinTable');
        if (!$pluginInfo) {
            return parent::joinTable($table, $bind, $fields, $cond, $joinType);
        } else {
            return $this->___callPlugins('joinTable', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeAttributeToSelect($attribute = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'removeAttributeToSelect');
        if (!$pluginInfo) {
            return parent::removeAttributeToSelect($attribute);
        } else {
            return $this->___callPlugins('removeAttributeToSelect', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setPage($pageNum, $pageSize)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setPage');
        if (!$pluginInfo) {
            return parent::setPage($pageNum, $pageSize);
        } else {
            return $this->___callPlugins('setPage', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function load($printQuery = false, $logQuery = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'load');
        if (!$pluginInfo) {
            return parent::load($printQuery, $logQuery);
        } else {
            return $this->___callPlugins('load', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAllIds($limit = null, $offset = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAllIds');
        if (!$pluginInfo) {
            return parent::getAllIds($limit, $offset);
        } else {
            return $this->___callPlugins('getAllIds', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAllIdsSql()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAllIdsSql');
        if (!$pluginInfo) {
            return parent::getAllIdsSql();
        } else {
            return $this->___callPlugins('getAllIdsSql', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'save');
        if (!$pluginInfo) {
            return parent::save();
        } else {
            return $this->___callPlugins('save', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'delete');
        if (!$pluginInfo) {
            return parent::delete();
        } else {
            return $this->___callPlugins('delete', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function importFromArray($arr)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'importFromArray');
        if (!$pluginInfo) {
            return parent::importFromArray($arr);
        } else {
            return $this->___callPlugins('importFromArray', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function exportToArray()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'exportToArray');
        if (!$pluginInfo) {
            return parent::exportToArray();
        } else {
            return $this->___callPlugins('exportToArray', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRowIdFieldName()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getRowIdFieldName');
        if (!$pluginInfo) {
            return parent::getRowIdFieldName();
        } else {
            return $this->___callPlugins('getRowIdFieldName', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIdFieldName()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getIdFieldName');
        if (!$pluginInfo) {
            return parent::getIdFieldName();
        } else {
            return $this->___callPlugins('getIdFieldName', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setRowIdFieldName($fieldName)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setRowIdFieldName');
        if (!$pluginInfo) {
            return parent::setRowIdFieldName($fieldName);
        } else {
            return $this->___callPlugins('setRowIdFieldName', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function _loadEntities($printQuery = false, $logQuery = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, '_loadEntities');
        if (!$pluginInfo) {
            return parent::_loadEntities($printQuery, $logQuery);
        } else {
            return $this->___callPlugins('_loadEntities', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function _loadAttributes($printQuery = false, $logQuery = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, '_loadAttributes');
        if (!$pluginInfo) {
            return parent::_loadAttributes($printQuery, $logQuery);
        } else {
            return $this->___callPlugins('_loadAttributes', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setOrder($attribute, $dir = 'ASC')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setOrder');
        if (!$pluginInfo) {
            return parent::setOrder($attribute, $dir);
        } else {
            return $this->___callPlugins('setOrder', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toArray($arrAttributes = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'toArray');
        if (!$pluginInfo) {
            return parent::toArray($arrAttributes);
        } else {
            return $this->___callPlugins('toArray', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getLoadedIds()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getLoadedIds');
        if (!$pluginInfo) {
            return parent::getLoadedIds();
        } else {
            return $this->___callPlugins('getLoadedIds', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'clear');
        if (!$pluginInfo) {
            return parent::clear();
        } else {
            return $this->___callPlugins('clear', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeAllItems()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'removeAllItems');
        if (!$pluginInfo) {
            return parent::removeAllItems();
        } else {
            return $this->___callPlugins('removeAllItems', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeItemByKey($key)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'removeItemByKey');
        if (!$pluginInfo) {
            return parent::removeItemByKey($key);
        } else {
            return $this->___callPlugins('removeItemByKey', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getMainTable()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getMainTable');
        if (!$pluginInfo) {
            return parent::getMainTable();
        } else {
            return $this->___callPlugins('getMainTable', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addFieldToSelect($field, $alias = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addFieldToSelect');
        if (!$pluginInfo) {
            return parent::addFieldToSelect($field, $alias);
        } else {
            return $this->___callPlugins('addFieldToSelect', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeFieldFromSelect($field)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'removeFieldFromSelect');
        if (!$pluginInfo) {
            return parent::removeFieldFromSelect($field);
        } else {
            return $this->___callPlugins('removeFieldFromSelect', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeAllFieldsFromSelect()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'removeAllFieldsFromSelect');
        if (!$pluginInfo) {
            return parent::removeAllFieldsFromSelect();
        } else {
            return $this->___callPlugins('removeAllFieldsFromSelect', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addBindParam($name, $value)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addBindParam');
        if (!$pluginInfo) {
            return parent::addBindParam($name, $value);
        } else {
            return $this->___callPlugins('addBindParam', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setConnection(\Magento\Framework\DB\Adapter\AdapterInterface $conn)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setConnection');
        if (!$pluginInfo) {
            return parent::setConnection($conn);
        } else {
            return $this->___callPlugins('setConnection', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSelect()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getSelect');
        if (!$pluginInfo) {
            return parent::getSelect();
        } else {
            return $this->___callPlugins('getSelect', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getConnection()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getConnection');
        if (!$pluginInfo) {
            return parent::getConnection();
        } else {
            return $this->___callPlugins('getConnection', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSize()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getSize');
        if (!$pluginInfo) {
            return parent::getSize();
        } else {
            return $this->___callPlugins('getSize', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSelectSql($stringMode = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getSelectSql');
        if (!$pluginInfo) {
            return parent::getSelectSql($stringMode);
        } else {
            return $this->___callPlugins('getSelectSql', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addOrder($field, $direction = 'DESC')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addOrder');
        if (!$pluginInfo) {
            return parent::addOrder($field, $direction);
        } else {
            return $this->___callPlugins('addOrder', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function unshiftOrder($field, $direction = 'DESC')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'unshiftOrder');
        if (!$pluginInfo) {
            return parent::unshiftOrder($field, $direction);
        } else {
            return $this->___callPlugins('unshiftOrder', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function distinct($flag)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'distinct');
        if (!$pluginInfo) {
            return parent::distinct($flag);
        } else {
            return $this->___callPlugins('distinct', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function loadWithFilter($printQuery = false, $logQuery = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'loadWithFilter');
        if (!$pluginInfo) {
            return parent::loadWithFilter($printQuery, $logQuery);
        } else {
            return $this->___callPlugins('loadWithFilter', func_get_args(), $pluginInfo);
        }
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

    /**
     * {@inheritdoc}
     */
    public function resetData()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'resetData');
        if (!$pluginInfo) {
            return parent::resetData();
        } else {
            return $this->___callPlugins('resetData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function loadData($printQuery = false, $logQuery = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'loadData');
        if (!$pluginInfo) {
            return parent::loadData($printQuery, $logQuery);
        } else {
            return $this->___callPlugins('loadData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function printLogQuery($printQuery = false, $logQuery = false, $sql = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'printLogQuery');
        if (!$pluginInfo) {
            return parent::printLogQuery($printQuery, $logQuery, $sql);
        } else {
            return $this->___callPlugins('printLogQuery', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addFilterToMap($filter, $alias, $group = 'fields')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addFilterToMap');
        if (!$pluginInfo) {
            return parent::addFilterToMap($filter, $alias, $group);
        } else {
            return $this->___callPlugins('addFilterToMap', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function joinExtensionAttribute(\Magento\Framework\Api\ExtensionAttribute\JoinDataInterface $join, \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $extensionAttributesJoinProcessor)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'joinExtensionAttribute');
        if (!$pluginInfo) {
            return parent::joinExtensionAttribute($join, $extensionAttributesJoinProcessor);
        } else {
            return $this->___callPlugins('joinExtensionAttribute', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getItemObjectClass()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getItemObjectClass');
        if (!$pluginInfo) {
            return parent::getItemObjectClass();
        } else {
            return $this->___callPlugins('getItemObjectClass', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addFilter($field, $value, $type = 'and')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addFilter');
        if (!$pluginInfo) {
            return parent::addFilter($field, $value, $type);
        } else {
            return $this->___callPlugins('addFilter', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFilter($field)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getFilter');
        if (!$pluginInfo) {
            return parent::getFilter($field);
        } else {
            return $this->___callPlugins('getFilter', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isLoaded()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isLoaded');
        if (!$pluginInfo) {
            return parent::isLoaded();
        } else {
            return $this->___callPlugins('isLoaded', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCurPage($displacement = 0)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCurPage');
        if (!$pluginInfo) {
            return parent::getCurPage($displacement);
        } else {
            return $this->___callPlugins('getCurPage', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getLastPageNumber()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getLastPageNumber');
        if (!$pluginInfo) {
            return parent::getLastPageNumber();
        } else {
            return $this->___callPlugins('getLastPageNumber', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPageSize()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getPageSize');
        if (!$pluginInfo) {
            return parent::getPageSize();
        } else {
            return $this->___callPlugins('getPageSize', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFirstItem()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getFirstItem');
        if (!$pluginInfo) {
            return parent::getFirstItem();
        } else {
            return $this->___callPlugins('getFirstItem', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getLastItem()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getLastItem');
        if (!$pluginInfo) {
            return parent::getLastItem();
        } else {
            return $this->___callPlugins('getLastItem', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getItems()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getItems');
        if (!$pluginInfo) {
            return parent::getItems();
        } else {
            return $this->___callPlugins('getItems', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getColumnValues($colName)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getColumnValues');
        if (!$pluginInfo) {
            return parent::getColumnValues($colName);
        } else {
            return $this->___callPlugins('getColumnValues', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getItemsByColumnValue($column, $value)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getItemsByColumnValue');
        if (!$pluginInfo) {
            return parent::getItemsByColumnValue($column, $value);
        } else {
            return $this->___callPlugins('getItemsByColumnValue', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getItemByColumnValue($column, $value)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getItemByColumnValue');
        if (!$pluginInfo) {
            return parent::getItemByColumnValue($column, $value);
        } else {
            return $this->___callPlugins('getItemByColumnValue', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function walk($callback, array $args = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'walk');
        if (!$pluginInfo) {
            return parent::walk($callback, $args);
        } else {
            return $this->___callPlugins('walk', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function each($objMethod, $args = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'each');
        if (!$pluginInfo) {
            return parent::each($objMethod, $args);
        } else {
            return $this->___callPlugins('each', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDataToAll($key, $value = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setDataToAll');
        if (!$pluginInfo) {
            return parent::setDataToAll($key, $value);
        } else {
            return $this->___callPlugins('setDataToAll', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCurPage($page)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCurPage');
        if (!$pluginInfo) {
            return parent::setCurPage($page);
        } else {
            return $this->___callPlugins('setCurPage', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setPageSize($size)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setPageSize');
        if (!$pluginInfo) {
            return parent::setPageSize($size);
        } else {
            return $this->___callPlugins('setPageSize', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setItemObjectClass($className)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setItemObjectClass');
        if (!$pluginInfo) {
            return parent::setItemObjectClass($className);
        } else {
            return $this->___callPlugins('setItemObjectClass', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getNewEmptyItem()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getNewEmptyItem');
        if (!$pluginInfo) {
            return parent::getNewEmptyItem();
        } else {
            return $this->___callPlugins('getNewEmptyItem', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toXml()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'toXml');
        if (!$pluginInfo) {
            return parent::toXml();
        } else {
            return $this->___callPlugins('toXml', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'toOptionArray');
        if (!$pluginInfo) {
            return parent::toOptionArray();
        } else {
            return $this->___callPlugins('toOptionArray', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionHash()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'toOptionHash');
        if (!$pluginInfo) {
            return parent::toOptionHash();
        } else {
            return $this->___callPlugins('toOptionHash', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getItemById($idValue)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getItemById');
        if (!$pluginInfo) {
            return parent::getItemById($idValue);
        } else {
            return $this->___callPlugins('getItemById', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getIterator');
        if (!$pluginInfo) {
            return parent::getIterator();
        } else {
            return $this->___callPlugins('getIterator', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'count');
        if (!$pluginInfo) {
            return parent::count();
        } else {
            return $this->___callPlugins('count', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFlag($flag)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getFlag');
        if (!$pluginInfo) {
            return parent::getFlag($flag);
        } else {
            return $this->___callPlugins('getFlag', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setFlag($flag, $value = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setFlag');
        if (!$pluginInfo) {
            return parent::setFlag($flag, $value);
        } else {
            return $this->___callPlugins('setFlag', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasFlag($flag)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'hasFlag');
        if (!$pluginInfo) {
            return parent::hasFlag($flag);
        } else {
            return $this->___callPlugins('hasFlag', func_get_args(), $pluginInfo);
        }
    }
}
