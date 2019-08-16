<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Model\Source;

use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Data\Collection\AbstractDb;
use Amasty\Geoip\Model\Import as ModelImport;
use Amasty\Geoip\Helper\Data as GeoipHelper;

class DisableLogging extends \Magento\Framework\App\Config\Value
{
    const DEFAULT_VALUE = false;

    /**
     * @var GeoipHelper
     */
    private $geoipHelper;

    /**
     * @var ModelImport
     */
    private $import;

    public function __construct(
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        ModelImport $import,
        GeoipHelper $geoipHelper,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->import = $import;
        $this->geoipHelper = $geoipHelper;
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

    public function beforeSave()
    {
        if (!$this->geoipHelper->isDone() || !$this->import->importTableHasData()) {
            $this->setValue(self::DEFAULT_VALUE);
        }

        parent::beforeSave();
    }
}
