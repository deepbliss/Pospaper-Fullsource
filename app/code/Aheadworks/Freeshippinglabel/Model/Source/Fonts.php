<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Freeshippinglabel\Model\Source;

/**
 * Class Fonts
 *
 * @package Aheadworks\Freeshippinglabel\Model\Source
 */
class Fonts implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * Hardcoded top 12 google fonts from fonts.google.com
     *
     * @return array
     */
    private function getFonts()
    {
        return [
            'Roboto',
            'Open Sans',
            'Slabo 27px',
            'Lato',
            'Oswald',
            'Roboto Condensed',
            'Source Sans Pro',
            'Montserrat',
            'Raleway',
            'PT Sans',
            'Roboto Slab',
            'Merriweather'
        ];
    }

    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        $fonts = [];
        foreach ($this->getFonts() as $fontName) {
            $fonts[] = ['value' => $fontName,  'label' => $fontName];
        }
        return $fonts;
    }
}
