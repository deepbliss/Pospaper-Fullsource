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
use Aheadworks\RewardPoints\Model\ResourceModel\SpendRate as SpendRateResource;
use Aheadworks\RewardPoints\Model\ResourceModel\SpendRate\CollectionFactory as SpendRateCollectionFactory;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Data\Collection\AbstractDb;

/**
 * Class Aheadworks\RewardPoints\Model\Config\Backend\SpendRate
 */
class SpendRate extends \Magento\Framework\App\Config\Value
{
    /**
     * @var SpendRateResource
     */
    private $spendRateResource;

    /**
     * @var SpendRateCollectionFactory
     */
    private $spendRateCollectionFactory;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $config
     * @param TypeListInterface $cacheTypeList
     * @param SpendRateResource $spendRateResource
     * @param SpendRateCollectionFactory $spendRateCollectionFactory
     * @param AbstractResource $resource
     * @param AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        SpendRateResource $spendRateResource,
        SpendRateCollectionFactory $spendRateCollectionFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->spendRateResource = $spendRateResource;
        $this->spendRateCollectionFactory = $spendRateCollectionFactory;
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

    /**
     * {@inheritDoc}
     */
    public function beforeSave()
    {
        $value = $this->getValue();
        $this->setSpendRateValue($value);

        //to be able to check that the values have changed
        $this->setValue(serialize($value));
        return parent::beforeSave();
    }

    /**
     * {@inheritDoc}
     */
    public function afterSave()
    {
        $this->spendRateResource->saveConfigValue($this->getSpendRateValue());
        return parent::afterSave();
    }

    /**
     * {@inheritDoc}
     */
    protected function _afterLoad()
    {
        $collection = $this->spendRateCollectionFactory->create();
        $value = $collection->toConfigDataArray();
        $this->setValue($value);
    }
}
