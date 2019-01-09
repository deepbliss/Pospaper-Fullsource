<?php

namespace Pos\Custommodule\Controller\Adminhtml\Orders;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;

class Search extends \Pos\Custommodule\Controller\Adminhtml\Index
{
    protected $orderFactory;
    protected $customerRegistry;
    protected $rateCalculator;
    protected $earningCalculator;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Framework\App\State $appState,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Customer\Model\CustomerRegistry $customerRegistry,
        \Aheadworks\RewardPoints\Model\Calculator\RateCalculator $rateCalculator,
        \Aheadworks\RewardPoints\Model\Calculator\Earning $earningCalculator
    ) {
        parent::__construct($context,$resource,$appState,$logger,$resultPageFactory);
        $this->orderFactory = $orderFactory;
        $this->customerRegistry = $customerRegistry;
        $this->rateCalculator = $rateCalculator;
        $this->earningCalculator = $earningCalculator;
    }

    public function execute()
    {
        $responseData = array('error' => 'Order not found');
        try {
            $orderIncrementId = trim($this->getRequest()->getParam('order_number'));
            if($orderIncrementId) {
                $order = $this->orderFactory->create()->loadByIncrementId($orderIncrementId);
                if($order->getId() && $order->getIncrementId() == $orderIncrementId) {
                    if($order->getCustomerId() > 0 && !$order->getCustomerIsGuest()) {
                        throw new LocalizedException(new Phrase('This order is already assigned to customer'));
                    }
                    $storeId = $order->getStoreId();
                    if($storeId > 0) {
                        $customer = $this->customerRegistry->retrieveByEmail($order->getCustomerEmail(), $storeId);
                    } else {
                        $customer = $this->customerRegistry->retrieveByEmail($order->getCustomerEmail());
                    }
                    $subtotal = $order->getBaseGrandTotal()
                        - $order->getBaseShippingAmount()
                        + $order->getBaseShippingDiscountAmount()
                        - $order->getBaseTaxAmount();

                    $customerId = $customer->getId();
                    //$rewardPoints = $this->rateCalculator->calculateEarnPoints($customerId, $subtotal, $storeId);
                    $invoices = $order->getInvoiceCollection();
                    $rewardPointsForPurchase = 0;
                    foreach ($invoices as $invoice)
                    {
                        $rewardPointsForPurchase += $this->earningCalculator->calculation($invoice, $customerId, $storeId);
                    }

                    $resultPage = $this->_resultPageFactory->create();
                    $block = $resultPage->getLayout()
                        ->getBlock('transfer.order.to.customer')
                        ->setTemplate('Pos_Custommodule::orders/order.phtml')
                        ->setOrder($order)
                        ->setSubtotal($subtotal)
                        ->setCustomer($customer)
                        ->setSubmitUrl($this->getUrl('pospaper/orders/link'));
                    if(count($invoices) == 0) {
                        $block->setNoRewards('Rewards will be applied after order is invoiced');
                        $block->setRewards(0);
                    } else if($rewardPointsForPurchase > 0) {
                        $block->setRewards($rewardPointsForPurchase);
                        $block->setNoRewards('');
                    } else {
                        $block->setNoRewards('Rewards calculation error');
                        $block->setRewards(0);
                    }

                    $responseData = array(
                        'status' => 'done',
                        'html'    => $block->toHtml()
                    );
                }
            }
        } catch (LocalizedException $e) {
            $this->_logger->critical($e);
            $responseData['error'] = $e->getMessage();
        } catch (\Exception $e) {
            $this->_logger->critical($e);
            $responseData['error'] = __('Something went wrong, please check exception logs.');
        }

        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($responseData);
        return $resultJson;
    }
}
