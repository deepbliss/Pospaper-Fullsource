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
namespace Magestore\Megamenu\Block\Adminhtml\Megamenu\Edit\Tab;

class AbstractBlock extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;
    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;
    /**
     * @var \Magestore\Megamenu\Model\System\Config\Megamenutype
     */
    protected $_megaModel;
    /**
     * @var \Magento\Backend\Model\Auth\Session
     */
    protected $_adminSession;

    /**
     * AbstractBlock constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Backend\Model\Auth\Session $adminSession
     * @param \Magestore\Megamenu\Model\Megamenu $megaModel
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Backend\Model\Auth\Session $adminSession,
        \Magestore\Megamenu\Model\Megamenu $megaModel,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
        $this->_adminSession= $adminSession;
        $this->_megaModel =$megaModel;
        $this->_wysiwygConfig =$wysiwygConfig;
        $this->_objectManager =$objectManager;
        $this->_storeManager= $context->getStoreManager();
    }
    /**
     * get registry model.
     *
     * @return \Magento\Framework\Model\AbstractModel|null
     */
    public function getRegistryModel()
    {
        return $this->_coreRegistry->registry('megamenu_model');
    }

    public function getCatalogCategoryColection(){
        return $this->_objectManager->create('Magento\Catalog\Model\ResourceModel\Category\Collection');
    }

    public function getRuleTrigerImage(){
        return   $this->_storeManager->getStore()->getBaseUrl().'pub/static/adminhtml/Magento/backend/en_US/images/rule_chooser_trigger.gif';
    }
}