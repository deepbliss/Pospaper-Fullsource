<?php
namespace CommerceExtensions\OrderImportExport\Model\Data\Import;

use Magento\Quote\Model\Quote as QuoteEntity;

class QuoteValidator extends \Magento\Quote\Model\QuoteValidator {
    
    public function validateBeforeSubmit(QuoteEntity $quote)
    {
		/*
		$items = $quote->getAllItems();
		if (count($items) == 0) {
            throw new \Magento\Framework\Exception\LocalizedException(__('CE - Please specify order items.'));
        }
		*/
        if (!$quote->isVirtual()) {
            if ($quote->getShippingAddress()->validate() !== true) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __(
                        'Please check the shipping address information. %1',
                        implode(' ', $quote->getShippingAddress()->validate())
                    )
                );
            }
            $method = $quote->getShippingAddress()->getShippingMethod();
            #$rate = $quote->getShippingAddress()->getShippingRateByCode($method);
            if (!$quote->isVirtual() && (!$method)) {
                throw new \Magento\Framework\Exception\LocalizedException(__('Please specify a shipping method.'));
            }
        }
        if ($quote->getBillingAddress()->validate() !== true) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __(
                    'Please check the billing address information. %1',
                    implode(' ', $quote->getBillingAddress()->validate())
                )
            );
        }
        if (!$quote->getPayment()->getMethod()) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Please select a valid payment method.'));
        }
        return $this;
    }
}