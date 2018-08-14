<?php
namespace Magento\Quote\Api\Data;

/**
 * Extension class for @see \Magento\Quote\Api\Data\PaymentInterface
 */
class PaymentExtension extends \Magento\Framework\Api\AbstractSimpleObject implements PaymentExtensionInterface
{
    /**
     * @return string[]|null
     */
    public function getAgreementIds()
    {
        return $this->_get('agreement_ids');
    }

    /**
     * @param string[] $agreementIds
     * @return $this
     */
    public function setAgreementIds($agreementIds)
    {
        $this->setData('agreement_ids', $agreementIds);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTokenbaseId()
    {
        return $this->_get('tokenbase_id');
    }

    /**
     * @param string $tokenbaseId
     * @return $this
     */
    public function setTokenbaseId($tokenbaseId)
    {
        $this->setData('tokenbase_id', $tokenbaseId);
        return $this;
    }
}
