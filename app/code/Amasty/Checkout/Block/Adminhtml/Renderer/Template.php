<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Block\Adminhtml\Renderer;

use Magento\Framework\Data\Form\Element\AbstractElement;

class Template extends \Magento\Backend\Block\Template implements
    \Magento\Framework\Data\Form\Element\Renderer\RendererInterface
{

    /**
     * @var \Amasty\Checkout\Helper\Onepage
     */
    protected $helper;

    /**
     * Template constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Amasty\Checkout\Helper\Onepage $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Amasty\Checkout\Helper\Onepage $helper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helper = $helper;
    }

    /**
     * @var AbstractElement
     */
    protected $_element;

    /**
     * @return AbstractElement
     */
    public function getElement()
    {
        return $this->_element;
    }

    /**
     * @param AbstractElement $element
     *
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $this->_element = $element;
        return $this->toHtml();
    }

    public function isStoreSelected()
    {
        return $this->_request->getParam('store', false) !== false;
    }

    /**
     * @param null $moduleName
     * @return bool
     */
    public function isModuleExist($moduleName = null)
    {
        return $this->helper->isModuleOutputEnabled($moduleName);
    }
}
