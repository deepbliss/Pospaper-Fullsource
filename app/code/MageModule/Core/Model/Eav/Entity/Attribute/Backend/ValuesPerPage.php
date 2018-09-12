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

class ValuesPerPage extends \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
{
    /**
     * @param \MageModule\Core\Model\AbstractExtensibleModel $object
     *
     * @return $this
     */
    public function beforeSave($object)
    {
        $attributeCode = $this->getAttribute()->getName();
        $value         = $object->getData($attributeCode);
        if (is_array($value)) {
            asort($value);
            $value = str_replace(' ', '', implode(',', array_unique($value)));
            $object->setData($attributeCode, $value);
        }

        return $this;
    }

    public function validate($object)
    {
        //TODO validate postive values, integers only, comma separated
        return parent::validate($object);
    }
}
