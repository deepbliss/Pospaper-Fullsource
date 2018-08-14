<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Pos\Attributesorting\Block\Adminhtml\Edit\Banner;
/**
 * Class AbstractCategory
 */
class Attributeposition extends \Magento\Backend\Block\Template
{	
	/**
     * Block template.
     *
     * @var string
     */
    protected $_template = 'attributeposition.phtml';
 
    /**
     * AssignProducts constructor.
     *
     * @param \Magento\Backend\Block\Template\Context  $context
     * @param array                                    $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

}