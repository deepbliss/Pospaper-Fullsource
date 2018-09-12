<?php
namespace Magento\Customer\Model\Customer\DataProvider;

/**
 * Interceptor class for @see \Magento\Customer\Model\Customer\DataProvider
 */
class Interceptor extends \Magento\Customer\Model\Customer\DataProvider implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct($name, $primaryFieldName, $requestFieldName, \Magento\Ui\DataProvider\EavValidationRules $eavValidationRules, \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory, \Magento\Eav\Model\Config $eavConfig, \Magento\Framework\View\Element\UiComponent\DataProvider\FilterPool $filterPool, \Magento\Customer\Model\FileProcessorFactory $fileProcessorFactory = null, array $meta = array(), array $data = array(), \Magento\Framework\View\Element\UiComponent\ContextInterface $context = null, $allowToShowHiddenAttributes = true)
    {
        $this->___init();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $eavValidationRules, $customerCollectionFactory, $eavConfig, $filterPool, $fileProcessorFactory, $meta, $data, $context, $allowToShowHiddenAttributes);
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
    public function getCollection()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCollection');
        if (!$pluginInfo) {
            return parent::getCollection();
        } else {
            return $this->___callPlugins('getCollection', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getName');
        if (!$pluginInfo) {
            return parent::getName();
        } else {
            return $this->___callPlugins('getName', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPrimaryFieldName()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getPrimaryFieldName');
        if (!$pluginInfo) {
            return parent::getPrimaryFieldName();
        } else {
            return $this->___callPlugins('getPrimaryFieldName', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestFieldName()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getRequestFieldName');
        if (!$pluginInfo) {
            return parent::getRequestFieldName();
        } else {
            return $this->___callPlugins('getRequestFieldName', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getMeta');
        if (!$pluginInfo) {
            return parent::getMeta();
        } else {
            return $this->___callPlugins('getMeta', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldSetMetaInfo($fieldSetName)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getFieldSetMetaInfo');
        if (!$pluginInfo) {
            return parent::getFieldSetMetaInfo($fieldSetName);
        } else {
            return $this->___callPlugins('getFieldSetMetaInfo', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldsMetaInfo($fieldSetName)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getFieldsMetaInfo');
        if (!$pluginInfo) {
            return parent::getFieldsMetaInfo($fieldSetName);
        } else {
            return $this->___callPlugins('getFieldsMetaInfo', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldMetaInfo($fieldSetName, $fieldName)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getFieldMetaInfo');
        if (!$pluginInfo) {
            return parent::getFieldMetaInfo($fieldSetName, $fieldName);
        } else {
            return $this->___callPlugins('getFieldMetaInfo', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addFilter');
        if (!$pluginInfo) {
            return parent::addFilter($filter);
        } else {
            return $this->___callPlugins('addFilter', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSearchCriteria()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getSearchCriteria');
        if (!$pluginInfo) {
            return parent::getSearchCriteria();
        } else {
            return $this->___callPlugins('getSearchCriteria', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSearchResult()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getSearchResult');
        if (!$pluginInfo) {
            return parent::getSearchResult();
        } else {
            return $this->___callPlugins('getSearchResult', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addField($field, $alias = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addField');
        if (!$pluginInfo) {
            return parent::addField($field, $alias);
        } else {
            return $this->___callPlugins('addField', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addOrder($field, $direction)
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
    public function setLimit($offset, $size)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setLimit');
        if (!$pluginInfo) {
            return parent::setLimit($offset, $size);
        } else {
            return $this->___callPlugins('setLimit', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeField($field, $isAlias = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'removeField');
        if (!$pluginInfo) {
            return parent::removeField($field, $isAlias);
        } else {
            return $this->___callPlugins('removeField', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeAllFields()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'removeAllFields');
        if (!$pluginInfo) {
            return parent::removeAllFields();
        } else {
            return $this->___callPlugins('removeAllFields', func_get_args(), $pluginInfo);
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
    public function getConfigData()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getConfigData');
        if (!$pluginInfo) {
            return parent::getConfigData();
        } else {
            return $this->___callPlugins('getConfigData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setConfigData($config)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setConfigData');
        if (!$pluginInfo) {
            return parent::setConfigData($config);
        } else {
            return $this->___callPlugins('setConfigData', func_get_args(), $pluginInfo);
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
}
