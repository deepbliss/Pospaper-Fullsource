<?php
namespace Magento\SalesRule\Model\ResourceModel\Rule\Collection;

/**
 * Interceptor class for @see \Magento\SalesRule\Model\ResourceModel\Rule\Collection
 */
class Interceptor extends \Magento\SalesRule\Model\ResourceModel\Rule\Collection implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Data\Collection\EntityFactory $entityFactory, \Psr\Log\LoggerInterface $logger, \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy, \Magento\Framework\Event\ManagerInterface $eventManager, \Magento\Framework\Stdlib\DateTime\TimezoneInterface $date, \Magento\Framework\DB\Adapter\AdapterInterface $connection = null, \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null, \Magento\Framework\Serialize\Serializer\Json $serializer = null)
    {
        $this->___init();
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $date, $connection, $resource, $serializer);
    }

    /**
     * {@inheritdoc}
     */
    public function setValidationFilter($websiteId, $customerGroupId, $couponCode = '', $now = null, \Magento\Quote\Model\Quote\Address $address = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setValidationFilter');
        if (!$pluginInfo) {
            return parent::setValidationFilter($websiteId, $customerGroupId, $couponCode, $now, $address);
        } else {
            return $this->___callPlugins('setValidationFilter', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addWebsiteGroupDateFilter($websiteId, $customerGroupId, $now = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addWebsiteGroupDateFilter');
        if (!$pluginInfo) {
            return parent::addWebsiteGroupDateFilter($websiteId, $customerGroupId, $now);
        } else {
            return $this->___callPlugins('addWebsiteGroupDateFilter', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function _initSelect()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, '_initSelect');
        if (!$pluginInfo) {
            return parent::_initSelect();
        } else {
            return $this->___callPlugins('_initSelect', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addAttributeInConditionFilter($attributeCode)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addAttributeInConditionFilter');
        if (!$pluginInfo) {
            return parent::addAttributeInConditionFilter($attributeCode);
        } else {
            return $this->___callPlugins('addAttributeInConditionFilter', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addAllowedSalesRulesFilter()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addAllowedSalesRulesFilter');
        if (!$pluginInfo) {
            return parent::addAllowedSalesRulesFilter();
        } else {
            return $this->___callPlugins('addAllowedSalesRulesFilter', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addCustomerGroupFilter($customerGroupId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addCustomerGroupFilter');
        if (!$pluginInfo) {
            return parent::addCustomerGroupFilter($customerGroupId);
        } else {
            return $this->___callPlugins('addCustomerGroupFilter', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addWebsitesToResult($flag = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addWebsitesToResult');
        if (!$pluginInfo) {
            return parent::addWebsitesToResult($flag);
        } else {
            return $this->___callPlugins('addWebsitesToResult', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addWebsiteFilter($websiteId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addWebsiteFilter');
        if (!$pluginInfo) {
            return parent::addWebsiteFilter($websiteId);
        } else {
            return $this->___callPlugins('addWebsiteFilter', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addFieldToFilter($field, $condition = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addFieldToFilter');
        if (!$pluginInfo) {
            return parent::addFieldToFilter($field, $condition);
        } else {
            return $this->___callPlugins('addFieldToFilter', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addIsActiveFilter($isActive = 1)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addIsActiveFilter');
        if (!$pluginInfo) {
            return parent::addIsActiveFilter($isActive);
        } else {
            return $this->___callPlugins('addIsActiveFilter', func_get_args(), $pluginInfo);
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
    public function setMainTable($table)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setMainTable');
        if (!$pluginInfo) {
            return parent::setMainTable($table);
        } else {
            return $this->___callPlugins('setMainTable', func_get_args(), $pluginInfo);
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
    public function addExpressionFieldToSelect($alias, $expression, $fields)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addExpressionFieldToSelect');
        if (!$pluginInfo) {
            return parent::addExpressionFieldToSelect($alias, $expression, $fields);
        } else {
            return $this->___callPlugins('addExpressionFieldToSelect', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeFieldFromSelect($field, $isAlias = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'removeFieldFromSelect');
        if (!$pluginInfo) {
            return parent::removeFieldFromSelect($field, $isAlias);
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
    public function setModel($model)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setModel');
        if (!$pluginInfo) {
            return parent::setModel($model);
        } else {
            return $this->___callPlugins('setModel', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getModelName()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getModelName');
        if (!$pluginInfo) {
            return parent::getModelName();
        } else {
            return $this->___callPlugins('getModelName', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setResourceModel($model)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setResourceModel');
        if (!$pluginInfo) {
            return parent::setResourceModel($model);
        } else {
            return $this->___callPlugins('setResourceModel', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getResourceModelName()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getResourceModelName');
        if (!$pluginInfo) {
            return parent::getResourceModelName();
        } else {
            return $this->___callPlugins('getResourceModelName', func_get_args(), $pluginInfo);
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
    public function getAllIds()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAllIds');
        if (!$pluginInfo) {
            return parent::getAllIds();
        } else {
            return $this->___callPlugins('getAllIds', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function join($table, $cond, $cols = '*')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'join');
        if (!$pluginInfo) {
            return parent::join($table, $cond, $cols);
        } else {
            return $this->___callPlugins('join', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setResetItemsDataChanged($flag)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setResetItemsDataChanged');
        if (!$pluginInfo) {
            return parent::setResetItemsDataChanged($flag);
        } else {
            return $this->___callPlugins('setResetItemsDataChanged', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function resetItemsDataChanged()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'resetItemsDataChanged');
        if (!$pluginInfo) {
            return parent::resetItemsDataChanged();
        } else {
            return $this->___callPlugins('resetItemsDataChanged', func_get_args(), $pluginInfo);
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
    public function setOrder($field, $direction = 'DESC')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setOrder');
        if (!$pluginInfo) {
            return parent::setOrder($field, $direction);
        } else {
            return $this->___callPlugins('setOrder', func_get_args(), $pluginInfo);
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
    public function addItem(\Magento\Framework\DataObject $item)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addItem');
        if (!$pluginInfo) {
            return parent::addItem($item);
        } else {
            return $this->___callPlugins('addItem', func_get_args(), $pluginInfo);
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
    public function toArray($arrRequiredFields = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'toArray');
        if (!$pluginInfo) {
            return parent::toArray($arrRequiredFields);
        } else {
            return $this->___callPlugins('toArray', func_get_args(), $pluginInfo);
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
