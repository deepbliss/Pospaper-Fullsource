<?php

namespace Magecomp\Deleteorder\Controller\Adminhtml\Deleteorder;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;

use Magecomp\Deleteorder\Model\OrderFactory;

class MassDelete extends \Magento\Sales\Controller\Adminhtml\Order\AbstractMassAction
{
    /**
     * @var OrderFactory
     */
    protected $_modelOrderFactory;

    public function __construct(Context $context, Filter $filter, CollectionFactory $collectionFactory, 
        OrderFactory $modelOrderFactory)
    {
		parent::__construct($context, $filter);
		$this->collectionFactory = $collectionFactory;
        $this->_modelOrderFactory = $modelOrderFactory;
    }
	
    protected  function massAction(AbstractCollection $collection)
    {
		$orderIds = array();

		foreach ($collection->getItems() as $order) 
		{
			$orderIds[] = $order->getId();
        }
		 
        if (empty($orderIds)) {
            $this->messageManager->addError(__('There is no order to process'));
            $this->_redirect('sales/order/index');
            return;
        }
        try {
            $count = 0;
            foreach($orderIds as $orderId)
			{
                if($this->_modelOrderFactory->create()->deleteOrder([$orderId])) $count++;
            }
            $this->messageManager->addSuccess(
                __('%1 order(s) successfully deleted', $count)
            );
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect('sales/order/index');
    }
}
