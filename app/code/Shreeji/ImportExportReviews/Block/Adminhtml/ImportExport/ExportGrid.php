<?php

namespace Shreeji\ImportExportReviews\Block\Adminhtml\ImportExport;

/**
 * Export Customer Reviews codes grid block
 * WARNING: This grid used for export Reviews
 *
 */
class ExportGrid extends \Magento\Backend\Block\Widget\Grid\Extended {

    /**
     *
     * @var status 
     */
    protected $_status = array(
        1 => 'approved',
        2 => 'pending',
        3 => 'not approved'
    );

    /**
     * Website filter
     *
     * @var int
     */
    protected $_websiteId;

    /**
     *
     * @var \Magento\Framework\App\ResourceConnection 
     */
    protected $_resource;

    /**
     *
     * @var connection 
     */
    protected $_connection;

    /**
     *
     * @var ratingOptionVoteTable
     */
    protected $_ratingOptionVoteTable;

    /**
     * Review collection model factory
     *
     * @var \Magento\Review\Model\ResourceModel\Review\Product\CollectionFactory
     */
    protected $_productsFactory;

    /**
     * 
     * @var \Magento\Framework\Data\Collection
     */
    protected $_blankCollection;

    /**
     * 
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Review\Model\ResourceModel\Review\Product\CollectionFactory $productsFactory
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param \Magento\Framework\Data\Collection $_blankCollection
     * @param array $data
     */
    public function __construct(
    \Magento\Backend\Block\Template\Context $context, \Magento\Backend\Helper\Data $backendHelper, \Magento\Review\Model\ResourceModel\Review\Product\CollectionFactory $productsFactory, \Magento\Framework\App\ResourceConnection $resource, \Magento\Framework\Data\Collection $_blankCollection, array $data = []
    ) {
        $this->_resource = $resource;
        $this->_connection = $this->_resource->getConnection();
        $this->_ratingOptionVoteTable = $this->_resource->getTableName('rating_option_vote');
        $this->_productsFactory = $productsFactory;
        $this->_blankCollection = $_blankCollection;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * Define grid properties
     *
     * @return void
     */
    protected function _construct() {
        parent::_construct();
        $this->setId('exportCustomerReviewGrid');
    }

    /**
     * Set current website
     *
     * @param int $websiteId
     * @return $this
     */
    public function setWebsiteId($websiteId) {
        $this->_websiteId = $this->_storeManager->getWebsite($websiteId)->getId();
        return $this;
    }

    /**
     * Retrieve current website id
     *
     * @return int
     */
    public function getWebsiteId() {
        $this->getCsv();
        if ($this->_websiteId === null) {
            $this->_websiteId = $this->_storeManager->getWebsite()->getId();
        }
        return $this->_websiteId;
    }

    /**
     * Prepare ZIP codes collection
     *
     * @return \Shreeji\Zipbasecod\Block\Adminhtml\Paymentmethod\Cod\Grid
     */
    protected function _prepareCollection() {
        /** @var $collection \Magento\Review\Model\ResourceModel\Review\Product\Collection */
        $reviewCollection = $this->_productsFactory->create();
        $reviewDatas = $reviewCollection->getData();
        if (count($reviewDatas) > 0) {
            $ratingOptionTable = $this->_ratingOptionVoteTable;
            $reviewIds = array();
            foreach ($reviewDatas as $reviewData) {
                $finalReviewDatas[$reviewData['review_id']]['created_at'] = $reviewData['review_created_at'];
                $finalReviewDatas[$reviewData['review_id']]['sku'] = $reviewData['sku'];
                $finalReviewDatas[$reviewData['review_id']]['nickname'] = $reviewData['nickname'];
                $finalReviewDatas[$reviewData['review_id']]['title'] = $reviewData['title'];
                $finalReviewDatas[$reviewData['review_id']]['detail'] = $reviewData['detail'];
                $finalReviewDatas[$reviewData['review_id']]['status'] = $this->_status[$reviewData['status_id']];
                $finalReviewDatas[$reviewData['review_id']]['customer_id'] = $reviewData['customer_id'];
                $finalReviewDatas[$reviewData['review_id']]['store_id'] = $reviewData['store_id'];
                $finalReviewDatas[$reviewData['review_id']]['has_star'] = 0;
                $reviewIds[] = $reviewData['review_id'];
            }
            $reviewIdsString = implode(",", $reviewIds);
            $sql = "SELECT rating_id,review_id,value FROM $ratingOptionTable where review_id in ($reviewIdsString)";
            $result = $this->_connection->fetchAll($sql);
            if (count($result) > 0) {
                foreach ($result as $singleResult) {
                    $raingid = $singleResult['rating_id'];
                    $finalReviewDatas[$singleResult['review_id']]["rating_$raingid"] = $singleResult['value'];
                    $finalReviewDatas[$singleResult['review_id']]['has_star'] = 1;
                }
            }
        }
        $collection = $this->_blankCollection;
        foreach ($finalReviewDatas as $finalReviewData) {
            $newObj = new \Magento\Framework\DataObject();
            $newObj->setData($finalReviewData);
            $collection->addItem($newObj);
        }
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare table columns
     *
     * @return \Magento\Backend\Block\Widget\Grid\Extended
     */
    protected function _prepareColumns() {
        $this->addColumn(
                'created_at', ['header' => __('created_at'), 'index' => 'created_at']
        );
        $this->addColumn(
                'sku', ['header' => __('sku'), 'index' => 'sku']
        );
        $this->addColumn(
                'nickname', ['header' => __('nickname'), 'index' => 'nickname']
        );
        $this->addColumn(
                'title', ['header' => __('title'), 'index' => 'title']
        );
        $this->addColumn(
                'detail', ['header' => __('detail'), 'index' => 'detail']
        );
        $this->addColumn(
                'status', ['header' => __('status'), 'index' => 'status']
        );
        $this->addColumn(
                'customer_id', ['header' => __('customer_id'), 'index' => 'customer_id']
        );
        $this->addColumn(
                'store_id', ['header' => __('store_id'), 'index' => 'store_id']
        );
        $this->addColumn(
                'has_star', ['header' => __('has_star'), 'index' => 'has_star']
        );
        $ratingTable = $this->_resource->getTableName('rating');
        $sql = "SELECT rating_id FROM $ratingTable";
        $result = $this->_connection->fetchAll($sql);
        foreach ($result as $singleresult) {
            $ratingId = "rating_" . $singleresult['rating_id'];
            $this->addColumn(
                    "$ratingId", ['header' => __("$ratingId"), 'index' => "$ratingId"]
            );
        }
        return parent::_prepareColumns();
    }

}
