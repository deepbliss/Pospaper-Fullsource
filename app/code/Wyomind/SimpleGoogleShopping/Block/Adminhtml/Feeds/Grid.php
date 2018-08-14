<?php

/**
 * Copyright © 2015 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Block\Adminhtml\Feeds;

/**
 * Grid definition
 */
class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{

    protected $_collectionFactory;
    protected $_coreHelper;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Wyomind\SimpleGoogleShopping\Model\ResourceModel\Feeds\CollectionFactory $collectionFactory
     * @param \Wyomind\Core\Helper\Data $coreHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Wyomind\SimpleGoogleShopping\Model\ResourceModel\Feeds\CollectionFactory $collectionFactory,
        \Wyomind\Core\Helper\Data $coreHelper,
        array $data = []
    )
    {

        $this->_collectionFactory = $collectionFactory;
        $this->_coreHelper = $coreHelper;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * initializer
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('simplegoogleshoppingGrid');
        $this->setDefaultSort('simplegoogleshopping_id');
        $this->setDefaultDir('ASC');
    }

    /**
     * Prepare collection
     * @return \Magento\Backend\Block\Widget\Grid
     */
    protected function _prepareCollection()
    {
        $collection = $this->_collectionFactory->create();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare columns
     * @return \Magento\Backend\Block\Widget\Grid\Extended
     */
    public function _prepareColumns()
    {
        $this->addColumn('simplegoogleshopping_id', ['header' => __('Id'), 'width' => '50px', 'index' => 'simplegoogleshopping_id']);
        $this->addColumn('simplegoogleshopping_filename', ['header' => __('Filename'), 'index' => 'simplegoogleshopping_filename']);
        $this->addColumn('simplegoogleshopping_path', ['header' => __('Path'), 'index' => 'simplegoogleshopping_path']);

        $this->addColumn(
            'link',
            [
                'header' => __('Link'),
                'align' => 'left',
                'index' => 'link',
                "filter" => false,
                "sortable" => false,
                'renderer' => 'Wyomind\SimpleGoogleShopping\Block\Adminhtml\Feeds\Renderer\Link'
            ]
        );

        $this->addColumn(
            'simplegoogleshopping_status',
            [
                'header' => __('Status'),
                'align' => 'left',
                'renderer' => 'Wyomind\SimpleGoogleShopping\Block\Adminhtml\Feeds\Renderer\Status',
                "filter" => false,
                "sortable" => false,
            ]
        );

        $this->addColumn(
            'simplegoogleshopping_time',
            [
                'header' => __('Update'),
                'index' => 'simplegoogleshopping_time',
                'type' => 'datetime'
            ]
        );
        $this->addColumn(
            'store_id',
            [
                'header' => __('Store'),
                'index' => 'store_id',
                'type' => 'store',
            ]
        );


        $this->addColumn(
            'action',
            [
                'header' => __('Action'),
                'type' => 'action',
                'getter' => 'getId',
                'filter' => false,
                'sortable' => false,
                'index' => 'id',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action',
                'renderer' => 'Wyomind\SimpleGoogleShopping\Block\Adminhtml\Feeds\Renderer\Action',
            ]
        );

        return parent::_prepareColumns();
    }

    /**
     * Row click url
     * @param  \Magento\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return "";
    }
}
