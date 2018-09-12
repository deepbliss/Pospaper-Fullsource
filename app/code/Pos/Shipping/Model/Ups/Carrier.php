<?php

namespace Pos\Shipping\Model\Ups;

use Magento\Quote\Model\Quote\Address\RateRequest;

class Carrier extends \Magento\Ups\Model\Carrier
{
    const ZIPCODE_API_URL = 'https://www.zipcodeapi.com/rest/';
    const API_KEY = 'RcyOGXvHQ4MmvfhIi5zsc9g6iwWgiip29UMm70en8hHpz6oUIscsV9iXE62rpm3K';

    /**
     * Prepare and set request to this instance
     *
     * @param RateRequest $request
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function setRequest(RateRequest $request)
    {
        $this->_request = $request;
        $rowRequest = new \Magento\Framework\DataObject();

        if ($request->getLimitMethod()) {
            $rowRequest->setAction($this->configHelper->getCode('action', 'single'));
            $rowRequest->setProduct($request->getLimitMethod());
        } else {
            $rowRequest->setAction($this->configHelper->getCode('action', 'all'));
            $rowRequest->setProduct('GND' . $this->getConfigData('dest_type'));
        }

        if ($request->getUpsPickup()) {
            $pickup = $request->getUpsPickup();
        } else {
            $pickup = $this->getConfigData('pickup');
        }
        $rowRequest->setPickup($this->configHelper->getCode('pickup', $pickup));

        if ($request->getUpsContainer()) {
            $container = $request->getUpsContainer();
        } else {
            $container = $this->getConfigData('container');
        }
        $rowRequest->setContainer($this->configHelper->getCode('container', $container));

        if ($request->getUpsDestType()) {
            $destType = $request->getUpsDestType();
        } else {
            $destType = $this->getConfigData('dest_type');
        }
        $rowRequest->setDestType($this->configHelper->getCode('dest_type', $destType));

        if ($request->getOrigCountry()) {
            $origCountry = $request->getOrigCountry();
        } else {
            $origCountry = $this->_scopeConfig->getValue(
                \Magento\Sales\Model\Order\Shipment::XML_PATH_STORE_COUNTRY_ID,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $request->getStoreId()
            );
        }

        $rowRequest->setOrigCountry($this->_countryFactory->create()->load($origCountry)->getData('iso2_code'));

        if ($request->getOrigRegionCode()) {
            $origRegionCode = $request->getOrigRegionCode();
        } else {
            $origRegionCode = $this->_scopeConfig->getValue(
                \Magento\Sales\Model\Order\Shipment::XML_PATH_STORE_REGION_ID,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $request->getStoreId()
            );
        }
        if (is_numeric($origRegionCode)) {
            $origRegionCode = $this->_regionFactory->create()->load($origRegionCode)->getCode();
        }
        $rowRequest->setOrigRegionCode($origRegionCode);

        if ($request->getOrigPostcode()) {
            $rowRequest->setOrigPostal($request->getOrigPostcode());
        } else {
            $rowRequest->setOrigPostal(
                $this->_scopeConfig->getValue(
                    \Magento\Sales\Model\Order\Shipment::XML_PATH_STORE_ZIP,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                    $request->getStoreId()
                )
            );
        }

        if ($request->getOrigCity()) {
            $rowRequest->setOrigCity($request->getOrigCity());
        } else {
            $rowRequest->setOrigCity(
                $this->_scopeConfig->getValue(
                    \Magento\Sales\Model\Order\Shipment::XML_PATH_STORE_CITY,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                    $request->getStoreId()
                )
            );
        }

        if ($request->getDestCountryId()) {
            $destCountry = $request->getDestCountryId();
        } else {
            $destCountry = self::USA_COUNTRY_ID;
        }

        // Find state from zip code
        $destRegion = null;
        $destCity = null;
        try {
            if($request->getDestPostcode()) {
                $location = $this->getOriginFromZipcode($request->getDestPostcode());
                if(!empty($location)) {
                    if(isset($location['state']) && $location['state'] != $request->getDestRegionCode()) {
                        $destRegion = $location['state'];
                        $destCity = $location['city'];
                    }
                }
            }

        } catch (Exception $e) {
            $debugData['result'] = ['error' => $e->getMessage(), 'code' => $e->getCode()];
            $this->_debug($debugData);
        }

        //for UPS, puero rico state for US will assume as puerto rico country
        if ($destCountry == self::USA_COUNTRY_ID && ($request->getDestPostcode() == '00912' ||
                $request->getDestRegionCode() == self::PUERTORICO_COUNTRY_ID || $destRegion == self::PUERTORICO_COUNTRY_ID)
        ) {
            $destCountry = self::PUERTORICO_COUNTRY_ID;
        }

        // For UPS, Guam state of the USA will be represented by Guam country
        if ($destCountry == self::USA_COUNTRY_ID && ($request->getDestRegionCode() == self::GUAM_REGION_CODE || $destRegion == self::GUAM_REGION_CODE)) {
            $destCountry = self::GUAM_COUNTRY_ID;
        }

        $country = $this->_countryFactory->create()->load($destCountry);
        $rowRequest->setDestCountry($country->getData('iso2_code') ?: $destCountry);

        $rowRequest->setDestRegionCode($request->getDestRegionCode());
        if($destRegion) {
            $rowRequest->setDestRegionCode($destRegion);
        }

        if ($request->getDestPostcode()) {
            $rowRequest->setDestPostal($request->getDestPostcode());
        }

        // Get closest origin from product origin_zipcode
        try {
            $productOriginZipcodes = '';
            if ($request->getAllItems()) {
                foreach ($request->getAllItems() as $item) {
                    if ($item->getProduct()->isVirtual() || $item->getParentItem()) {
                        continue;
                    }

                    $productOriginZipcodes .= '|'.preg_replace('/[ ,]+/', '|', trim($item->getProduct()->getOriginZipcode()));
                }
            }

            $productOriginZipcodes = trim($productOriginZipcodes,'|');
            $productOriginZipcodes = explode('|',$productOriginZipcodes);
            $productOriginZipcodes = array_unique($productOriginZipcodes);
            if(!empty($productOriginZipcodes) && $request->getDestPostcode()) {
                $productOriginZipcodes = implode(',',$productOriginZipcodes);
                $closestOriginZipcode = $this->getNearestOrigin($request->getDestPostcode(),$productOriginZipcodes);
                $nearestOrigin = $this->getOriginFromZipcode($closestOriginZipcode);
                if(!empty($nearestOrigin)) {
                    $rowRequest->setOrigCountry('US');
                    $rowRequest->setOrigRegionCode($nearestOrigin['state']);
                    $rowRequest->setOrigPostal($closestOriginZipcode);
                    $rowRequest->setOrigCity($nearestOrigin['city']);
                }
            }
        } catch (Exception $e) {
            $debugData['result'] = ['error' => $e->getMessage(), 'code' => $e->getCode()];
            $this->_debug($debugData);
        }


        $weight = $this->getTotalNumOfBoxes($request->getPackageWeight());

        $weight = $this->_getCorrectWeight($weight);

        $rowRequest->setWeight($weight);
        if ($request->getFreeMethodWeight() != $request->getPackageWeight()) {
            $rowRequest->setFreeMethodWeight($request->getFreeMethodWeight());
        }

        $rowRequest->setValue($request->getPackageValue());
        $rowRequest->setValueWithDiscount($request->getPackageValueWithDiscount());

        if ($request->getUpsUnitMeasure()) {
            $unit = $request->getUpsUnitMeasure();
        } else {
            $unit = $this->getConfigData('unit_of_measure');
        }
        $rowRequest->setUnitMeasure($unit);
        $rowRequest->setIsReturn($request->getIsReturn());
        $rowRequest->setBaseSubtotalInclTax($request->getBaseSubtotalInclTax());

        $this->_rawRequest = $rowRequest;

        return $this;
    }

    /**
     * Prepare shipping rate result based on response
     *
     * @param mixed $xmlResponse
     * @return Result
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function _parseXmlResponse($xmlResponse)
    {
        $costArr = [];
        $priceArr = [];
        if (strlen(trim($xmlResponse)) > 0) {
            $xml = new \Magento\Framework\Simplexml\Config();
            $xml->loadString($xmlResponse);
            $arr = $xml->getXpath("//RatingServiceSelectionResponse/Response/ResponseStatusCode/text()");
            $success = (int)$arr[0];
            if ($success === 1) {
                $arr = $xml->getXpath("//RatingServiceSelectionResponse/RatedShipment");
                $allowedMethods = explode(",", $this->getConfigData('allowed_methods'));

                // Negotiated rates
                $negotiatedArr = $xml->getXpath("//RatingServiceSelectionResponse/RatedShipment/NegotiatedRates");
                $negotiatedActive = $this->getConfigFlag(
                        'negotiated_active'
                    ) && $this->getConfigData(
                        'shipper_number'
                    ) && !empty($negotiatedArr);

                $allowedCurrencies = $this->_currencyFactory->create()->getConfigAllowCurrencies();
                foreach ($arr as $shipElement) {
                    $code = (string)$shipElement->Service->Code;
                    if (in_array($code, $allowedMethods)) {
                        if ($negotiatedActive) {
                            $cost = $shipElement->NegotiatedRates->NetSummaryCharges->GrandTotal->MonetaryValue;
                        } else {
                            $cost = $shipElement->TotalCharges->MonetaryValue;
                        }

                        //convert price with Origin country currency code to base currency code
                        $successConversion = true;
                        $responseCurrencyCode = $this->mapCurrencyCode(
                            (string)$shipElement->TotalCharges->CurrencyCode
                        );
                        if ($responseCurrencyCode) {
                            if (in_array($responseCurrencyCode, $allowedCurrencies)) {
                                $cost = (double)$cost * $this->_getBaseCurrencyRate($responseCurrencyCode);
                            } else {
                                $errorTitle = __(
                                    'We can\'t convert a rate from "%1-%2".',
                                    $responseCurrencyCode,
                                    $this->_request->getPackageCurrency()->getCode()
                                );
                                $error = $this->_rateErrorFactory->create();
                                $error->setCarrier('ups');
                                $error->setCarrierTitle($this->getConfigData('title'));
                                $error->setErrorMessage($errorTitle);
                                $successConversion = false;
                            }
                        }

                        if ($successConversion) {
                            $costArr[$code] = $cost;
                            $priceArr[$code] = $this->getMethodPrice(floatval($cost), $code);
                        }
                    }
                }
            } else {
                $arr = $xml->getXpath("//RatingServiceSelectionResponse/Response/Error/ErrorDescription/text()");
                $errorTitle = (string)$arr[0][0];
                $error = $this->_rateErrorFactory->create();
                $error->setCarrier('ups');
                $error->setCarrierTitle($this->getConfigData('title'));
                $error->setErrorMessage($this->getConfigData('specificerrmsg'));
            }
        }

        $result = $this->_rateFactory->create();

        if (empty($priceArr)) {
            $error = $this->_rateErrorFactory->create();
            $error->setCarrier('ups');
            $error->setCarrierTitle($this->getConfigData('title'));
            if (!isset($errorTitle)) {
                $errorTitle = __('Cannot retrieve shipping rates');
            }
            $error->setErrorMessage($this->getConfigData('specificerrmsg'));
            $result->append($error);
        } else {
            foreach ($priceArr as $method => $price) {
                $surCharge = $this->_scopeConfig->getValue(
                    'shipping/pospaper/ups_surcharge',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                );
                if($surCharge > 0) {
                    $price = $price + ($price/100*$surCharge);
                }
                $rate = $this->_rateMethodFactory->create();
                $rate->setCarrier('ups');
                $rate->setCarrierTitle($this->getConfigData('title'));
                $rate->setMethod($method);
                $methodArr = $this->getShipmentByCode($method);
                $rate->setMethodTitle($methodArr);
                $rate->setCost($costArr[$method]);
                $rate->setPrice($price);
                $result->append($rate);
            }
        }

        return $result;
    }

    /**
     * Map currency alias to currency code
     *
     * @param string $code
     * @return string
     */
    private function mapCurrencyCode($code)
    {
        $currencyMapping = [
            'RMB' => 'CNY',
            'CNH' => 'CNY'
        ];

        return isset($currencyMapping[$code]) ? $currencyMapping[$code] : $code;
    }

    private function getNearestOrigin($postcode,$postcodes)
    {
        $zip = null;
        $apiKey = self::API_KEY;
        $url = self::ZIPCODE_API_URL.$apiKey.'/multi-distance.json/'.$postcode.'/'.$postcodes.'/mile';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response  = curl_exec($ch);
        $object = json_decode($response,true);
        if(is_array($object) && isset($object['distances']) && count($object['distances']) > 0) {
            $zip = array_search(min($object['distances']),$object['distances']);
        }

        return $zip;
    }

    private function getOriginFromZipcode($postcode)
    {
        $origin = array();
        $apiKey = self::API_KEY;
        $url = self::ZIPCODE_API_URL.$apiKey.'/info.json/'.$postcode.'/degrees';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response  = curl_exec($ch);
        $object = json_decode($response,true);
        if(is_array($object) && isset($object['city']) && isset($object['state'])) {
            $origin['state'] = $object['state'];
            $origin['city'] = $object['city'];
        }

        return $origin;
    }
}