<?php
namespace Magento\Sales\Api\Data;

/**
 * Extension class for @see \Magento\Sales\Api\Data\ShipmentInterface
 */
class ShipmentExtension extends \Magento\Framework\Api\AbstractSimpleObject implements ShipmentExtensionInterface
{
    /**
     * @return string|null
     */
    public function getExtShipmentId()
    {
        return $this->_get('ext_shipment_id');
    }

    /**
     * @param string $extShipmentId
     * @return $this
     */
    public function setExtShipmentId($extShipmentId)
    {
        $this->setData('ext_shipment_id', $extShipmentId);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getExtReturnShipmentId()
    {
        return $this->_get('ext_return_shipment_id');
    }

    /**
     * @param string $extReturnShipmentId
     * @return $this
     */
    public function setExtReturnShipmentId($extReturnShipmentId)
    {
        $this->setData('ext_return_shipment_id', $extReturnShipmentId);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getExtLocationId()
    {
        return $this->_get('ext_location_id');
    }

    /**
     * @param string $extLocationId
     * @return $this
     */
    public function setExtLocationId($extLocationId)
    {
        $this->setData('ext_location_id', $extLocationId);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getExtTrackingUrl()
    {
        return $this->_get('ext_tracking_url');
    }

    /**
     * @param string $extTrackingUrl
     * @return $this
     */
    public function setExtTrackingUrl($extTrackingUrl)
    {
        $this->setData('ext_tracking_url', $extTrackingUrl);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getExtTrackingReference()
    {
        return $this->_get('ext_tracking_reference');
    }

    /**
     * @param string $extTrackingReference
     * @return $this
     */
    public function setExtTrackingReference($extTrackingReference)
    {
        $this->setData('ext_tracking_reference', $extTrackingReference);
        return $this;
    }
}
