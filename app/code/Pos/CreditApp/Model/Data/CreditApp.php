<?php


namespace Pos\CreditApp\Model\Data;

use Pos\CreditApp\Api\Data\CreditAppInterface;

class CreditApp extends \Magento\Framework\Api\AbstractExtensibleObject implements CreditAppInterface
{

    /**
     * Get creditapp_id
     * @return string|null
     */
    public function getCreditappId()
    {
        return $this->_get(self::CREDITAPP_ID);
    }

    /**
     * Set creditapp_id
     * @param string $creditappId
     * @return \Pos\CreditApp\Api\Data\CreditAppInterface
     */
    public function setCreditappId($creditappId)
    {
        return $this->setData(self::CREDITAPP_ID, $creditappId);
    }

    /**
     * Get id
     * @return string|null
     */
    public function getId()
    {
        return $this->_get(self::ID);
    }

    /**
     * Set id
     * @param string $id
     * @return \Pos\CreditApp\Api\Data\CreditAppInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Pos\CreditApp\Api\Data\CreditAppExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Pos\CreditApp\Api\Data\CreditAppExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Pos\CreditApp\Api\Data\CreditAppExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get federal
     * @return string|null
     */
    public function getFederal()
    {
        return $this->_get(self::FEDERAL);
    }

    /**
     * Set federal
     * @param string $federal
     * @return \Pos\CreditApp\Api\Data\CreditAppInterface
     */
    public function setFederal($federal)
    {
        return $this->setData(self::FEDERAL, $federal);
    }
}
