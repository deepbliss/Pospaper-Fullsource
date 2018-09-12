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
 * @author       MageModule, LLC admin@magemodule.com
 * @copyright   2018 MageModule, LLC
 * @license       http://www.magemodule.com/magento2-ext-license.html
 *
 */

namespace MageModule\OrderImportExport\Model\Processor;

interface ProcessorInterface
{
    /**
     * @param array                                                             $data
     * @param \Magento\Sales\Api\Data\OrderInterface|\Magento\Sales\Model\Order $order
     *
     * @return mixed
     */
    public function process(array $data, \Magento\Sales\Api\Data\OrderInterface $order);

    /**
     * @param \MageModule\OrderImportExport\Api\Data\ImportConfigInterface $config
     *
     * @return $this
     */
    public function setConfig(\MageModule\OrderImportExport\Api\Data\ImportConfigInterface $config);
}
