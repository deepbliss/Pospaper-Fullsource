<?php
namespace WeltPixel\GoogleTagManager\CustomerData;

use Magento\Customer\CustomerData\SectionSourceInterface;

/**
 * Gtm section
 */
class Gtm extends \Magento\Framework\DataObject implements SectionSourceInterface
{

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonHelper;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * Constructor
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Magento\Checkout\Model\Session $_checkoutSession
     * @param \Magento\Customer\Model\Session $customerSession
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Checkout\Model\Session $_checkoutSession,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Api\GroupRepositoryInterface $groupRepository,
        \WeltPixel\GoogleTagManager\Helper\Data $gtmHelper,
        array $data = []
    )
    {
        parent::__construct($data);
        $this->jsonHelper = $jsonHelper;
        $this->_checkoutSession = $_checkoutSession;
        $this->customerSession = $customerSession;
    }

    /**
     * {@inheritdoc}
     */
    public function getSectionData()
    {

        $data = [];

        /** AddToCart data verifications */
        if ($this->_checkoutSession->getAddToCartData()) {
            $data[] = $this->_checkoutSession->getAddToCartData();
        }

        $this->_checkoutSession->setAddToCartData(null);

        /** RemoveFromCart data verifications */
        if ($this->_checkoutSession->getRemoveFromCartData()) {
            $data[] = $this->_checkoutSession->getRemoveFromCartData();
        }

        $this->_checkoutSession->setRemoveFromCartData(null);

        /** Checkout Steps data verifications */
        if ($this->_checkoutSession->getCheckoutOptionsData()) {
            $checkoutOptions = $this->_checkoutSession->getCheckoutOptionsData();
            foreach ($checkoutOptions as $options) {
                $data[] = $options;
            }
        }
        $this->_checkoutSession->setCheckoutOptionsData(null);

        /** Add To Wishlist Data */
        if ($this->customerSession->getAddToWishListData()) {
            $data[] = $this->customerSession->getAddToWishListData();
        }
        $this->customerSession->setAddToWishListData(null);

        /** Add To Compare Data */
        if ($this->customerSession->getAddToCompareData()) {
            $data[] = $this->customerSession->getAddToCompareData();
        }
        $this->customerSession->setAddToCompareData(null);

        return [
            'datalayer' => $this->jsonHelper->jsonEncode($data)
        ];
    }
}