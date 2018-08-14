<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Freeshippinglabel\Block\Adminhtml\Settings\Index\Tab;

/**
 * Class Preview
 * @package Aheadworks\Freeshippinglabel\Block\Adminhtml\Settings\Index\Tab
 */
class Preview extends \Magento\Backend\Block\Template
{
    /**
     * Block template
     *
     * @var string
     */
    protected $_template = 'label/edit/preview.phtml';

    /**
     * Retrieve script options encoded to json
     *
     * @return string
     */
    public function getScriptOptions()
    {
        $params = [
            'currencies' => $this->getCurrencies()
        ];
        return json_encode($params);
    }

    /**
     * Retrieve currencies
     *
     * @return array
     */
    private function getCurrencies()
    {
        $currencies = [];
        foreach ($this->_storeManager->getStores(true) as $store) {
            $currencies[$store->getId()] = $store->getDefaultCurrency()->getCurrencySymbol();
        }
        return $currencies;
    }
}
