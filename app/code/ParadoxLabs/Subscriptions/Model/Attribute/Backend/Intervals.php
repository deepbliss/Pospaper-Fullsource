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

namespace ParadoxLabs\Subscriptions\Model\Attribute\Backend;

/**
 * Intervals Class
 */
class Intervals extends \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
{
    /**
     * @var \ParadoxLabs\Subscriptions\Model\Config
     */
    protected $config;

    /**
     * Intervals constructor.
     *
     * @param \ParadoxLabs\Subscriptions\Model\Config $config
     */
    public function __construct(
        \ParadoxLabs\Subscriptions\Model\Config $config
    ) {
        $this->config = $config;
    }

    /**
     * Validate subscription interval(s)
     *
     * @param \Magento\Framework\DataObject $object
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function validate($object)
    {
        /** @var \Magento\Catalog\Model\Product $object */
        parent::validate($object);

        $attrCode = $this->getAttribute()->getAttributeCode();
        $value = $object->getData($attrCode);

        // If array or JSON, we're dealing with grid values, skip normal validation.
        if (is_array($value) || (is_string($value) && !empty($value) && ($value[0] === '[' || $value[0] === '{'))) {
            return true;
        }

        /**
         * Ensure that comma-separated values are positive-numeric only.
         */
        $values = array_filter(explode(',', str_replace(' ', '', $value)));
        foreach ($values as $value) {
            if (preg_match('/\D/', $value)) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __(
                        'Attribute "%1" contains invalid interval "%2". Please only enter positive numbers.',
                        $attrCode,
                        $value
                    )
                );
            }
        }

        /**
         * Ensure bundle product does not have multiple interval options.
         */
        if ($object->getTypeId() === \Magento\Bundle\Model\Product\Type::TYPE_CODE) {
            if ($this->config->skipSingleOption() !== true) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __(
                        'To create subscription-enabled bundle products, please change setting Admin > Stores > '
                        . 'Configuration > Catalog > Adaptive Subscriptions > "Always add product option" to "No".'
                    )
                );
            }

            if (count($values) > 1) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __(
                        'Bundle products may not have more than one subscription interval (because they do not support '
                        . 'custom options). Please change the intervals to one value.',
                        $attrCode
                    )
                );
            }
        }

        // Repack after validation to ensure there are no duplicate or empty values left over.
        $object->setData($attrCode, implode(',', array_unique($values)));

        return true;
    }

    /**
     * Clean up values on save
     *
     * @param \Magento\Framework\DataObject  $object
     * @return \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
     */
    public function beforeSave($object)
    {
        $this->validate($object);

        return parent::beforeSave($object);
    }
}
