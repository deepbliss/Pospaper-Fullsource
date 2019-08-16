<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Geoip
 */

namespace Amasty\Geoip\Controller\Adminhtml;

use Amasty\Geoip\Helper\Data as Helper;
use Amasty\Geoip\Model\Import;
use Amasty\Geoip\Model\Geolocation;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Json\Helper\Data as JsonData;
use Magento\Framework\Filesystem\Driver\File;

/**
 * Class GeoipAbstract
 */
abstract class GeoipAbstract extends Action
{

    /**
     * @var Import
     */
    protected $importModel;

    /**
     * @var Helper
     */
    protected $geoipHelper;

    /**
     * @var JsonData
     */
    protected $jsonHelper;

    /**
     * @var File
     */
    protected $driverFile;

    /**
     * @var Geolocation
     */
    protected $geolocationModel;

    public function __construct(
        Context $context,
        Import $importModel,
        Helper $geoipHelper,
        JsonData $jsonHelper,
        File $driverFile,
        Geolocation $geolocationModel
    ) {
        parent::__construct($context);
        $this->importModel = $importModel;
        $this->geoipHelper = $geoipHelper;
        $this->jsonHelper = $jsonHelper;
        $this->driverFile = $driverFile;
        $this->geolocationModel = $geolocationModel;
    }
}
