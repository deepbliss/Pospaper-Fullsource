<?php


namespace Pos\CreditApp\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Pos\CreditApp\Api\Data\CreditAppSearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Store\Model\StoreManagerInterface;
use Pos\CreditApp\Model\ResourceModel\CreditApp as ResourceCreditApp;
use Pos\CreditApp\Model\ResourceModel\CreditApp\CollectionFactory as CreditAppCollectionFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Exception\NoSuchEntityException;
use Pos\CreditApp\Api\CreditAppRepositoryInterface;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Pos\CreditApp\Api\Data\CreditAppInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class CreditAppRepository implements CreditAppRepositoryInterface
{

    protected $creditAppFactory;

    private $collectionProcessor;

    protected $extensibleDataObjectConverter;
    protected $extensionAttributesJoinProcessor;

    protected $dataObjectHelper;

    protected $resource;

    protected $dataCreditAppFactory;

    protected $searchResultsFactory;

    protected $dataObjectProcessor;

    protected $creditAppCollectionFactory;

    private $storeManager;


    /**
     * @param ResourceCreditApp $resource
     * @param CreditAppFactory $creditAppFactory
     * @param CreditAppInterfaceFactory $dataCreditAppFactory
     * @param CreditAppCollectionFactory $creditAppCollectionFactory
     * @param CreditAppSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceCreditApp $resource,
        CreditAppFactory $creditAppFactory,
        CreditAppInterfaceFactory $dataCreditAppFactory,
        CreditAppCollectionFactory $creditAppCollectionFactory,
        CreditAppSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->creditAppFactory = $creditAppFactory;
        $this->creditAppCollectionFactory = $creditAppCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataCreditAppFactory = $dataCreditAppFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Pos\CreditApp\Api\Data\CreditAppInterface $creditApp
    ) {
        /* if (empty($creditApp->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $creditApp->setStoreId($storeId);
        } */
        
        $creditAppData = $this->extensibleDataObjectConverter->toNestedArray(
            $creditApp,
            [],
            \Pos\CreditApp\Api\Data\CreditAppInterface::class
        );
        
        $creditAppModel = $this->creditAppFactory->create()->setData($creditAppData);
        
        try {
            $this->resource->save($creditAppModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the creditApp: %1',
                $exception->getMessage()
            ));
        }
        return $creditAppModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getById($creditAppId)
    {
        $creditApp = $this->creditAppFactory->create();
        $this->resource->load($creditApp, $creditAppId);
        if (!$creditApp->getId()) {
            throw new NoSuchEntityException(__('CreditApp with id "%1" does not exist.', $creditAppId));
        }
        return $creditApp->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->creditAppCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Pos\CreditApp\Api\Data\CreditAppInterface::class
        );
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model->getDataModel();
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Pos\CreditApp\Api\Data\CreditAppInterface $creditApp
    ) {
        try {
            $creditAppModel = $this->creditAppFactory->create();
            $this->resource->load($creditAppModel, $creditApp->getCreditappId());
            $this->resource->delete($creditAppModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the CreditApp: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($creditAppId)
    {
        return $this->delete($this->getById($creditAppId));
    }
}
