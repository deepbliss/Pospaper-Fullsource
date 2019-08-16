<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Block\Adminhtml\System\Config\Field;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Backend\Block\Template\Context;
use Amasty\Geoip\Model\Import;
use Amasty\Geoip\Helper\Data as GeoipHelper;

class DisableLogging extends Field
{
    const GEOIP_SECTION = 'amgeoip';

    /**
     * @var GeoipHelper
     */
    private $geoipHelper;

    /**
     * @var Import
     */
    private $import;

    public function __construct(
        Context $context,
        Import $import,
        GeoipHelper $geoipHelper,
        array $data = []
    ) {
        $this->import = $import;
        $this->geoipHelper = $geoipHelper;
        parent::__construct($context, $data);
    }

    /**
     * @param AbstractElement $element
     *
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        if ($this->geoipHelper->isDone() && $this->import->importTableHasData()) {
            $element->setData('comment', '');
        } else {
            $element->setData('disabled', true);
            $url = $this->getUrl('*/*/*', ['_current' => true, 'section' => self::GEOIP_SECTION]);
            $comment = $element->getData('comment') .
                '</br>' . '<a href=' . $url . '>' . __('import GeoIP Data') . '</a>';
            $element->setData('comment', $comment);
        }

        $html = $element->getElementHtml();

        return $html;
    }
}
