<?php

namespace Wyomind\SimpleGoogleShopping\Block\Adminhtml\Feeds\Renderer;

class Action extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\Action
{

    protected $_coreHelper = null;

    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Wyomind\Core\Helper\Data $coreHelper,
        array $data = []
    )
    {

        parent::__construct($context, $jsonEncoder, $data);
        $this->_coreHelper = $coreHelper;
    }

    public function render(\Magento\Framework\DataObject $row)
    {

        $actions = [
            [// Edit
                'caption' => __('Edit'),
                'url' => [
                    'base' => '*/*/edit'
                ],
                'field' => 'id'
            ],
            [// Generate
                'caption' => __('Generate'),
                'url' => "javascript:void(require(['sgs_index'], function (index) { index.generate('" . $this->getUrl('simplegoogleshopping/feeds/generate', ['id' => $row->getId()]) . "'); }))"
            ],
            [// Preview
                'caption' => __('Preview (%1 items)', $this->_coreHelper->getStoreConfig("simplegoogleshopping/system/preview")),
                'url' => [
                    'base' => '*/*/preview'
                ],
                'field' => 'simplegoogleshopping_id',
                'popup' => true
            ],
            [// Report
                'caption' => __('Show Report'),
                'url' => [
                    'base' => '*/*/showreport'
                ],
                'field' => 'simplegoogleshopping_id',
                'popup' => true
            ],
            [// Delete
                'caption' => __('Delete'),
                'url' => "javascript:void(require(['sgs_index'], function (index) { index.delete('" . $this->getUrl('simplegoogleshopping/feeds/delete', ['id' => $row->getId()]) . "'); }))"
            ]
        ];

        $this->getColumn()->setActions($actions);
        return parent::render($row);
    }
}
