<?php
namespace Magento\Framework\App\ResourceConnection;

/**
 * Interceptor class for @see \Magento\Framework\App\ResourceConnection
 */
class Interceptor extends \Magento\Framework\App\ResourceConnection implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\ResourceConnection\ConfigInterface $resourceConfig, \Magento\Framework\Model\ResourceModel\Type\Db\ConnectionFactoryInterface $connectionFactory, \Magento\Framework\App\DeploymentConfig $deploymentConfig, $tablePrefix = '')
    {
        $this->___init();
        parent::__construct($resourceConfig, $connectionFactory, $deploymentConfig, $tablePrefix);
    }

    /**
     * {@inheritdoc}
     */
    public function getConnection($resourceName = 'default')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getConnection');
        if (!$pluginInfo) {
            return parent::getConnection($resourceName);
        } else {
            return $this->___callPlugins('getConnection', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function closeConnection($resourceName = 'default')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'closeConnection');
        if (!$pluginInfo) {
            return parent::closeConnection($resourceName);
        } else {
            return $this->___callPlugins('closeConnection', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getConnectionByName($connectionName)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getConnectionByName');
        if (!$pluginInfo) {
            return parent::getConnectionByName($connectionName);
        } else {
            return $this->___callPlugins('getConnectionByName', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTableName($modelEntity, $connectionName = 'default')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getTableName');
        if (!$pluginInfo) {
            return parent::getTableName($modelEntity, $connectionName);
        } else {
            return $this->___callPlugins('getTableName', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTablePlaceholder($tableName)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getTablePlaceholder');
        if (!$pluginInfo) {
            return parent::getTablePlaceholder($tableName);
        } else {
            return $this->___callPlugins('getTablePlaceholder', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTriggerName($tableName, $time, $event)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getTriggerName');
        if (!$pluginInfo) {
            return parent::getTriggerName($tableName, $time, $event);
        } else {
            return $this->___callPlugins('getTriggerName', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setMappedTableName($tableName, $mappedName)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setMappedTableName');
        if (!$pluginInfo) {
            return parent::setMappedTableName($tableName, $mappedName);
        } else {
            return $this->___callPlugins('setMappedTableName', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getMappedTableName($tableName)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getMappedTableName');
        if (!$pluginInfo) {
            return parent::getMappedTableName($tableName);
        } else {
            return $this->___callPlugins('getMappedTableName', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIdxName($tableName, $fields, $indexType = 'index')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getIdxName');
        if (!$pluginInfo) {
            return parent::getIdxName($tableName, $fields, $indexType);
        } else {
            return $this->___callPlugins('getIdxName', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFkName($priTableName, $priColumnName, $refTableName, $refColumnName)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getFkName');
        if (!$pluginInfo) {
            return parent::getFkName($priTableName, $priColumnName, $refTableName, $refColumnName);
        } else {
            return $this->___callPlugins('getFkName', func_get_args(), $pluginInfo);
        }
    }
}
