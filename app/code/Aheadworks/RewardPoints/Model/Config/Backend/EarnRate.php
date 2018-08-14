<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\Config\Backend;

use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Cache\TypeListInterface;
use Aheadworks\RewardPoints\Model\ResourceModel\EarnRate as EarnRateResource;
use Aheadworks\RewardPoints\Model\ResourceModel\EarnRate\CollectionFactory as EarnRateCollectionFactory;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Data\Collection\AbstractDb;

/**
 * Class Aheadworks\RewardPoints\Model\Config\Backend\EarnRate
 */
class EarnRate extends \Magento\Framework\App\Config\Value
{
    /**
     * @var EarnRateResource
     */
    private $earnRateResource;

    /**
     * @var EarnRateCollectionFactory
     */
    private $earnRateCollectionFactory;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $config
     * @param TypeListInterface $cacheTypeList
     * @param EarnRateResource $earnRateResource
     * @param EarnRateCollectionFactory $earnRateCollectionFactory
     * @param AbstractResource $resource
     * @param AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        EarnRateResource $earnRateResource,
        EarnRateCollectionFactory $earnRateCollectionFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->earnRateResource = $earnRateResource;
        $this->earnRateCollectionFactory = $earnRateCollectionFactory;
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

    /**
     * {@inheritDoc}
     */
    public function beforeSave()
    {
        $value = $this->getValue();
        $this->setEarnRateValue($value);

        //to be able to check that the values have changed
        $this->setValue(serialize($value));
        return parent::beforeSave();
    }

    /**
     * {@inheritDoc}
     */
    public function afterSave()
    {
        $this->earnRateResource->saveConfigValue($this->getEarnRateValue());
        return parent::afterSave();
    }

    /**
     * {@inheritDoc}
     */
    protected function _afterLoad()
    {
        $collection = $this->earnRateCollectionFactory->create();
        $value = $collection->toConfigDataArray();
        $this->setValue($value);
    }
}
