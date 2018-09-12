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

class StatusHistory extends \MageModule\OrderImportExport\Model\Processor\AbstractProcessor implements
    \MageModule\OrderImportExport\Model\Processor\ProcessorInterface
{
    const KEY = 'status_histories';

    /**
     * @var \MageModule\OrderImportExport\Model\Parser\ParserInterface
     */
    private $parser;

    /**
     * @var \Magento\Sales\Api\Data\OrderStatusHistoryInterfaceFactory
     */
    private $objectFactory;

    /**
     * StatusHistory constructor.
     *
     * @param \MageModule\OrderImportExport\Model\Parser\ParserInterface $parser
     * @param \Magento\Sales\Api\Data\OrderStatusHistoryInterfaceFactory $objectFactory
     * @param array                                                      $excludedFields
     */
    public function __construct(
        \MageModule\OrderImportExport\Model\Parser\ParserInterface $parser,
        \Magento\Sales\Api\Data\OrderStatusHistoryInterfaceFactory $objectFactory,
        $excludedFields = []
    ) {
        parent::__construct($excludedFields);
        $this->parser        = $parser;
        $this->objectFactory = $objectFactory;
    }

    /**
     * @param array                                  $data
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     *
     * @return $this
     */
    public function process(array $data, \Magento\Sales\Api\Data\OrderInterface $order)
    {
        $statusHistoryString = $order->getData(self::KEY);
        $statusHistories     = $this->parser->parse($statusHistoryString);

        foreach ($statusHistories as &$statusHistory) {
            /** @var \Magento\Sales\Api\Data\OrderStatusHistoryInterface $object */
            $object = $this->objectFactory->create();
            $object->addData($statusHistory);
            $statusHistory = $object;
        }

        if ($statusHistories) {
            $order->setStatusHistories($statusHistories);
        }

        return $this;
    }
}
