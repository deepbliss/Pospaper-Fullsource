<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Controller\Index;

use Magento\Store\Model\ScopeInterface;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;

class Index extends \Magento\Checkout\Controller\Index\Index
{
    /**
     * @var \Amasty\Checkout\Helper\Onepage
     */
    protected $onepageHelper;
    /**
     * @var \Magento\Checkout\Helper\Data
     */
    protected $checkoutHelper;

    /**
     * Index constructor.
     *
     * @param \Magento\Framework\App\Action\Context              $context
     * @param \Magento\Customer\Model\Session                    $customerSession
     * @param CustomerRepositoryInterface                        $customerRepository
     * @param AccountManagementInterface                         $accountManagement
     * @param \Magento\Framework\Registry                        $coreRegistry
     * @param \Magento\Framework\Translate\InlineInterface       $translateInline
     * @param \Magento\Framework\Data\Form\FormKey\Validator     $formKeyValidator
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\View\LayoutFactory              $layoutFactory
     * @param \Magento\Quote\Api\CartRepositoryInterface         $quoteRepository
     * @param \Magento\Framework\View\Result\PageFactory         $resultPageFactory
     * @param \Magento\Framework\View\Result\LayoutFactory       $resultLayoutFactory
     * @param \Magento\Framework\Controller\Result\RawFactory    $resultRawFactory
     * @param \Magento\Framework\Controller\Result\JsonFactory   $resultJsonFactory
     * @param \Amasty\Checkout\Helper\Onepage                    $onepageHelper
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        CustomerRepositoryInterface $customerRepository,
        AccountManagementInterface $accountManagement,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\Translate\InlineInterface $translateInline,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Amasty\Checkout\Helper\Onepage $onepageHelper,
        \Magento\Checkout\Helper\Data $checkoutHelper
    ) {
        parent::__construct(
            $context, $customerSession, $customerRepository, $accountManagement, $coreRegistry, $translateInline,
            $formKeyValidator, $scopeConfig, $layoutFactory, $quoteRepository, $resultPageFactory, $resultLayoutFactory,
            $resultRawFactory, $resultJsonFactory
        );
        
        $this->onepageHelper = $onepageHelper;
        $this->checkoutHelper = $checkoutHelper;
    }

    /**
     * Checkout page
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        if (!$this->scopeConfig->isSetFlag('amasty_checkout/general/enabled', ScopeInterface::SCOPE_STORE))
            return parent::execute();

        if (!$this->checkoutHelper->canOnepageCheckout()) {
            $this->messageManager->addErrorMessage(__('One-page checkout is turned off.'));
            return $this->resultRedirectFactory->create()->setPath('checkout/cart');
        }

        $quote = $this->getOnepage()->getQuote();
        if (!$quote->hasItems() || $quote->getHasError() || !$quote->validateMinimumAmount()) {
            return $this->resultRedirectFactory->create()->setPath('checkout/cart');
        }

        if (!$this->_customerSession->isLoggedIn() && !$this->checkoutHelper->isAllowedGuestCheckout($quote)) {
            $this->messageManager->addErrorMessage(__('Guest checkout is disabled.'));
            return $this->resultRedirectFactory->create()->setPath('checkout/cart');
        }

        $this->_customerSession->regenerateId();
        $this->_objectManager->get('Magento\Checkout\Model\Session')->setCartWasUpdated(false);
        $this->getOnepage()->initCheckout();
        $resultPage = $this->resultPageFactory->create();

        if ($font = $this->scopeConfig->getValue('amasty_checkout/design/font', ScopeInterface::SCOPE_STORE)) {
            $resultPage->getConfig()->addRemotePageAsset(
                'https://fonts.googleapis.com/css?family=' . urlencode($font),
                'css'
            );
        }

        $resultPage->getLayout()->getUpdate()->addHandle('amasty_checkout');

        if ($this->scopeConfig->getValue('amasty_checkout/design/header_footer', ScopeInterface::SCOPE_STORE)) {
            $resultPage->getConfig()->setPageLayout("1column");
        }

        /** @var \Magento\Checkout\Block\Onepage $checkoutBlock */
        $checkoutBlock = $resultPage->getLayout()->getBlock('checkout.root');

        $checkoutBlock
            ->setTemplate('Amasty_Checkout::onepage.phtml')
            ->setData('amcheckout_helper', $this->onepageHelper)
        ;

        $resultPage->getConfig()->getTitle()->set(__('Checkout'));
        return $resultPage;
    }
}
