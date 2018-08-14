<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */

namespace Amasty\Checkout\Block\Adminhtml\Field\Edit;

use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;

use Magento\Framework\Escaper;

class Group extends \Magento\Framework\Data\Form\Element\Fieldset
{
    protected static $_rowRenderer;
    
    protected static $_groupRenderer;
    
    /**
     * @var RowFactory
     */
    protected $rowFactory;

    public function __construct(
        \Magento\Framework\Data\Form\Element\Factory $factoryElement,
        \Magento\Framework\Data\Form\Element\CollectionFactory $factoryCollection,
        Escaper $escaper,
    
        \Amasty\Checkout\Block\Adminhtml\Field\Edit\Group\RowFactory $rowFactory,
        
        array $data = []
    ) {
        parent::__construct($factoryElement, $factoryCollection, $escaper, $data);
        $this->rowFactory = $rowFactory;
    }

    public function addRow($id, $data, $after = false)
    {
        /** @var \Amasty\Checkout\Block\Adminhtml\Field\Edit\Group\Row $row */
        $row = $this->rowFactory->create(['data' => $data]);
        $row->setId($id);
        $element = $this->addElement($row, $after);

        if ($renderer = self::getRowRenderer()) {
            $row->setRenderer($renderer);
        }

        return $element;
    }
    
    public static function getRowRenderer()
    {
        return self::$_rowRenderer;
    }

    public static function setRowRenderer(RendererInterface $renderer)
    {
        self::$_rowRenderer = $renderer;
    }
    
    public static function getGroupRenderer()
    {
        return self::$_groupRenderer;
    }

    public static function setGroupRenderer(RendererInterface $renderer)
    {
        self::$_groupRenderer = $renderer;
    }
}
