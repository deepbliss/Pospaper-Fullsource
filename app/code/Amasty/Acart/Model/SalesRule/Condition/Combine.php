<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Model\SalesRule\Condition;

class Combine extends \Magento\SalesRule\Model\Rule\Condition\Combine
{
    public function __construct(
        \Magento\Rule\Model\Condition\Context $context,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\SalesRule\Model\Rule\Condition\Address $conditionAddress,
        array $data = []
    ) {
        parent::__construct($context, $eventManager, $conditionAddress, $data);

        $this->setType('Amasty\Acart\Model\SalesRule\Condition\Combine');
    }

    /**
     * Get new child select options
     *
     * @return array
     */
    public function getNewChildSelectOptions()
    {
        $conditions = parent::getNewChildSelectOptions();

        $conditions = array_merge_recursive(
            $conditions,
            [
                [
                    'value' => 'Amasty\Acart\Model\SalesRule\Condition\Carts',
                    'label' => __('Number of recovered cards this month')
                ]
            ]
        );

        return $conditions;
    }
}
