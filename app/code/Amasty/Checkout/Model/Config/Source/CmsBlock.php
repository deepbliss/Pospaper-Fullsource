<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Model\Config\Source;

class CmsBlock implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * @var \Magento\Cms\Api\BlockRepositoryInterface
     */
    private $blockRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaInterface
     */
    private $searchCriteria;

    /**
     * @var \Magento\Framework\Convert\DataObject
     */
    private $objectConverter;

    function __construct(
        \Magento\Cms\Api\BlockRepositoryInterface $blockRepository,
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \Magento\Framework\Convert\DataObject $objectConverter
    ) {
        $this->blockRepository = $blockRepository;
        $this->searchCriteria = $searchCriteria;
        $this->objectConverter = $objectConverter;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $result = ['value' => 0, 'label' => __('Please, select a static block')];
        $items = $this->blockRepository->getList($this->searchCriteria)->getItems();

        $options = $this->prepapreOptions($items);
        if (empty($options) || array_shift($options) === null) {
            $options = $this->objectConverter->toOptionArray(
                $items,
                'block_id',
                'title'
            );
        }

        array_unshift($options, $result);

        return $options;
    }

    /**
     * The method inits options for old version of magento
     *
     * @param array $items
     *
     * @return array
     */
    private function prepapreOptions($items = [])
    {
        $options = array_map(function ($item) {
            if (is_array($item)) {
                $value = $item['identifier'] ?: '';
                $label = $item['title'] ?: '';
                if ($value && $label) {
                    return ['value' => $value, 'label' => $label];
                }
            }
        }, $items);

        return $options;
    }
}
