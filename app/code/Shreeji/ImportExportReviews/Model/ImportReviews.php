<?php

namespace Shreeji\ImportExportReviews\Model;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\File\ReadInterface;
use Magento\OfflineShipping\Model\ResourceModel\Carrier\Tablerate\CSV\RowException;
use Magento\Store\Model\StoreManagerInterface;

class ImportReviews {

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var ScopeConfigInterface
     */
    private $coreConfig;

    /**
     * @var array
     */
    private $errors = [];

    /**
     * @var array
     */
    private $uniqueHash = [];

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     *
     * @var \Magento\Catalog\Model\Product 
     */
    protected $_product;

    /**
     *
     * @var status 
     */
    protected $_status = array(
        'approved' => 1,
        'pending' => 2,
        'not approved' => 3
    );

    /**
     *
     * @var \Magento\Review\Model\ReviewFactory
     */
    protected $_review;

    /**
     *
     * @var \Magento\Review\Model\RatingFactory 
     */
    protected $_rating;

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
     * @var customerTable 
     */
    protected $_customerTable;

    /**
     *
     * @var ratingarray 
     */
    protected $_ratingHeadar = array();

    /**
     *
     * @var ratingOptionTable 
     */
    protected $_ratingOptionTable;

    /**
     * 
     * @param StoreManagerInterface $storeManager
     * @param Filesystem $filesystem
     * @param ScopeConfigInterface $coreConfig
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Catalog\Model\Product $_product
     * @param \Magento\Review\Model\ReviewFactory $_review
     * @param \Magento\Review\Model\RatingFactory $_rating
     * @param \Magento\Framework\App\ResourceConnection $resource
     */
    public function __construct(
    StoreManagerInterface $storeManager, Filesystem $filesystem, ScopeConfigInterface $coreConfig, \Magento\Framework\Model\Context $context, \Magento\Catalog\Model\Product $_product, \Magento\Review\Model\ReviewFactory $_review, \Magento\Review\Model\RatingFactory $_rating, \Magento\Framework\App\ResourceConnection $resource
    ) {
        $this->storeManager = $storeManager;
        $this->filesystem = $filesystem;
        $this->coreConfig = $coreConfig;
        $this->logger = $context->getLogger();
        $this->_product = $_product;
        $this->_review = $_review;
        $this->_rating = $_rating;
        $this->_resource = $resource;
        $this->_connection = $this->_resource->getConnection();
        $this->_customerTable = $this->_resource->getTableName('customer_entity');
        $this->_ratingOptionTable = $this->_resource->getTableName('rating_option');
    }

    /**
     * 
     * @param type $filepath
     * @throws LocalizedException
     */
    public function importReviews($filepath) {
        $file = $this->getCsvFile($filepath);
        try {
            foreach ($this->getData($file) as $bunch) {
                $this->importReviewbyRow($bunch);
            }
        } catch (\Exception $e) {
            $this->logger->critical($e);
            throw new \Magento\Framework\Exception\LocalizedException(
            __('Something went wrong while importing customer reviews.')
            );
        }
        if ($this->hasErrors()) {
            $error = __(
                    'We couldn\'t import this data because of these errors: %1', implode(" \n", $this->getErrors())
            );
            throw new \Magento\Framework\Exception\LocalizedException($error);
        }
    }

    /**
     * @return bool
     */
    public function hasErrors() {
        return (bool) count($this->getErrors());
    }

    /**
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * @return array
     */
    public function getColumns() {
        return [
            'created_at',
            'sku',
            'nickname',
            'title',
            'detail',
            'status',
            'customer_id',
            'store_id'
        ];
    }

    /**
     * @param string $filePath
     * @return \Magento\Framework\Filesystem\File\ReadInterface
     */
    private function getCsvFile($filePath) {
        $directory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $path = $directory->getRelativePath($filePath);
        return $directory->openFile($path);
    }

    /**
     * @param ReadInterface $file
     * @param int $websiteId
     * @return \Generator
     * @throws LocalizedException
     */
    public function getData(ReadInterface $file) {
        $this->errors = [];
        $headers = $this->getHeaders($file);
        $defaultColumns = $this->getColumns();
        $ratingCheck = count($headers) >= 8 ? true : false;
        $rowNumber = 1;
        $items = [];
        while (false !== ($csvLine = $file->readCsv())) {
            try {
                $rowNumber++;
                if (empty($csvLine)) {
                    continue;
                }
                $rowData = array();
                for ($k = 0; $k < count($csvLine); $k++) {
                    if ($k >= 8) {
                        if ($k == 8) {
                            $rowData[trim($headers[$k])] = $csvLine[$k];
                        } else {
                            $this->_ratingHeadar[] = $headers[$k];
                        }
                        $rowData[trim($headers[$k])] = $csvLine[$k];
                    } else {
                        $rowData[$defaultColumns[$k]] = $csvLine[$k];
                    }
                }
                $items[] = $rowData;
            } catch (RowException $e) {
                $this->errors[] = $e->getMessage();
            }
        }
        if (count($items)) {
            yield $items;
        }
    }

