<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Block;

use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;

/**
 * Search block
 */
class Search extends \Magento\Backend\Block\Template implements RendererInterface
{
    protected $_template = 'Amasty_Base::search.phtml';

    protected function _construct()
    {
        parent::_construct();
        $this->setData('cache_lifetime', 86400);
    }

    /**
     * Render Search html
     *
     * @param AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        return $this->toHtml();
    }
}
