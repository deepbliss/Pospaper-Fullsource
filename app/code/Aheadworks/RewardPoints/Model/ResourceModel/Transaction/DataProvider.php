<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\ResourceModel\Transaction;

use Aheadworks\RewardPoints\Model\ResourceModel\Transaction\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * Class Aheadworks\RewardPoints\Model\ResourceModel\Transaction\DataProvider
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var array
     */
    private $loadedData;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->meta = $this->prepareMeta($this->meta);
    }

    /**
     *  {@inheritDoc}
     */
    public function getCollection()
    {
        if (null == $this->collection) {
            $this->collection = $this->collectionFactory->create();
        }
        return $this->collection;
    }

    /**
     * Prepares Meta
     *
     * @param array $meta
     * @return array
     */
    public function prepareMeta(array $meta)
    {
        return $meta;
    }

    /**
     *  {@inheritDoc}
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->getCollection()->getItems();
        /** @var $transaction \Aheadworks\RewardPoints\Model\Transaction */
        foreach ($items as $transaction) {
            $this->loadedData[$transaction->getId()] = $transaction->getData();
        }

        $data = $this->dataPersistor->get('transaction');
        if (!empty($data)) {
            $transaction = $this->getCollection()->getNewEmptyItem();
            $transaction->setData($data);
            $this->loadedData[$transaction->getId()] = $transaction->getData();
            $this->dataPersistor->clear('transaction');
        }

        return $this->loadedData;
    }
}
