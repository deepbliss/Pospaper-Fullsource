<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Price
 *
 * @package Aheadworks\RewardPoints\Ui\Component\Listing\Columns
 */
class Price extends Column
{
    /**
     * @var PriceCurrencyInterface
     */
    private $priceFormatter;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Constructor
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param PriceCurrencyInterface $priceFormatter
     * @param StoreManagerInterface $storeManager
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        PriceCurrencyInterface $priceFormatter,
        StoreManagerInterface $storeManager,
        array $components = [],
        array $data = []
    ) {
        $this->priceFormatter = $priceFormatter;
        $this->storeManager = $storeManager;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $website = $this->storeManager->getWebsite($item['website_id']);
                $currencyCode = $website->getBaseCurrencyCode();

                $item[$this->getData('name')] = $this->priceFormatter->format(
                    $item[$this->getData('name')],
                    false,
                    null,
                    null,
                    $currencyCode
                );
            }
        }

        return $dataSource;
    }
}
