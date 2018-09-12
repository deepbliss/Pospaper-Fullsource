<?php
/**
 * Copyright (c) 2018 MageModule, LLC: All rights reserved
 *
 * LICENSE: This source file is subject to our standard End User License
 * Agreeement (EULA) that is available through the world-wide-web at the
 * following URI: http://www.magemodule.com/magento2-ext-license.html.
 *
 * If you did not receive a copy of the EULA and are unable to obtain it through
 * the web, please send a note to admin@magemodule.com so that we can mail
 * you a copy immediately.
 *
 * @author        MageModule, LLC admin@magemodule.com
 * @copyright     2018 MageModule, LLC
 * @license       http://www.magemodule.com/magento2-ext-license.html
 *
 */

namespace MageModule\Core\Model\Data;

class Sanitizer
{
    /**
     * @var \MageModule\Core\Model\Data\SanitizerInterface[]
     */
    private $pool;

    /**
     * ['field_name' => 'sanitizer_type']
     *
     * @var array
     */
    private $fields;

    /**
     * Sanitizer constructor.
     *
     * @param \MageModule\Core\Model\Data\SanitizerInterface[] $pool
     * @param array                                            $fields
     */
    public function __construct($pool = [], $fields = [])
    {
        $this->pool   = $pool;
        $this->fields = $fields;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function sanitize(array &$data)
    {
        foreach ($data as $key => &$value) {
            if (isset($this->fields[$key])) {
                $type = $this->fields[$key];
                if (isset($this->pool[$type])) {
                    $value = $this->pool[$type]->sanitize($value);
                }
            }
        }

        return $data;
    }
}
