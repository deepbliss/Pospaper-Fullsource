<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Model\SalesRule\Condition;

class Carts extends \Magento\Rule\Model\Condition\AbstractCondition
{
    const ATTRIBUTE_CARDS_NUM = 'amasty_acart_cards_num';

    protected $_collectionFactory;

    public function __construct(
        \Magento\Rule\Model\Condition\Context $context,
        \Amasty\Acart\Model\ResourceModel\RuleQuote\CollectionFactory $collectionFactory,
        array $data = []
    ) {
        $this->_collectionFactory = $collectionFactory;

        return parent::__construct($context, $data);
    }

    public function loadAttributeOptions()
    {
        $attributes = [
            self::ATTRIBUTE_CARDS_NUM => __('Number of recovered cards this month'),
        ];

        $this->setAttributeOption($attributes);

        return $this;
    }

    public function getAttributeElement()
    {
        $element = parent::getAttributeElement();
        $element->setShowAsText(true);

        return $element;
    }

    public function getInputType()
    {
        return 'numeric';
    }

    public function getValueElementType()
    {
        return 'text';
    }

    public function validate(\Magento\Framework\Model\AbstractModel $model)
    {
        $quote = $model;

        if (!$quote instanceof \Magento\Quote\Model\Quote) {
            $quote = $model->getQuote();
        }

        $email = $quote->getTargetEmail() ? $quote->getTargetEmail() : $quote->getCustomerEmail();

        $from = (new \DateTime())->setTimestamp((new \DateTime())->getTimestamp())->format('Y-m-01');

        $to = (new \DateTime())->setTimestamp((new \DateTime())->getTimestamp())->format('Y-m-t');

        $collection = $this->_collectionFactory->create()
            ->addFieldToFilter('customer_email', $email)
            //            ->addFieldToFilter('status', \Amasty\Acart\Model\RuleQuote::STATUS_COMPLETE)
            ->addFieldToFilter(
                'created_at',
                ['gteq' => $from]
            )->addFieldToFilter(
                'created_at',
                ['lteq' => $to]
            );

        return $this->validateAttribute($collection->getSize());
    }
}
