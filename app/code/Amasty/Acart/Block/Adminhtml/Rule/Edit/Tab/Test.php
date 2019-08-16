<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Block\Adminhtml\Rule\Edit\Tab;

use Magento\Backend\Block\Widget\Grid\Column;
use Magento\Backend\Block\Widget\Tab\TabInterface;

class Test extends \Magento\Reports\Block\Adminhtml\Grid\Shopcart implements TabInterface
{
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        array $data = []
    ) {
        parent::__construct($context, $backendHelper);

        $this->setId('amasty_acart_rule_test');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
    }

    public function getTabLabel()
    {
        return __('Test');
    }

    public function getTabTitle()
    {
        return __('Test');
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }

    protected function _prepareCollection()
    {
        $collection = \Magento\Framework\App\ObjectManager::getInstance()
            ->create('Amasty\Acart\Model\ResourceModel\Quote\Collection')
            ->joinQuoteEmail();

        $collection->getSelect()
            ->where('ifnull(main_table.customer_email, quoteEmail.customer_email) is not null');

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _addColumns()
    {
        $this->addColumn(
            'run',
            [
                'header' => '',//Mage::helper('amacart')->__('Run'),
                'index' => 'customer_id',
                'sortable' => false,
                'filter' => false,
                'renderer' => 'Amasty\Acart\Block\Adminhtml\Rule\Edit\Tab\Test\Renderer\Run',
                'align' => 'center',
            ]
        );

        $this->addColumn(
            'target_email',
            [
                'header' => __('Email'),
                'index' => 'target_email',
                'sortable' => false,
                'filter_index' => new \Zend_Db_Expr('ifnull(main_table.customer_email, quoteEmail.customer_email)'),
                'header_css_class' => 'col-name',
                'column_css_class' => 'col-name'

            ]
        );

        $this->addColumn(
            'items_count',
            [
                'header' => __('Products'),
                'index' => 'items_count',
                'sortable' => false,
                'type' => 'number',
                'header_css_class' => 'col-number',
                'column_css_class' => 'col-number'
            ]
        );

        $this->addColumn(
            'items_qty',
            [
                'header' => __('Quantity'),
                'index' => 'items_qty',
                'sortable' => false,
                'type' => 'number',
                'header_css_class' => 'col-qty',
                'column_css_class' => 'col-qty'
            ]
        );

        $currencyCode = $this->getCurrentCurrencyCode();

        $this->addColumn(
            'subtotal',
            [
                'header_css_class' => 'col-subtotal',
                'rate' => $this->getRate($currencyCode),
                'header' => __('Subtotal'),
                'type' => 'currency',
                'sortable' => false,
                'renderer' => 'Magento\Reports\Block\Adminhtml\Grid\Column\Renderer\Currency',
                'currency_code' => $currencyCode,
                'index' => 'subtotal',
                'column_css_class' => 'col-subtotal'
            ]
        );

        $this->addColumn(
            'coupon_code',
            [
                'header_css_class' => 'col-coupon',
                'header' => __('Applied Coupon'),
                'index' => 'coupon_code',
                'sortable' => false,
                'column_css_class' => 'col-coupon'
            ]
        );

        $this->addColumn(
            'created_at',
            [
                'header' => __('Created'),
                'type' => 'datetime',
                'index' => 'created_at',
                'filter_index' => 'main_table.created_at',
                'sortable' => false,
                'header_css_class' => 'col-created',
                'column_css_class' => 'col-created'
            ]
        );

        $this->addColumn(
            'updated_at',
            [
                'header' => __('Updated'),
                'type' => 'datetime',
                'index' => 'updated_at',
                'filter_index' => 'main_table.updated_at',
                'sortable' => false,
                'header_css_class' => 'col-updated',
                'column_css_class' => 'col-updated'
            ]
        );

        $this->addColumn(
            'remote_ip',
            [
                'header' => __('IP Address'),
                'index' => 'remote_ip',
                'sortable' => false,
                'header_css_class' => 'col-ip',
                'column_css_class' => 'col-ip'
            ]
        );
    }

    protected function _prepareColumns()
    {
        $this->_addColumns();

        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', ['_current' => true]);
    }
}
