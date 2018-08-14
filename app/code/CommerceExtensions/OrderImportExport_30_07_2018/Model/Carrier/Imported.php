<?php
/**
 * Imported.php
 * CommerceExtensions
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.commerceextensions.com/LICENSE-M1.txt
 *

 * @category   Orders
 * @package    Imported
 * @copyright  Copyright (c) 2003-2009 CommerceExtensions @ InterSEC Solutions LLC. (http://www.commerceextensions.com)
 * @license    http://www.commerceextensions.com/LICENSE-M1.txt
 */ 
namespace CommerceExtensions\OrderImportExport\Model\Carrier;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Rate\Result;

class Imported extends \Magento\Shipping\Model\Carrier\AbstractCarrier implements
    \Magento\Shipping\Model\Carrier\CarrierInterface
{

    protected $_code = 'imported';

    public static $methodQueue = array();
 /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        array $data = []
    ) {
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }
	public function collectRates(RateRequest $request)
    {
        #if (!$this->getConfigFlag('active')) {
            #return false;
        #}
 
        /** @var \Magento\Shipping\Model\Rate\Result $result */
        $result = $this->_rateResultFactory->create();
 
        /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
        $method = $this->_rateMethodFactory->create();
 
        $method->setCarrier('imported');
        $method->setCarrierTitle($this->getConfigData('title'));
 
        $method->setMethod('imported');
        $method->setMethodTitle($this->getConfigData('name'));
 
        /*you can fetch shipping price from different sources over some APIs, we used price from config.xml - xml node price*/
        #$amount = $this->getConfigData('price');
 
        $method->setPrice(0);
        $method->setCost(0);
 
        $result->append($method);
 
        return $result;
    }
	/*
    public function collectRates(\Magento\Shipping\Model\Rate\Request $request)
    {
        $result = Mage::getModel('shipping/rate_result');

        $method = Mage::getModel('shipping/rate_result_method');

        $method->setCarrier('imported');
        $method->setCarrierTitle('Imported');

        $method->setMethod('imported');
        $method->setMethodTitle(array_shift(self::$methodQueue));

        $method->setPrice(0);
        $method->setCost(0);

        $result->append($method);

        return $result;
    }
	*/
    public function getAllowedMethods()
    {
        #return array('imported'=>'imported');
        return ['imported' => $this->getConfigData('name')];
    }
}