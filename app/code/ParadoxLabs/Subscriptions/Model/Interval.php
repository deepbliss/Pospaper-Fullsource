<?php
/**
 * Paradox Labs, Inc.
 * http://www.paradoxlabs.com
 * 717-431-3330
 *
 * Need help? Open a ticket in our support system:
 *  http://support.paradoxlabs.com
 *
 * @author      Ryan Hoerr <info@paradoxlabs.com>
 * @license     http://store.paradoxlabs.com/license.html
 */

namespace ParadoxLabs\Subscriptions\Model;

use ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface;

/**
 * Interval Class
 */
class Interval extends \Magento\Framework\Model\AbstractExtensibleModel implements ProductIntervalInterface
{
    /**
     * @var string[] Values to build unique Interval identifying key from
     */
    const IDENTIFYING_KEYS = [
        'frequency_count',
        'frequency_unit',
        'length',
        'installment_price',
        'adjustment_price',
    ];

    /**
     * @var string
     */
    protected $_eventPrefix = 'paradoxlabs_subscription_interval';

    /**
     * @var string
     */
    protected $_eventObject = 'interval';

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $dateProcessor;

    /**
     * @var array
     */
    protected $additionalInfo;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Source\Period
     */
    protected $periodSource;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory
     * @param \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $dateProcessor
     * @param \ParadoxLabs\Subscriptions\Model\Source\Period $periodSource
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $dateProcessor,
        \ParadoxLabs\Subscriptions\Model\Source\Period $periodSource,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $resource,
            $resourceCollection,
            $data
        );

        $this->dateProcessor = $dateProcessor;
        $this->periodSource = $periodSource;
    }

    /**
     * Model construct that should be used for object initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\ParadoxLabs\Subscriptions\Model\ResourceModel\Interval::class);
    }

    /**
     * Finalize before saving.
     *
     * @return $this
     */
    public function beforeSave()
    {
        parent::beforeSave();

        if ($this->isObjectNew()) {
            /**
             * Set date.
             */
            $now = $this->dateProcessor->date(null, null, false);
            $this->setCreatedAt($now->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT));
        }

        return $this;
    }

    /**
     * Get product ID
     *
     * @return int
     */
    public function getProductId()
    {
        return (int)$this->getData('product_id');
    }

    /**
     * Set product ID
     *
     * @param int $productId
     * @return ProductIntervalInterface
     */
    public function setProductId($productId)
    {
        $this->setData('product_id', $productId);

        return $this;
    }

    /**
     * Get option ID
     *
     * @return int
     */
    public function getOptionId()
    {
        return (int)$this->getData('option_id');
    }

    /**
     * Set option ID
     *
     * @param int $optionId
     * @return ProductIntervalInterface
     */
    public function setOptionId($optionId)
    {
        $this->setData('option_id', $optionId);

        return $this;
    }

    /**
     * Get value ID
     *
     * @return int
     */
    public function getValueId()
    {
        return (int)$this->getData('value_id');
    }

    /**
     * Set value ID
     *
     * @param int $valueId
     * @return ProductIntervalInterface
     */
    public function setValueId($valueId)
    {
        $this->setData('value_id', $valueId);

        return $this;
    }

    /**
     * Get store ID
     *
     * @return int
     */
    public function getStoreId()
    {
        return (int)$this->getData('store_id');
    }

    /**
     * Set store ID
     *
     * @param int $storeId
     * @return ProductIntervalInterface
     */
    public function setStoreId($storeId)
    {
        $this->setData('store_id', $storeId);

        return $this;
    }

    /**
     * Get frequency count
     *
     * @return int
     */
    public function getFrequencyCount()
    {
        return (int)$this->getData('frequency_count');
    }

    /**
     * Set frequency count
     *
     * @param int $frequencyCount
     * @return ProductIntervalInterface
     */
    public function setFrequencyCount($frequencyCount)
    {
        $this->setData('frequency_count', max(0, (int)$frequencyCount));

        return $this;
    }

    /**
     * Get frequency unit
     *
     * @return string
     */
    public function getFrequencyUnit()
    {
        return $this->getData('frequency_unit');
    }

    /**
     * Set frequency unit
     *
     * @param string $frequencyUnit
     * @return \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function setFrequencyUnit($frequencyUnit)
    {
        if ($frequencyUnit === null) {
            $this->unsetData('frequency_unit');
            return $this;
        }

        if ($this->periodSource->isAllowedPeriod($frequencyUnit)) {
            $this->setData('frequency_unit', $frequencyUnit);
        } else {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Invalid frequency unit "%1"', $frequencyUnit)
            );
        }

        return $this;
    }

    /**
     * Get length
     *
     * @return int
     */
    public function getLength()
    {
        return (int)$this->getData('length');
    }

    /**
     * Set length
     *
     * @param int $length
     * @return ProductIntervalInterface
     */
    public function setLength($length)
    {
        $this->setData('length', $length);

        return $this;
    }

    /**
     * Get installment price
     *
     * @return float
     */
    public function getInstallmentPrice()
    {
        return $this->getData('installment_price') ? (float)$this->getData('installment_price') : null;
    }

    /**
     * Set installment price
     *
     * @param float $installmentPrice
     * @return ProductIntervalInterface
     */
    public function setInstallmentPrice($installmentPrice)
    {
        $this->setData('installment_price', $installmentPrice);

        return $this;
    }

    /**
     * Get adjustment price
     *
     * @return float
     */
    public function getAdjustmentPrice()
    {
        return $this->getData('adjustment_price') ? (float)$this->getData('adjustment_price') : null;
    }

    /**
     * Set adjustment price
     *
     * @param float $adjustmentPrice
     * @return ProductIntervalInterface
     */
    public function setAdjustmentPrice($adjustmentPrice)
    {
        $this->setData('adjustment_price', $adjustmentPrice);

        return $this;
    }

    /**
     * Set created-at date.
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->setData('created_at', $createdAt);

        return $this;
    }

    /**
     * Get created-at date.
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->getData('created_at');
    }

    /**
     * Get identifying key for comparison purposes.
     *
     * @return string
     */
    public function getKey()
    {
        $components = [];
        foreach (static::IDENTIFYING_KEYS as $key) {
            $value = $this->getData($key);

            // Ensure consistency of number formatting for comparison purposes
            $components[] = is_numeric($value) ? sprintf('%0.4f', $value) : (string)$value;
        }

        return implode('|', $components);
    }

    /**
     * Get additional information.
     *
     * If $key is set, will return that value or null; otherwise, will return an array of all additional date.
     *
     * @param string|null $key
     * @return mixed|null
     */
    public function getAdditionalInformation($key = null)
    {
        if ($this->additionalInfo === null) {
            $this->additionalInfo = json_decode(parent::getData('additional_information'), 1);

            if (empty($this->additionalInfo)) {
                $this->additionalInfo = [];
            }
        }

        if ($key !== null) {
            return (isset($this->additionalInfo[$key]) ? $this->additionalInfo[$key] : null);
        }

        return $this->additionalInfo;
    }

    /**
     * Set additional information.
     *
     * Can pass in a key-value pair to set one value, or a single parameter (associative array) to overwrite all data.
     *
     * @param string|array $key
     * @param string|null $value
     * @return $this
     */
    public function setAdditionalInformation($key, $value = null)
    {
        if ($value !== null) {
            if ($this->additionalInfo === null) {
                $this->getAdditionalInformation();
            }

            $this->additionalInfo[$key] = $value;
        } elseif (is_array($key)) {
            $this->additionalInfo = $key;
        }

        parent::setData('additional_information', json_encode($this->additionalInfo));

        return $this;
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
