<?php

/**
 * Copyright © 2017 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Wyomind\Core\Logger;

/**
 * Logger for Wyomind_Core
 */
class Logger extends \Monolog\Logger
{
	public function __construct($name, array $handlers = array(), array $processors = array())
    {
        $this->name = $name;
        $this->handlers = $handlers;
        $this->processors = $processors;
    }
}
