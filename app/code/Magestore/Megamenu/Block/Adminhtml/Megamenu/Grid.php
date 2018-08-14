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
namespace Magestore\Megamenu\Block\Adminhtml\Megamenu;

/**
 * Grid Grid
 */
class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magestore\Megamenu\Helper\Data
     */
    protected $_megaHelper;
    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager = null;

    /**
     * Grid constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magestore\Megamenu\Helper\Data $megaHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magestore\Megamenu\Helper\Data $megaHelper,
        array $data = array())
    {
        parent::__construct($context, $backendHelper, $data);
        $this->_objectManager = $objectManager;
        $this->_megaHelper= $megaHelper;
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('Grid');
        $this->setDefaultSort('megamenu_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareCollection()
    {
        $collection = $this->_objectManager->create('Magestore\Megamenu\Model\ResourceModel\Megamenu\Collection');
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareColumns()
    {
        $this->addColumn('megamenu_id',
            [
                'header'	=> __('ID'),
                'align'	 =>'right',
                'width'	 => '50px',
                'index'	 => 'megamenu_id',
            ]
        );

        $this->addColumn('name_menu',
            [
            'header'	=>__('Name'),
            'align'	 =>'left',
            'index'	 => 'name_menu',
             ]
        );
        $this->addColumn('megamenu_type',
            [
            'header'    =>  __('Menu Type'),
            'align'     =>  'left',
            'index'     =>  'megamenu_type',
            'type'      =>  'options',
            'options'   =>  $this->_megaHelper->megamenuTypeToOptionArray(),
        ]
    );
        $this->addColumn('menu_type',
            [
            'header'    =>  __('SubMenu Type'),
            'align'     =>  'left',
            'index'     =>  'menu_type',
            'type'      =>  'options',
            'options'   =>  $this->_megaHelper->menuTypeToOptionArray(),
        ]
    );
        $this->addColumn('link',
            [
            'header'	=> __('Link'),
            'align'	 =>'left',
            'index'	 => 'link',
        ]
    );

//        if (!$this->_storeManager->isSingleStoreMode()) {
//            $this->addColumn('stores',
//                [
//                'header'        => __('Store View'),
//                'index'         => 'stores',
//                'type'          => 'store',
//                'store_all'     => true,
//                'store_view'    => true,
//                'sortable'      => false,
//                'filter_condition_callback'
//                => array($this, '_filterStoreCondition'),
//            ]
//        );
//        }
        $this->addColumn('status',
            [
            'header'	=> __('Status'),
            'align'	 => 'left',
            'width'	 => '80px',
            'index'	 => 'status',
            'type'		=> 'options',
            'options'	 => [
                1 => 'Enabled',
                2 => 'Disabled',
            ],
        ]
    );
        $this->addColumn('sort_order',
            [
            'header'	=> __('Sort Order'),
            'width'	 => '50px',
            'index'	 => 'sort_order',
        ]
    );

        $this->addColumn('action',
            [
                'header'	=>	__('Action'),
                'width'		=> '100',
                'type'		=> 'action',
                'getter'	=> 'getId',
                'actions'	=> array(
                    array(
                        'caption'	=> __('Edit'),
                        'url'		=> array('base'=> '*/*/edit'),
                        'field'		=> 'megamenu_id'
                    )),
                'filter'	=> false,
                'sortable'	=> false,
                'index'		=> 'stores',
                'is_system'	=> true,
            ]
    );

        $this->addExportType('*/*/exportCsv', __('CSV'));
        $this->addExportType('*/*/exportXml', __('XML'));
        $this->addExportType('*/*/exportExcel', __('Excel'));

        return parent::_prepareColumns();
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('megamenu_id');
        $this->getMassactionBlock()->setFormFieldName('megamenus');

        $this->getMassactionBlock()->addItem(
            'delete',
            [
                'label'   => __('Delete'),
                'url'     => $this->getUrl('megamenuadmin/*/massDelete'),
                'confirm' => __('Are you sure?'),
            ]
        );

        $statuses = \Magestore\Megamenu\Model\Status::getAvailableStatuses();

        array_unshift($statuses, ['label' => '', 'value' => '']);
        $this->getMassactionBlock()->addItem(
            'status',
            [
                'label'      => __('Change status'),
                'url'        => $this->getUrl('megamenuadmin/*/massStatus', ['_current' => TRUE]),
                'additional' => [
                    'visibility' => [
                        'name'   => 'status',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => __('Status'),
                        'values' => $statuses,
                    ],
                ],
            ]
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', ['_current' => TRUE]);
    }

    /**
     * {@inheritdoc}
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', ['megamenu_id' => $row->getId()]);
    }
}
