<?php
/**
 * Copyright (c) 2018 MageModule, LLC: All rights reserved
 *
 * LICENSE: This source file is subject to our standard End User License
 * Agreeement (EULA) that is available through the world-wide-web at the
 * following URI: https://www.magemodule.com/magento2-ext-license.html.
 *
 *  If you did not receive a copy of the EULA and are unable to obtain it through
 *  the web, please send a note to admin@magemodule.com so that we can mail
 *  you a copy immediately.
 *
 * @author         MageModule admin@magemodule.com
 * @copyright      2018 MageModule, LLC
 * @license        https://www.magemodule.com/magento2-ext-license.html
 */

namespace MageModule\Core\Model\Eav\Entity\Attribute\Backend;

class StoreViews extends \Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend
{
    /**
     * @param \Magento\Framework\DataObject $object
     *
     * @return \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
     */
    public function beforeSave($object)
    {
        $attributeCode = $this->getAttribute()->getAttributeCode();
        $value         = $object->getData($attributeCode);
        if (is_string($value)) {
            $value = explode(',', $value);
        } elseif ($value === null) {
            $value = [];
        }

        $value = array_map('trim', $value);
        $value = array_filter($value, 'strlen');
        $value = array_filter($value, 'ctype_digit');
        $value = array_unique($value);
        asort($value);

        if (empty($value)) {
            $value = false;
        } else {
            $value = implode(',', $value);
        }

        $object->setData($attributeCode, $value);

        return parent::beforeSave($object);
    }

    /**
     * @param \Magento\Framework\DataObject $object
     *
     * @return \Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend
     */
    public function afterLoad($object)
    {
        $attributeCode = $this->getAttribute()->getAttributeCode();
        $value = explode(',', $object->getData($attributeCode));
        $object->setData($attributeCode, $value);

        return parent::afterLoad($object);
    }
}
