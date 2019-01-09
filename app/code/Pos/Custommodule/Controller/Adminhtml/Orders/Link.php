<?php

namespace Pos\Custommodule\Controller\Adminhtml\Orders;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Controller\ResultFactory;
use Aheadworks\RewardPoints\Model\Config as RewardPointsConfig;
use Aheadworks\RewardPoints\Model\DateTime as RewardPointsDateTime;
use Aheadworks\RewardPoints\Api\CustomerRewardPointsManagementInterface;
use Aheadworks\RewardPoints\Model\TransactionRepository;

class Link extends \Pos\Custommodule\Controller\Adminhtml\Index
{
    protected $customerRepository;
    protected $orderRepository;
    protected $rewardPointsConfig;
    protected $rewardPointsDateTime;
    protected $transactionService;
    protected $transactionRepository;
    protected $customerRewardPointsService;
    protected $scopeConfig;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Framework\App\State $appState,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        RewardPointsConfig $rewardPointsConfig,
        RewardPointsDateTime $rewardPointsDateTime,
        \Aheadworks\RewardPoints\Api\TransactionManagementInterface $transactionService,
        TransactionRepository $transactionRepository,
        CustomerRewardPointsManagementInterface $customerRewardPointsService,
        ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context,$resource,$appState,$logger,$resultPageFactory);
        $this->customerRepository = $customerRepository;
        $this->orderRepository = $orderRepository;
        $this->rewardPointsConfig = $rewardPointsConfig;
        $this->rewardPointsDateTime = $rewardPointsDateTime;
        $this->transactionService = $transactionService;
        $this->transactionRepository = $transactionRepository;
        $this->customerRewardPointsService = $customerRewardPointsService;
        $this->scopeConfig = $scopeConfig;
    }

    public function execute()
    {
        $responseData = array();
        try {
            $orderId = $this->getRequest()->getParam('real_order_number', false);
            $orderIncrementId = $this->getRequest()->getParam('order_number', false);
            $customerId = $this->getRequest()->getParam('customer_id', false);
            $rewardsAmount = $this->getRequest()->getParam('rewards_amount', 0);

            if($orderId && $customerId) {
                $order = $this->orderRepository->get($orderId);
                $customer = $this->customerRepository->getById($customerId);
                $websiteId = $order->getStoreId();

                if($order->getCustomerId() > 0 && !$order->getCustomerIsGuest()) {
                    throw new LocalizedException(new Phrase('This order is already assigned to customer'));
                }

                $groupId = $this->scopeConfig->getValue(
                    'customer/create_account/default_group',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                    $websiteId
                );
                $connection = $this->_resource->getConnection();
                $name = $connection->quote($customer->getFirstname().' '.$customer->getLastname());
                $fname = $connection->quote($customer->getFirstname());
                $lname = $connection->quote($customer->getLastname());
                $sql = "UPDATE sales_order SET customer_id = {$customerId}, customer_is_guest = 0, customer_group_id = {$groupId}, customer_firstname = {$fname}, customer_lastname = {$lname} where entity_id = {$orderId};";
                $connection->query($sql);
                $sql = "UPDATE sales_order_grid SET customer_id = {$customerId}, customer_name = {$name}, customer_group = {$groupId} where entity_id = {$orderId};";
                $connection->query($sql);

                if($rewardsAmount > 0) {
                    $expirationDate = $this->getExpirationDate($websiteId);
                    $result = $this->transactionService->createTransaction(
                        $customer,
                        $rewardsAmount,
                        $expirationDate,
                        $orderIncrementId,
                        null,
                        null,
                        $websiteId,
                        null
                    );

                    $pointsBalance = $this->customerRewardPointsService->getCustomerRewardPointsBalance($customerId);

                    $transaction = $this->transactionRepository->getById($result->getTransactionId());
                    $transaction->setCurrentBalance($pointsBalance);
                    $this->transactionRepository->save($transaction);
                }
                $responseData = array(
                    'status' => 'done',
                    'message'    => 'Order Transferred'
                );
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
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

    private function getExpirationDate($websiteId = null)
    {
        $expireInDays = $this->rewardPointsConfig->getCalculationExpireRewardPoints($websiteId);

        if ($expireInDays == 0) {
            return null;
        }
        return $this->rewardPointsDateTime->getExpirationDate($expireInDays, false);
    }
}
