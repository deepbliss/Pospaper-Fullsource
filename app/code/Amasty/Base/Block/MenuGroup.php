<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Block;

use Magento\Config\Block\System\Config\Form\Fieldset;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class MenuGroup
 * @package Amasty\Base\Block
 */
class MenuGroup extends Fieldset
{
    /**
     * @var ProductMetadataInterface
     */
    private $metadata;

    public function __construct(
        ProductMetadataInterface $metadata,
        \Magento\Backend\Block\Context $context,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Framework\View\Helper\Js $jsHelper,
        array $data = []
    ) {
        parent::__construct($context, $authSession, $jsHelper, $data);
        $this->metadata = $metadata;
    }

    public function render(AbstractElement $element)
    {
        if (version_compare($this->metadata->getVersion(), '2.2.0', '>=')) {
            return parent::render($element);
        } else {
            return;
        }
    }
}
