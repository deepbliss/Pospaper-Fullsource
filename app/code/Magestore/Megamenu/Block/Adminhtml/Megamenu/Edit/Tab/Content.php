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

use Magento\Backend\Block\Widget\Tab\TabInterface;

/**
 * Class Tab Content
 */
class Content extends \Magento\Backend\Block\Widget\Form\Generic implements TabInterface
{
    /**
     * @var \Magento\Framework\Session\SessionManagerInterface
     */
    protected $_adminSession;
    /**
     * @var string
     */
    protected $_template = 'Magestore_Megamenu::content.phtml';

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected  $_storeManager;

    /**
     * Content constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param \Magento\Backend\Model\Auth\Session $adminSession
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Backend\Model\Auth\Session $adminSession,
        array $data = array()
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
        $this->_storeManager= $context->getStoreManager();
        $this->_systemStore =$systemStore;
        $this->_adminSession= $adminSession;
    }


    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('Content information');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Content information');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Preparing global layout
     *
     * You can redefine this method in child classes for changing layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        /**
         * \Magestore\Meagamenu\Block\Adminhtml\Megamenu\Edit\Tab\Content\Headerfooter
         */
        $headerfooter= $this->getLayout()->createBlock('Magestore\Megamenu\Block\Adminhtml\Megamenu\Edit\Tab\Content\Headerfooter');
        $this->setChild('header_and_footer', $headerfooter);
        /**
         * \Magestore\Meagamenu\Block\Adminhtml\Megamenu\Edit\Tab\Content\Maincontent
         */
        $maincontent= $this->getLayout()->createBlock('Magestore\Megamenu\Block\Adminhtml\Megamenu\Edit\Tab\Content\Maincontent');
        $maincontent->setData(
           [
                'label' => __('Main Content'),
                'onclick' => 'templateControl.load();',
                'type' => 'button',
                'class' => 'save'
            ]
        );
        $this->setChild('main_content', $maincontent);
        /**
         * \Magestore\Meagamenu\Block\Adminhtml\Megamenu\Edit\Tab\Content\Maincontent
         */
        $featureditem= $this->getLayout()->createBlock('Magestore\Megamenu\Block\Adminhtml\Megamenu\Edit\Tab\Content\Featureditem');
        $featureditem->setData(
           [
                'label' => __('Featured Item'),
                'onclick' => 'templateControl.load();',
                'type' => 'button',
                'class' => 'save'
            ]
        );
        $this->setChild('featured_item', $featureditem);




        return parent::_prepareLayout();
    }
    public function getLoadButtonHtml() {
        return $this->getChildHtml('load_button');
    }

    public function getLoadUrl() {
        return $this->getUrl('*/*/gettemplate');
    }

    public function getSaveUrl() {
        return $this->getUrl('*/*/save');
    }

    public function getFormData() {
        if ($this->_adminSession->getMegamenuData()) {
            $data = $this->_adminSession->getMegamenuData();
            $this->_adminSession->setMegamenuData(null);
        } elseif ($this->_coreRegistry->registry('megamenu_data'))
            $data = $this->_coreRegistry->registry('megamenu_data')->getData();
        if(isset($data) && $data){
            return $data;
        }
        return null;
    }


}
