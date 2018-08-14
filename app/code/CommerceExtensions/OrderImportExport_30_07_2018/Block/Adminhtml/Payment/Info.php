<?php

namespace CommerceExtensions\OrderImportExport\Block\Adminhtml\Payment;

class Info extends \Magento\Payment\Block\Info
{
    protected $orderFactory;
	
	protected $jsonHelper;

    #private $order = NULL;

    #protected $_template = 'CommerceExtensions_OrderImportExport::order/payment/info.phtml';
    #protected $_template = 'Magento_Payment::info/default.phtml';
	
    public function __construct(
        \Magento\Sales\Model\OrderFactory $orderFactory,
		\Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        $this->orderFactory = $orderFactory;
		$this->jsonHelper = $jsonHelper;

        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('CommerceExtensions_OrderImportExport::order/payment/info.phtml');
    }
	
	public function decodeData($dataToDecode)
    {
		return $this->jsonHelper->jsonDecode($dataToDecode);
	}
    /**
     * @return \Magento\Sales\Model\Order
     */
	 /*
    public function getOrder()
    {
        if (!is_null($this->order)) {
            return $this->order;
        }

        $orderId = $this->getInfo()->getAdditionalInformation('order_id');
        if (empty($orderId)) {
            return null;
        }

        $this->order = $this->orderFactory->create();
        $this->order->load($orderId);

        return $this->order;
    }
	*/

    public function getPaymentMethod()
    {
        return (string)$this->getInfo()->getAdditionalInformation('assign_payment_method');
    }
	/*
    public function getPaymentTitle()
    {
        return (string)$this->getInfo()->getAdditionalInformation('method_title');
    }
	*/
	
    public function getTransactions()
    {
		return $this->getInfo()->getAdditionalInformation('assign_transactions');
		/*
        $transactions = $this->getInfo()->getAdditionalInformation('transactions');
        return is_array($transactions) ? $transactions : array();
		*/
    }
}