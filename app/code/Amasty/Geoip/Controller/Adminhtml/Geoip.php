<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Geoip
 */

namespace Amasty\Geoip\Controller\Adminhtml;

abstract class Geoip extends \Magento\Backend\App\Action
{

    /**
     * @var \Amasty\Geoip\Model\Import
     */
    protected $importModel;

    /**
     * @var \Amasty\Geoip\Helper\Data
     */
    protected $geoipHelper;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonHelper;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Amasty\Geoip\Model\Import $importModel,
        \Amasty\Geoip\Helper\Data $geoipHelper,
        \Magento\Framework\Json\Helper\Data $jsonHelper
    ) {
        parent::__construct($context);
        $this->importModel = $importModel;
        $this->geoipHelper = $geoipHelper;
        $this->jsonHelper = $jsonHelper;
    }

}
