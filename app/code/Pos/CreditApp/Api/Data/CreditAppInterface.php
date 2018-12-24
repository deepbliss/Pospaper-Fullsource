<?php


namespace Pos\CreditApp\Api\Data;

interface CreditAppInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const ID = 'id';
    const CREDITAPP_ID = 'creditapp_id';
    const FEDERAL = 'federal';

    /**
     * Get creditapp_id
     * @return string|null
     */
    public function getCreditappId();

    /**
     * Set creditapp_id
     * @param string $creditappId
     * @return \Pos\CreditApp\Api\Data\CreditAppInterface
     */
    public function setCreditappId($creditappId);

    /**
     * Get id
     * @return string|null
     */
    public function getId();

    /**
     * Set id
     * @param string $id
     * @return \Pos\CreditApp\Api\Data\CreditAppInterface
     */
    public function setId($id);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Pos\CreditApp\Api\Data\CreditAppExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Pos\CreditApp\Api\Data\CreditAppExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Pos\CreditApp\Api\Data\CreditAppExtensionInterface $extensionAttributes
    );

    /**
     * Get federal
     * @return string|null
     */
    public function getFederal();

    /**
     * Set federal
     * @param string $federal
     * @return \Pos\CreditApp\Api\Data\CreditAppInterface
     */
    public function setFederal($federal);
}
