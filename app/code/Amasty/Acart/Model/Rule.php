<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Model;

class Rule extends \Magento\Framework\Model\AbstractModel
{
    const CANCEL_CONDITION_CLICKED = 'clicked';

    const CANCEL_CONDITION_ANY_PRODUCT_WENT_OUT_OF_STOCK = 'any_product_went_out_of_stock';

    const CANCEL_CONDITION_ALL_PRODUCTS_WENT_OUT_OF_STOCK = 'all_products_went_out_of_stock';

    const SALES_RULE_PRODUCT_CONDITION_NAMESPACE = 'Magento\\SalesRule\\Model\\Rule\\Condition\\Product';

    const RULE_ACTIVE = '1';

    const RULE_INACTIVE = '0';

    /**
     * @var \Amasty\Acart\Model\SalesRule
     */
    protected $_salesRule;

    /**
     * @var
     */
    protected $_scheduleCollection;

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    protected $_dateTime;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    /**
     * @var \Amasty\Base\Model\Serializer
     */
    protected $serializer;

    /**
     * @var SalesRuleFactory
     */
    protected $salesRuleFactory;

    /**
     * @var Customer\AddressFactory
     */
    private $addressFactory;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Address\CollectionFactory
     */
    private $addressCollectionFactory;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        \Amasty\Base\Model\Serializer $serializer,
        \Amasty\Acart\Model\SalesRuleFactory $salesRuleFactory,
        \Amasty\Acart\Model\ResourceModel\Rule $resource,
        \Amasty\Acart\Model\Customer\AddressFactory $addressFactory,
        \Magento\Customer\Model\ResourceModel\Address\CollectionFactory $addressCollectionFactory,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->_dateTime = $dateTime;
        $this->_date = $date;
        $this->salesRuleFactory = $salesRuleFactory;
        $this->serializer = $serializer;
        $this->addressFactory = $addressFactory;
        $this->addressCollectionFactory = $addressCollectionFactory;
    }

    /**
     * @param $ruleId
     *
     * @return $this
     */
    public function loadById($ruleId)
    {
        $this->_resource->load($this, $ruleId);

        return $this;
    }

    /**
     * _construct
     */
    public function _construct()
    {
        $this->_init('Amasty\Acart\Model\ResourceModel\Rule');
    }

    /**
     * @return mixed
     */
    public function getSalesRule()
    {
        if (!$this->_salesRule) {
            $this->_salesRule = $this->salesRuleFactory->create()->load($this->getId());
        }

        return $this->_salesRule;
    }

    public function saveSchedule()
    {
        $schedule = $this->getSchedule();

        $savedIds = [];

        if (is_array($schedule) && count($schedule) > 0) {
            foreach ($schedule as $config) {
                /** @var \Amasty\Acart\Model\Schedule $object */
                $object = \Magento\Framework\App\ObjectManager::getInstance()
                    ->create('Amasty\Acart\Model\Schedule');

                if (isset($config['schedule_id'])) {
                    $object->load($config['schedule_id']);
                }

                $object->addData($config);

                $object->setRuleId($this->getId());

                $object->save();

                $savedIds[] = $object->getId();
            }
        } else {
            throw new \Magento\Framework\Exception\LocalizedException(__('The schedule should be completed.'));
        }

        /** @var \Amasty\Acart\Model\ResourceModel\Schedule\Collection $deleteCollection */
        $deleteCollection = \Magento\Framework\App\ObjectManager::getInstance()
            ->create('Amasty\Acart\Model\ResourceModel\Schedule\Collection')
            ->addFieldToFilter('rule_id', $this->getId())
            ->addFieldToFilter(
                'schedule_id',
                [
                    'nin' => $savedIds
                ]
            );

        foreach ($deleteCollection as $delete) {
            $delete->delete();
        }

        $ruleProductAttributes = $this->_getUsedAttributes($this->getConditionsSerialized());

        if (count($ruleProductAttributes)) {
            $this->getResource()->saveAttributes($this->getId(), $ruleProductAttributes);
        }
    }

    /**
     * Return all product attributes used on serialized action or condition
     *
     * @param string $serializedString
     *
     * @return array
     */
    protected function _getUsedAttributes($serializedString)
    {
        $result = [];
        $data = $this->serializer->unserialize($serializedString);

        if (is_array($data) && array_key_exists('conditions', $data)) {
            $result = $this->recursiveFindAttributes($data);
        }

        return array_filter($result);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function recursiveFindAttributes($data)
    {
        $arrayIterator = new \RecursiveIteratorIterator(
            new \RecursiveArrayIterator($data)
        );

        $result = [];
        $conditionAttribute = false;

        foreach ($arrayIterator as $key => $value) {
            if ($key == 'type' && $value == self::SALES_RULE_PRODUCT_CONDITION_NAMESPACE) {
                $conditionAttribute = true;
            }

            if ($key == 'attribute' && $conditionAttribute) {
                $result[] = $value;
                $conditionAttribute = false;
            }
        }

        return $result;
    }

    /**
     * @return \Amasty\Acart\Model\ResourceModel\Schedule\Collection
     */
    public function getScheduleCollection()
    {
        if (!$this->_scheduleCollection) {
            $this->_scheduleCollection = \Magento\Framework\App\ObjectManager::getInstance()
                ->create('Amasty\Acart\Model\ResourceModel\Schedule\Collection')
                ->addFieldToFilter('rule_id', $this->getId());
        }

        return $this->_scheduleCollection;
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     *
     * @return bool
     */
    private function validateAddress(\Magento\Quote\Model\Quote $quote)
    {
        $isValid = false;
        $quoteAddressIds = [];

        foreach ($quote->getAllAddresses() as $address) {
            if ($address->getAddressType() == \Magento\Quote\Model\Quote\Address::ADDRESS_TYPE_SHIPPING) {
                $address->setCollectShippingRates(true);
                $address->collectShippingRates();
            }

            $this->_initAddress($address, $quote);

            if ($this->getSalesRule()->validate($address)) {
                $isValid = true;
                break;
            } elseif ($address->getCustomerAddressId()) {
                $quoteAddressIds[] = $address->getCustomerAddressId();
            }
        }

        if (!$isValid && !$quote->getCustomerIsGuest()) {
            /** @var \Magento\Customer\Model\ResourceModel\Address\Collection $collection */
            $collection = $this->addressCollectionFactory->create();
            $collection->addFieldToFilter('parent_id', $quote->getCustomerId())
                ->addAttributeToSelect('*');

            if (!empty($quoteAddressIds)) {
                $collection->addFieldToFilter('entity_id', ['nin' => $quoteAddressIds]);
            }

            foreach ($collection->getItems() as $address) {
                if ($address instanceof \Magento\Customer\Model\Data\Address) {
                    $address = $this->addressFactory->create()->setAddress($address->setData('quote', $quote));
                }

                if ($this->getSalesRule()->validate($address)) {
                    $isValid = true;
                    break;
                }
            }
        }

        return $isValid;
    }

    protected function _initAddress($address, $quote)
    {
        $address->setData('total_qty', $quote->getData('items_qty'));

        return $address;
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     *
     * @return bool
     */
    public function validate(\Magento\Quote\Model\Quote $quote)
    {
        $storesIds = $customerGroupIds = [];
        $validStore = $validCustomerGroup = true;
        if (!empty($this->getStoreIds())) {
            $storesIds = explode(',', $this->getStoreIds());
        }
        if (!empty($this->getCustomerGroupIds())) {
            $customerGroupIds = explode(',', $this->getCustomerGroupIds());
        }

        if (!empty($storesIds)) {
            $validStore = in_array($quote->getStoreId(), $storesIds);
        }

        if (!empty($customerGroupIds)) {
            $validCustomerGroup = in_array($quote->getCustomerGroupId(), $customerGroupIds);
        }

        return $validStore
            && $validCustomerGroup
            && $this->validateAddress($quote);
    }
}
