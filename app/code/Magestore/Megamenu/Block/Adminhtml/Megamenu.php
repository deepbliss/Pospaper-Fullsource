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
namespace Magestore\Megamenu\Block\Adminhtml;

/**
 * Grid Container Megamenu
 */
class Megamenu extends \Magento\Backend\Block\Widget\Grid\Container
{
    const STATUS_ENABLED = 1;


    /**
     * @var \Magestore\Megamenu\Helper\Data
     */
    protected $_dataHelper;
    /**
     * Megamenu constructor.
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magestore\Megamenu\Helper\Data $dataHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magestore\Megamenu\Helper\Data $dataHelper,
        array $data = []
    )
    {
        $this->_dataHelper = $dataHelper;
        parent::__construct($context, $data);
    }

    /**
     * get mega menu cache
     * @return mixed
     */
    public function getCache()
    {
        return $this->_dataHelper->getConfig('megamenu/general/cache',$store=null);
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_megamenu';
        $this->_blockGroup = 'Magestore_Megamenu';
        $this->_headerText = __('Megamenu grid');
        $this->_addButtonLabel = __('Add New Megamenu');
        $cache = $this->getCache();
        if($cache == self::STATUS_ENABLED){
            $label = 'Disable Cache';
            $text = '<h3 style="margin:0;">Mega Menu Cache Status: <span style="background:#3CB861;padding: 3px 15px;color: #fff;font-size: 11px;border-radius: 7px;">ENABLED</span></h3>';
        }else{
            $label = 'Enable Cache';
            $text = '<h3 style="margin:0;">Mega Menu Cache Status: <span style="background:#E41101;padding: 3px 15px;color: #fff;font-size: 11px;border-radius: 7px;">DISABLED</span></h3>';
        }
        /**
         * add disable button
         */

        $this->addButton(
            $label,
            [
                'label' => $label,
                'onclick'   => 'setLocation(\'' . $this->getUrl('megamenuadmin/megamenu/changeCache',['status'=>$cache]) .'\')',
                'class' => 'action-default scalable add primary'
            ],$level = 0, $sortOrder = 1
        );
        /**
         * add refresh button
         */
        $this->addButton(
            'Refresh Menu Cache',
            [
                'label' => 'Refresh Menu Cache',
                'onclick' => 'setLocation(\'' . $this->getUrl('megamenuadmin/megamenu/rebuildAll') . '\')',
                'class' => 'action-default scalable add primary'
            ],$level = 0, $sortOrder = 2
        );
        $this->addButton(
            'Status',
            [
                'label' => $text,
                'title' => 'Status',
                'onclick'   => '',
                'class' => ''
            ],$level = 0, $sortOrder = 0
        );

        parent::_construct();
    }
    protected function _addNewButton()
    {
        $this->addButton(
            'add',
            [
                'label' => $this->getAddButtonLabel(),
                'onclick' => 'setLocation(\'' . $this->getCreateUrl() . '\')',
                'class' => 'add primary'
            ],$level = 0, $sortOrder = 3
        );
    }


}
