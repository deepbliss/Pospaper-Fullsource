<?php


namespace Pos\CreditApp\Model;

use Pos\CreditApp\Api\Data\CreditAppInterface;
use Pos\CreditApp\Api\Data\CreditAppInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class CreditApp extends \Magento\Framework\Model\AbstractModel
{
    const BASE_MEDIA_PATH = 'creditapp';

    protected $creditappDataFactory;

    protected $dataObjectHelper;

    protected $_eventPrefix = 'pos_creditapp_creditapp';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param CreditAppInterfaceFactory $creditappDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Pos\CreditApp\Model\ResourceModel\CreditApp $resource
     * @param \Pos\CreditApp\Model\ResourceModel\CreditApp\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        CreditAppInterfaceFactory $creditappDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Pos\CreditApp\Model\ResourceModel\CreditApp $resource,
        \Pos\CreditApp\Model\ResourceModel\CreditApp\Collection $resourceCollection,
        array $data = []
    ) {
        $this->creditappDataFactory = $creditappDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve creditapp model with creditapp data
     * @return CreditAppInterface
     */
    public function getDataModel()
    {
        $creditappData = $this->getData();
        
        $creditappDataObject = $this->creditappDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $creditappDataObject,
            $creditappData,
            CreditAppInterface::class
        );
        
        return $creditappDataObject;
    }
}
