<?php

namespace CommerceExtensions\OrderImportExport\Model\Payment\Method;

class Fake extends \Magento\Payment\Model\Method\AbstractMethod
{
    /**
     * Payment code name
     *
     * @var string
     */
    protected $_code = 'imported_placeholder';


    protected $_title;
    /**
     * @var bool
     */
    protected $_canOrder = true;
	
    /**
     * @var bool
     */
    protected $_canAuthorize = false;

    /**
     * Can be used in admin
     *
     * @var bool
     */
    protected $_canUseInternal = true;

    /**
     * Can be used in regular checkout
     *
     * @return bool
     */
    protected $_canUseCheckout = false;
	
	protected $_canUseForMultishipping = false;
	
	
    protected $_infoBlockType = 'CommerceExtensions\OrderImportExport\Block\Adminhtml\Payment\Info';

    public function isActive($storeId = null)
    {
        return true;
    }

    /**
     * Check whether method is available
     *
     * @param \Magento\Quote\Api\Data\CartInterface|\Magento\Quote\Model\Quote|null $quote
     * @return bool
     */
    public function isAvailable(\Magento\Quote\Api\Data\CartInterface $quote = null)
    {
        return true;
    }

    /**
     * @param string $country
     *
     * @return bool
     */
    public function canUseForCountry($country)
    {
        return true;
    }
	
    public function getCode()
    {
        return $this->_code;
    }

    /**
     * @return string|null
     */
    #public function getTitle()
    #{
      	#$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		#$_logger = $objectManager->create('Psr\Log\LoggerInterface');
		#$_logger->log(100,"PAYMENT METHOD TITLE: " .$this->getData('title'));
		#$_logger->log(100,print_r($this->getData(),true));
        #return $this->getData('title');
    #}
	
    public function assignData(\Magento\Framework\DataObject $data)
    {
		if(isset($data->getData()['additional_information'])) {
			$data = $data->getData()['additional_information'];
			
			$details = array(
				'assign_payment_method'    => $data['assign_payment_method'],
				'assign_transactions'      => $data['assign_transactions'],
			);
			$this->getInfoInstance()->setAdditionalInformation($details);
			return $this;
		}
    }
}