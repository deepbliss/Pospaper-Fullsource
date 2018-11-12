<?php

namespace WeltPixel\GoogleTagManager\Model;

use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;
use \Magento\Framework\Session\SessionManagerInterface;
use \Magento\Customer\Model\Session as CustomerSession;
use \Magento\Customer\Api\GroupRepositoryInterface;
use \WeltPixel\GoogleTagManager\Helper\Data as GtmHelper;

/**
 * Class \WeltPixel\GoogleTagManager\Model\CookieManager
 */
class CookieManager
{
    const COOKIE_CUSTOMER_ID = 'wp_customerId';
    const COOKIE_CUSTOMER_GROUP = 'wp_customerGroup';

    /**
     * @var CookieMetadataFactory
     */
    protected $cookieMetadataFactory;

    /**
     * @var CookieManagerInterface
     */
    protected $cookieManager;

    /**
     * @var SessionManagerInterface
     */
    protected $sessionManager;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * Customer group repository
     *
     * @var GroupRepositoryInterface
     */
    protected $groupRepository;

    /**
     * @var \WeltPixel\GoogleTagManager\Helper\Data
     */
    protected $gtmHelper;

    /**
     * @param CookieMetadataFactory $cookieMetadataFactory
     * @param CookieManagerInterface $cookieManager
     * @param SessionManagerInterface $sessionManager
     * @param CustomerSession $customerSession
     * @param GroupRepositoryInterface $groupRepository
     * @param GtmHelper $gtmHelper
     */
    public function __construct(
        CookieMetadataFactory $cookieMetadataFactory,
        CookieManagerInterface $cookieManager,
        SessionManagerInterface $sessionManager,
        CustomerSession $customerSession,
        GroupRepositoryInterface $groupRepository,
        GtmHelper $gtmHelper
    )
    {
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->cookieManager = $cookieManager;
        $this->sessionManager = $sessionManager;
        $this->customerSession = $customerSession;
        $this->groupRepository = $groupRepository;
        $this->gtmHelper = $gtmHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function setGtmCookies()
    {
        $cookieMetadata = $this->cookieMetadataFactory->createPublicCookieMetadata()
            ->setDurationOneYear()
            ->setPath('/')
            ->setDomain($this->sessionManager->getCookieDomain())
            ->setHttpOnly(false)
        ;

        if ($this->gtmHelper->isCustomDimensionCustomerIdEnabled() && $this->customerSession->isLoggedIn()) {
            $customerId = $this->customerSession->getCustomerId();
            $this->cookieManager->setPublicCookie(self::COOKIE_CUSTOMER_ID, $customerId, $cookieMetadata);
        } else {
            $this->cookieManager->deleteCookie(self::COOKIE_CUSTOMER_ID, $cookieMetadata);
        }


        if ($this->gtmHelper->isCustomDimensionCustomerGroupEnabled()) {
            $customerGroup = 'NOT LOGGED IN';
            if ($this->customerSession->isLoggedIn()) {
                $customerGroupId = $this->customerSession->getCustomerGroupId();
                $groupObj = $this->groupRepository->getById($customerGroupId);
                $customerGroup = $groupObj->getCode();
            }
            $this->cookieManager->setPublicCookie(self::COOKIE_CUSTOMER_GROUP, $customerGroup, $cookieMetadata);
        } else {
            $this->cookieManager->deleteCookie(self::COOKIE_CUSTOMER_GROUP, $cookieMetadata);
        }
    }

    /**
     * @return array
     */
    public function getWpCookies() {
        $wpCookies = [
            self::COOKIE_CUSTOMER_ID,
            self::COOKIE_CUSTOMER_GROUP
        ];

        return $wpCookies;
    }
}