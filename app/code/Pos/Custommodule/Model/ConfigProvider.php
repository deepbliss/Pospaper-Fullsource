<?php

namespace Pos\Custommodule\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Customer\Model\Context as CustomerContext;
use Magento\Customer\Api\CustomerRepositoryInterface as CustomerRepository;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Checkout\Model\Session as CheckoutSession;

class ConfigProvider implements ConfigProviderInterface
{
    protected $storeManager;
    protected $scopeConfig;
    protected $serialize;
    private $httpContext;
    private $customerRepository;
    private $customerSession;
    protected $checkoutSession;

    public function __construct(
        \Magento\Framework\Serialize\Serializer\Json $serialize,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        HttpContext $httpContext,
        CustomerRepository $customerRepository,
        CustomerSession $customerSession,
        CheckoutSession $checkoutSession
    ) {
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->serialize = $serialize;
        $this->httpContext = $httpContext;
        $this->customerRepository = $customerRepository;
        $this->customerSession = $customerSession;
        $this->checkoutSession = $checkoutSession;
    }

    public function getConfig()
    {


        $customerData = '';
        if ($this->customerSession->isLoggedIn()) {
            $customer = $this->customerSession->getCustomer();
            if($address = $customer->getDefaultBillingAddress()) {
                $customerData = $address->getCompany();
            }
        }

        return [
            'pospaper' => [
                'po_number' => $customerData,
            ],
        ];
    }
}