    /**
     * @param ReadInterface $file
     * @return array|bool
     * @throws LocalizedException
     */
    protected function getHeaders(ReadInterface $file) {
        // check and skip headers
        $headers = $file->readCsv();
        if ($headers === false) {
            throw new LocalizedException(__('Please correct Review CSV File Format.'));
        }
        return $headers;
    }

    /**
     * 
     * @param array $bunch
     */
    protected function importReviewbyRow(array $bunch) {
        for ($i = 0; $i < count($bunch); $i++) {
            $addReview = $this->_addReview($bunch, $i);
            if ($addReview != false) {
                $hasStar = isset($bunch[$i]['has_star']) ? $bunch[$i]['has_star'] : null;
                $hasStar == 1 ? $this->_addRating($addReview, $bunch, $i) : "";
            }
        }
        return;
    }

    /**
     * 
     * @param array $bunch
     * @param integer $i
     * @return boolean
     */
    protected function _addReview($bunch, $i) {
        $productSku = isset($bunch[$i]['sku']) ? $bunch[$i]['sku'] : "";
        if (empty($productSku)) {
            return false;
        }
        $productId = $this->_product->getIdBySku($productSku);
        if (empty($productId)) {
            $this->errors[] = __("'$productSku' SKU is not exist,");
            return false;
        }
        $nickName = isset($bunch[$i]['nickname']) ? $bunch[$i]['nickname'] : "";
        $details = isset($bunch[$i]['detail']) ? $bunch[$i]['detail'] : "";
        $title = isset($bunch[$i]['title']) ? $bunch[$i]['title'] : "";
        $status = isset($bunch[$i]['status']) ? $bunch[$i]['status'] : "";
        $customerId = isset($bunch[$i]['customer_id']) ? $bunch[$i]['customer_id'] : null;
        $createdAt = isset($bunch[$i]['created_at']) ? $bunch[$i]['created_at'] : null;
        if (!empty($customerId)) {
            $customerTable = $this->_customerTable;
            $sql = "SELECT email FROM $customerTable WHERE entity_id=$customerId";
            $result = $this->_connection->fetchAll($sql);
            $customerEmail = null;
            foreach ($result as $singleresult) {
                $customerEmail = $singleresult['email'];
                if (empty($customerEmail)) {
                    $this->errors[] = __("$customerId customer ID is not exist");
                    return false;
                }
            }
        }
        $storeId = isset($bunch[$i]['store_id']) ? $bunch[$i]['store_id'] : "";
        $status = !empty($status) ? strtolower($status) : "";
        $status = $this->_status[$status];
        try {
            $review = $this->_review->create()->setEntityId(1)
                    ->setEntityPkValue($productId)
                    ->setNickname($nickName)
                    ->setTitle($title)
                    ->setDetail($details)
                    ->setStatusId($status)
                    ->setStoreId($storeId)
                    ->setStores($storeId);
            if (!empty($customerId)) {
                $review->setCustomerId($customerId);
            }
            if (!empty($createdAt)) {
                $review->setSkipCreatedAtSet(true);
                $review->setCreatedAt($createdAt);
            }
            $review->save();
            return $review;
        } catch (\Exception $ex) {
            $this->errors[] = __($ex->getMessage());
            return false;
        }
    }

    /**
     * 
     * @param obj $review
     * @param array $bunch
     * @param integer $i
     */
    protected function _addRating($review, $bunch, $i) {
        $radingHeadar = array_unique($this->_ratingHeadar);
        $optionTable = $this->_ratingOptionTable;
        for ($k = 0; $k < count($radingHeadar); $k++) {
            $headingRating = $radingHeadar[$k];
            if (!empty($headingRating)) {
                $ratingExplode = explode("rating_", $headingRating);
                $ratingId = $ratingExplode[1];
                if (!empty($ratingId)) {
                    $sql = "SELECT option_id from $optionTable WHERE rating_id=$ratingId";
                    $result = $this->_connection->fetchAll($sql);
                    $optionIds = "";
                    $flag = 1;
                    foreach ($result as $singleresult) {
                        $optionIds[$flag] = $singleresult['option_id'];
                        $flag++;
                    }
                    $optionResult = "";
                    $optionResult = isset($optionIds[$bunch[$i][$headingRating]]) ? $optionIds[$bunch[$i][$headingRating]] : null;
                    if (!empty($optionResult)) {
                        $ratingArray[$ratingId] = $optionResult;
                    }
                }
            }
        }
		$productSku = isset($bunch[$i]['sku']) ? $bunch[$i]['sku'] : "";
        $productId = $this->_product->getIdBySku($productSku);
        foreach ($ratingArray as $ratingId => $optionId) {
            $this->_rating->create()
                    ->setRatingId($ratingId)
                    ->setReviewId($review->getId())
                    ->setCustomerId($review->getCustomerId())
                    ->addOptionVote($optionId, $productId);
        }
        $review->aggregate();
        return;
    }

}
