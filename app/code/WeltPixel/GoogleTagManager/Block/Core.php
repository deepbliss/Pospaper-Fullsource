<?php
namespace WeltPixel\GoogleTagManager\Block;

/**
 * Class \WeltPixel\GoogleTagManager\Block\Core
 */
class Core extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \WeltPixel\GoogleTagManager\Helper\Data
     */
    protected $helper;

    /**
     * @var \WeltPixel\GoogleTagManager\Model\Storage
     */
    protected $storage;

    /**
     * @var \WeltPixel\GoogleTagManager\Model\CookieManager
     */
    protected $cookieManager;


    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \WeltPixel\GoogleTagManager\Helper\Data $helper
     * @param \WeltPixel\GoogleTagManager\Model\Storage $storage
     * @param \WeltPixel\GoogleTagManager\Model\CookieManager $cookieManager
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \WeltPixel\GoogleTagManager\Helper\Data $helper,
        \WeltPixel\GoogleTagManager\Model\Storage $storage,
        \WeltPixel\GoogleTagManager\Model\CookieManager $cookieManager,
        array $data = []
    )
    {
        $this->helper = $helper;
        $this->storage = $storage;
        $this->cookieManager = $cookieManager;
        parent::__construct($context, $data);
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->helper->isEnabled();
    }


    /**
     * @return bool
     */
    public function excludeTaxFromTransaction()
    {
        return $this->helper->excludeTaxFromTransaction();
    }

    /**
     * @return bool
     */
    public function excludeShippingFromTransaction()
    {
        return $this->helper->excludeShippingFromTransaction();
    }


    /**
     * @param $label
     * @param $value
     * @return $this
     */
    public function setEcommerceData($label, $value) {
        $ecommerceData = $this->getEcommerceData();
        if (!$ecommerceData)  {
            $ecommerceData = [];
        }
        $ecommerceData[$label] = $value;

        $this->setDataLayerOption('ecommerce', $ecommerceData);
        return $this;
    }

    /**
     * @param $label
     * @return mixed
     */
    public function getEcommerceData($label = null) {
        $ecommerceData = $this->getDataLayerOption('ecommerce');
        if (isset($label)) {
            return $ecommerceData[$label];
        }

        return $ecommerceData;
    }

    /**
     * @param $label
     * @param $value
     * @return $this
     */
    public function setDataLayerOption($label, $value) {
        $this->storage->setData($label, $value);
        return $this;
    }

    /**
     * @param $label
     * @return mixed
     */
    public function getDataLayerOption($label = null) {
        if ($label) {
            return $this->storage->getData($label);
        }

        return $this->storage->getData();
    }

    /**
     * @return string
     */
    public function getDataLayerAsJson()
    {
        $options = $this->getDataLayerOption();
        $options = $this->_splitImpressions($options);

        return json_encode($options);
    }

    /**
     * @return string
     */
    public function getCurrencyCode() {
        return $this->_storeManager->getStore()->getCurrentCurrencyCode();
    }


    /**
     * @param $options
     * @return mixed
     */
    private function _splitImpressions($options) {

        $result = [];
        $chunkLimit = $this->helper->getImpressionChunkSize();

        if (isset($options['ecommerce']['impressions'])) {
            $currencyCode = $options['ecommerce']['currencyCode'];
            $eventLabel = '';
            if (isset($options['eventLabel'])) {
                $eventLabel = $options['eventLabel'];
            }
            $originalImpressions = $options['ecommerce']['impressions'];
            $impressionsCount = count($originalImpressions);
            if ($impressionsCount <= $chunkLimit) {
                $result[] = $options;
                return $result;
            }

            $impressionChunks = array_chunk($originalImpressions, $chunkLimit);
            $options['ecommerce']['impressions'] = $impressionChunks[0];
            $result[] = $options;

            $chunkCount = count($impressionChunks);
            for ($i = 1; $i<$chunkCount; $i++ ) {
                $newImpressionChunk = [];
                $newImpressionChunk['ecommerce'] = [];
                $newImpressionChunk['ecommerce']['currencyCode'] = $currencyCode;
                $newImpressionChunk['ecommerce']['impressions'] = $impressionChunks[$i];

                $newImpressionChunk['event'] = 'impression';
                $newImpressionChunk['eventCategory'] = 'Ecommerce';
                $newImpressionChunk['eventAction'] = 'Impression';
                $newImpressionChunk['eventLabel'] = $eventLabel;

                $result[] = $newImpressionChunk;
            }

            return $result;
        } else {
            $result[] = $options;
            return $result;
        }
    }

    /**
     * @return string
     */
    public function getWpCookiesForJs() {
        $cookies = $this->cookieManager->getWpCookies();
        return implode(',', array_map(function ($a) { return "'" . $a . "'"; } ,$cookies));
    }
}