<?php
/**
 * @copyright Copyright (c) 2016 www.magebuzz.com
 */
namespace Magebuzz\Testimonial\Helper;

use Magebuzz\Testimonial\Model\TestimonialFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Filesystem;
use Magento\Store\Model\StoreManager;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    /**
     * @var Filesystem
     */
    protected $_filesystem;
    /**
     * @var TestimonialFactory
     */
    protected $_testimonialFactory;
    /**
     * @var StoreManager
     */
    protected $_storeManager;

    protected $_request;

    protected $_assetRepo;

    protected $_urlBuilder;

    const BASE_MEDIA_PATH = 'magebuzz/testimonial/images/';
    const IMAGE_WIDTH = '150px';
    const IMAGE_HEIGHT = '60px';
    const IMAGE_STYLE = 'display: block;margin: auto;';

    const XML_PATH_TOP_LINK = 'testimonial_config/group_testimonial_general/config_toplink';
    const XML_PATH_SIDE_BAR = 'testimonial_config/group_testimonial_general/config_sidabar';
    const XML_PATH_PAGING = 'testimonial_config/group_testimonial_general/config_paging';
    const XML_PATH_AMOUNT_WORD = 'testimonial_config/group_testimonial_general/config_amount_word';
    const XML_PATH_CAPTCHA = 'testimonial_config/group_testimonial_configuration/config_captcha';
    const XML_PATH_PER_PAGE = 'testimonial_config/group_testimonial_general/config_per_page';
    const XML_PATH_ALLOW_SUBMIT = 'testimonial_config/group_testimonial_configuration/config_allow_customer_submit';
    const XML_PATH_MESSAGE_THANKYOU = 'testimonial_config/group_testimonial_configuration/config_thank_you_message';
    const XML_PATH_APPROVE = 'testimonial_config/group_testimonial_configuration/config_approve';
    const XML_PATH_SUBMIT_EMAIL = 'testimonial_config/group_testimonial_email_configuration/config_send_email_after_approved';
    const XML_PATH_APPROVE_EMAIL = 'testimonial_config/group_testimonial_email_configuration/config_send_email_after_post';

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * Data constructor.
     */
    public function __construct(
        Context $context,
        Filesystem $filesystem,
        TestimonialFactory $testimonial,
        StoreManager $storeManager,
        ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        \Magento\Framework\UrlInterface $urlBuilder
    )
    {
        $this->_scopeConfig = $scopeConfig;
        $this->_storeManager = $storeManager;
        $this->_testimonialFactory = $testimonial;
        $this->_filesystem = $filesystem;
        $this->_request = $request;
        $this->_assetRepo = $assetRepo;
        $this->_urlBuilder = $urlBuilder;
        parent::__construct($context);
    }

    /**
     * get config toplink is enable or disable
     * @return mixed
     */
    public function isEnabledInTopLink()
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_TOP_LINK,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function isEnabledInSidebar()
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_SIDE_BAR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function isEnabledInPaging()
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_PAGING,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getAmountWord()
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_AMOUNT_WORD,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getPerPage()
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_PER_PAGE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function isAllowSubmit()
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_ALLOW_SUBMIT,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getMessageThankYou()
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_MESSAGE_THANKYOU,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * @return mixed
     */
    public function isCaptcha()
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_CAPTCHA,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function isApprove() {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_APPROVE,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function isApproveEmail() {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_APPROVE_EMAIL,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function isSubmitEmail() {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_SUBMIT_EMAIL,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @param $image
     * @return string
     */
    public function getImageUrl($image)
    {
        $path = $this->_filesystem->getDirectoryRead(
            DirectoryList::MEDIA
        )->getAbsolutePath(
            'magebuzz/testimonial/images/resized/'
        );

        if ($image && file_exists($path . $image)) {
            $path = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
            return $path . 'magebuzz/testimonial/images/resized/' . $image;
        } else{
            return $this->getViewFileUrl('Magebuzz_Testimonial::images/default-avatar.jpg');
        }
    }

    /**
     * Set a specified store ID value
     *
     * @param int $store
     * @return $this
     */
    public function setStoreId($store)
    {
        $this->_storeId = $store;
        return $this;
    }

    /**
     * Retrieve url of a view file
     *
     * @param string $fileId
     * @param array $params
     * @return string
     */
    protected function getViewFileUrl($fileId, array $params = [])
    {
        try {
            $params = array_merge(['_secure' => $this->_request->isSecure()], $params);
            return $this->_assetRepo->getUrlWithParams($fileId, $params);
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            return $this->_urlBuilder->getUrl('', ['_direct' => 'core/index/notFound']);
        }
    }
}