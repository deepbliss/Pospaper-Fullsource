<?php
namespace Magento\Config\Model\Config\Structure\Element\Group;

/**
 * Interceptor class for @see \Magento\Config\Model\Config\Structure\Element\Group
 */
class Interceptor extends \Magento\Config\Model\Config\Structure\Element\Group implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\Module\Manager $moduleManager, \Magento\Config\Model\Config\Structure\Element\Iterator\Field $childrenIterator, \Magento\Config\Model\Config\BackendClone\Factory $cloneModelFactory, \Magento\Config\Model\Config\Structure\Element\Dependency\Mapper $dependencyMapper)
    {
        $this->___init();
        parent::__construct($storeManager, $moduleManager, $childrenIterator, $cloneModelFactory, $dependencyMapper);
    }

    /**
     * {@inheritdoc}
     */
    public function shouldCloneFields()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'shouldCloneFields');
        if (!$pluginInfo) {
            return parent::shouldCloneFields();
        } else {
            return $this->___callPlugins('shouldCloneFields', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCloneModel()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCloneModel');
        if (!$pluginInfo) {
            return parent::getCloneModel();
        } else {
            return $this->___callPlugins('getCloneModel', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function populateFieldset(\Magento\Framework\Data\Form\Element\Fieldset $fieldset)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'populateFieldset');
        if (!$pluginInfo) {
            return parent::populateFieldset($fieldset);
        } else {
            return $this->___callPlugins('populateFieldset', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isExpanded()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isExpanded');
        if (!$pluginInfo) {
            return parent::isExpanded();
        } else {
            return $this->___callPlugins('isExpanded', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldsetCss()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getFieldsetCss');
        if (!$pluginInfo) {
            return parent::getFieldsetCss();
        } else {
            return $this->___callPlugins('getFieldsetCss', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies($storeCode)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDependencies');
        if (!$pluginInfo) {
            return parent::getDependencies($storeCode);
        } else {
            return $this->___callPlugins('getDependencies', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setData(array $data, $scope)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setData');
        if (!$pluginInfo) {
            return parent::setData($data, $scope);
        } else {
            return $this->___callPlugins('setData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasChildren()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'hasChildren');
        if (!$pluginInfo) {
            return parent::hasChildren();
        } else {
            return $this->___callPlugins('hasChildren', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getChildren()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getChildren');
        if (!$pluginInfo) {
            return parent::getChildren();
        } else {
            return $this->___callPlugins('getChildren', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isVisible()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isVisible');
        if (!$pluginInfo) {
            return parent::isVisible();
        } else {
            return $this->___callPlugins('isVisible', func_get_args(), $pluginInfo);
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
    public function getId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getId');
        if (!$pluginInfo) {
            return parent::getId();
        } else {
            return $this->___callPlugins('getId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getLabel');
        if (!$pluginInfo) {
            return parent::getLabel();
        } else {
            return $this->___callPlugins('getLabel', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getComment()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getComment');
        if (!$pluginInfo) {
            return parent::getComment();
        } else {
            return $this->___callPlugins('getComment', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFrontendModel()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getFrontendModel');
        if (!$pluginInfo) {
            return parent::getFrontendModel();
        } else {
            return $this->___callPlugins('getFrontendModel', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAttribute($key)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAttribute');
        if (!$pluginInfo) {
            return parent::getAttribute($key);
        } else {
            return $this->___callPlugins('getAttribute', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getClass()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getClass');
        if (!$pluginInfo) {
            return parent::getClass();
        } else {
            return $this->___callPlugins('getClass', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPath($fieldPrefix = '')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getPath');
        if (!$pluginInfo) {
            return parent::getPath($fieldPrefix);
        } else {
            return $this->___callPlugins('getPath', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getElementVisibility()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getElementVisibility');
        if (!$pluginInfo) {
            return parent::getElementVisibility();
        } else {
            return $this->___callPlugins('getElementVisibility', func_get_args(), $pluginInfo);
        }
    }
}
