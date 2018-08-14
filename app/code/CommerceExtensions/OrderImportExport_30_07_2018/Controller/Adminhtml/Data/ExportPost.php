<?php
/**
 * Copyright © 2015 CommerceExtensions. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace CommerceExtensions\OrderImportExport\Controller\Adminhtml\Data;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

class ExportPost extends \CommerceExtensions\OrderImportExport\Controller\Adminhtml\Data
{
    const MULTI_DELIMITER = ' , ';
    protected $_store;
    protected $_stores;
    protected $_websites;
    protected $_customerModel;
    protected $_customerAddressModel;
    protected $_newsletterModel;
    protected $_fields;
    protected $_customerGroups = null;
    protected $_attributes = array();
    protected $_disabledAttributes = ['increment_id', 'entity_id', 'website_id', 'customer_email', 'customer_group_id', 'email'];
    protected $_disabledAddressAttributes = ['entity_type_id', 'increment_id', 'parent_id', 'attribute_set_id', 'entity_id', 'region_id', 'default_billing', 'default_shipping'];
    /**
     * Export action from import/export categories
     *
     * @return ResponseInterface
     */
	
    protected function _getCustomerGroupCode($customerGroupId)
    {
        if (is_null($this->_customerGroups)) {
			
			$customergroupscollection = $this->_objectManager->create('Magento\Customer\Model\ResourceModel\Group\Collection');
            foreach ($customergroupscollection as $group) {
                $this->_customerGroups[$group->getId()] = $group->getData('customer_group_code');
            }
        }

        if (isset($this->_customerGroups[$customerGroupId])) {
            return $this->_customerGroups[$customerGroupId];
        } else {
            return null;
        }
    }
	
    public function getFields()
    {
        if (!$this->_fields) {
            #$this->_fields = Mage::getConfig()->getFieldset('customer_dataflow', 'admin');
			$this->_fields = $this->_objectManager->get('Magento\Framework\DataObject\Copy\Config')->getFieldset('customer_address');
			#$this->_fields = $this->_objectManager->get('Magento\Framework\DataObject\Copy\Config')->getFieldset('order_address');
        }
        return $this->_fields;
    }
	
    public function getStore()
    {
        if (is_null($this->_store)) {
            try {
                #$store = Mage::app()->getStore(0);
				$store = $this->_objectManager->get('Magento\Store\Model\StoreManager')->getStore();
            }
            catch (\Exception $e) {
                #$this->addException(Mage::helper('catalog')->__('Invalid store specified'), Varien_Convert_Exception::FATAL);
                throw new \Magento\Framework\Exception\LocalizedException(__($e->getMessage()), $e);
            }
            $this->_store = $store;
        }
        return $this->_store;
    }
	
    public function getStoreById($storeId)
    {
        if (is_null($this->_stores)) {
            $this->_stores = $this->_objectManager->get('Magento\Store\Model\StoreManager')->getStores(true);
        }
        if (isset($this->_stores[$storeId])) {
            return $this->_stores[$storeId];
        }
        return false;
    }

	
    public function getWebsiteById($websiteId)
    {
        if (is_null($this->_websites)) {
            #$this->_websites = Mage::app()->getWebsites(true);
			$this->_websites = $this->_objectManager->get('Magento\Store\Model\StoreManager')->getWebsites(true);
        }
        if (isset($this->_websites[$websiteId])) {
            return $this->_websites[$websiteId];
        }
        return false;
    }
	
    public function getCustomerModel()
    {
        if (is_null($this->_customerModel)) {
            $this->_customerModel = $this->_objectManager->create('Magento\Customer\Model\Customer');
        }
        return $this->_customerModel;
    }
	
    public function getCustomerAddressModel()
    {
        if (is_null($this->_customerAddressModel)) {
            $this->_customerAddressModel = $this->_objectManager->create('Magento\Customer\Model\Address');
        }
        return $this->_customerAddressModel;
    }
	
    public function getNewsletterModel()
    {
        if (is_null($this->_newsletterModel)) {
            $this->_newsletterModel = $this->_objectManager->create('Magento\Newsletter\Model\Subscriber');
        }
        return $this->_newsletterModel;
    }
	
    public function getAttribute($code)
    {
        if (!isset($this->_attributes[$code])) {
            $this->_attributes[$code] = $this->getCustomerModel()->getResource()->getAttribute($code);
        }
        return $this->_attributes[$code];
    }
	
	public function applyPaymentFilter($orders,$payment_method)
    {
        $orders->getSelect()->join(
                                    array('p' => $orders->getResource()->getTable('sales/order_payment')),'p.parent_id = main_table.entity_id',array()
                                   );

        $orders->addFieldToFilter('method',$payment_method);
        return $orders;
    }
	
    public function isValidPayment($payment_method)
    {
        if( ($payment_method !='') || ($payment_method !='All') )
            return true;
         else
             return false;
    }
	
    public function execute()
    {
        /** start csv content and set template */
		$params = $this->getRequest()->getParams();
		$_resource = $this->_objectManager->create('Magento\Framework\App\ResourceConnection');
		$connection = $_resource->getConnection();
		$sales_order_item = $_resource->getTableName('sales_order_item');
		$payment_method = "";
		
		if($params['export_delimiter'] != "") {
			$delimiter = $params['export_delimiter'];
		} else {
			$delimiter = ",";
		}
		if($params['export_enclose'] != "") {
			$enclose = $params['export_enclose'];
		} else {
			$enclose = "\"";
		}
		
        $systemFields = array();
        foreach ($this->getFields() as $code=>$node) {
            #if ($node->is('system')) {
                $systemFields[] = $code;
            #}
        }
		
		/* BUILD OUT COLUMNS NOT IN DEFAULT ATTRIBUTES */
		$template = ''.$enclose.'{{order_id}}'.$enclose.''.$delimiter.''.$enclose.'{{website}}'.$enclose.''.$delimiter.''.$enclose.'{{email}}'.$enclose.''.$delimiter.''.$enclose.'{{invoice_id}}'.$enclose.''.$delimiter.''.$enclose.'{{is_guest}}'.$enclose.''.$delimiter.''.$enclose.'{{payment_method}}'.$enclose.''.$delimiter.''.$enclose.'{{products_ordered}}'.$enclose.''.$delimiter.''.$enclose.'{{order_status}}'.$enclose.''.$delimiter.''.$enclose.'{{tracking_date}}'.$enclose.''.$delimiter.''.$enclose.'{{tracking_ship_method}}'.$enclose.''.$delimiter.''.$enclose.'{{tracking_codes}}'.$enclose.''.$delimiter.''.$enclose.'{{additional_comments}}'.$enclose.''.$delimiter.''.$enclose.'{{tax_percent}}'.$enclose.''.$delimiter.'';
		
		$attributesArray = array('order_id' => 'order_id', 'website' => 'website', 'email' => 'email', 'invoice_id' => 'invoice_id', 'is_guest' => 'is_guest', 'payment_method' => 'payment_method', 'products_ordered' => 'products_ordered', 'order_status' => 'order_status', 'tracking_date' => 'tracking_date', 'tracking_ship_method' => 'tracking_ship_method', 'tracking_codes' => 'tracking_codes', 'additional_comments' => 'additional_comments', 'tax_percent' => 'tax_percent');
		/* BUILD OUT COLUMNS IN DEFAULT ATTRIBUTES */
		#$order_attributes = $this->_objectManager->get('Magento\Sales\Model\ResourceModel\Attribute');
		
		$order_attributes = $this->_objectManager->get('Magento\Framework\DataObject\Copy\Config')->getFieldset('sales_convert_order');
		$customer_attributes = $this->_objectManager->get('Magento\Customer\Model\Customer')->getAttributes();
		$customer_address_attributes = $this->_objectManager->get('Magento\Customer\Model\Address')->getAttributes();
		
		
        foreach($customer_attributes as $cal=>$val){
			if (!in_array($cal, $this->_disabledAttributes)) {
				$attributesArray[$cal] = $cal;
				$template .= ''.$enclose.'{{'.$cal.'}}'.$enclose.''.$delimiter.'';
			}
		}
		/* PREFIX ADDRESS FOR BILLING */
        foreach($customer_address_attributes as $address_cal=>$address_val){
			if (!in_array($address_cal, $this->_disabledAddressAttributes)) {
			    if($address_cal == "country_id") { $address_cal = "country"; }
				$attributesArray["billing_".$address_cal] = "billing_".$address_cal;
				$template .= ''.$enclose.'{{billing_'.$address_cal.'}}'.$enclose.''.$delimiter.'';
			} 
		}
		/* PREFIX ADDRESS FOR SHIPPING */
        foreach($customer_address_attributes as $address_cal=>$address_val){
			if (!in_array($address_cal, $this->_disabledAddressAttributes)) {
			    if($address_cal == "country_id") { $address_cal = "country"; }
				$attributesArray["shipping_".$address_cal] = "shipping_".$address_cal;
				$template .= ''.$enclose.'{{shipping_'.$address_cal.'}}'.$enclose.''.$delimiter.'';
			} 
		}
		/* ORDER ATTRIBUTES */
        foreach($order_attributes as $cal=>$val){
			if (!in_array($cal, $this->_disabledAttributes)) {
				$attributesArray[$cal] = $cal;
				$template .= ''.$enclose.'{{'.$cal.'}}'.$enclose.''.$delimiter.'';
			}
		}
		
        /** start csv content and set template */
		$headers = new \Magento\Framework\DataObject($attributesArray);
        $content = $headers->toString($template);
        $row = [];
        $content .= "\n";
		if($params['date_from'] != "" && $params['date_to'] != "" ) {
				
			$date_from = $params['date_from']. " 00:00:00";
			$date_to = $params['date_to']. " 23:59:59";
			$ordersscollection = $this->_objectManager->create('Magento\Sales\Model\ResourceModel\Order\Collection')
							->addAttributeToSelect('*')
							->addAttributeToFilter( 'created_at' , array( "from" => $date_from, "to" => $date_to, "datetime" => true ));
			//check if payment method is valid for filter
			//then we would apply filter
			if($this->isValidPayment($payment_method) && $payment_method !="")
			{
				$ordersscollection= $this->applyPaymentFilter($orders, $payment_method);
			}
			$ordersscollection->load();
			
		} else {
        	$ordersscollection = $this->_objectManager->create('Magento\Sales\Model\ResourceModel\Order\Collection');
		}
       	
		foreach ($ordersscollection as $order) {
			#print_r($order->getData());
			
			$customerId = $order->getData('customer_id');
			$store = $this->getStore();
			$storefromorder = $this->_objectManager->get('Magento\Store\Model\StoreManager')->getStore($order->getStoreId());
			
			$row['order_id'] = $order->getData('increment_id');
			$row['website'] = "";
			$row['firstname'] = "";
			$row['middlename'] = "";
			$row['lastname'] = "";
			
			$row['invoice_id'] = "";
			if ($order->hasInvoices()) {
				$invIncrementIDs = array();
				foreach ($order->getInvoiceCollection() as $inv) {
					$row['invoice_id'] = $inv->getIncrementId();
				}
			}
			#print_r($order->getData());
			#exit;
				/* THIS HERE GETS ARE INFORMATION IF ITS A GUEST THAT CHECKOUT AND NOT AN ACTUAL CUSTOMER */
				if ($order->getCustomerIsGuest() || $order->getData('customer_id') == "") {
				
			
					$valueid = $store->getData('website_id');
					$website = $this->getWebsiteById($valueid);
					//print($website);
          			$row['website'] = $website->getCode();
					#$row['website'] = $storefromorder->getCode();
					$row['group_id'] = $this->_getCustomerGroupCode($order->getData('customer_group_id'));
          			$row['is_guest'] = "1";
					
					if(method_exists($order, 'getBillingAddress') && method_exists($order->getBillingAddress(), 'getData')) {
						$row['prefix'] = $order->getBillingAddress()->getData('prefix');
						$row['firstname'] = $order->getBillingAddress()->getData('firstname');
						$row['middlename'] = $order->getBillingAddress()->getData('middlename');
						$row['lastname'] = $order->getBillingAddress()->getData('lastname');	
						$row['suffix'] = $order->getBillingAddress()->getData('suffix');	
						$row['billing_prefix'] = $order->getBillingAddress()->getData('prefix');
						$row['billing_firstname'] = $order->getBillingAddress()->getData('firstname');
						$row['billing_middlename'] = $order->getBillingAddress()->getData('middlename');
						$row['billing_lastname'] = $order->getBillingAddress()->getData('lastname');
						$row['billing_suffix'] = $order->getBillingAddress()->getData('suffix');
						$row['billing_street'] = $order->getBillingAddress()->getData('street');
						#$row['billing_street2'] = $order->getBillingAddress()->getStreet(2);
						#$row['billing_street3'] = $order->getBillingAddress()->getStreet(3);
						#$row['billing_street4'] = $order->getBillingAddress()->getStreet(4);
						$row['billing_city'] = $order->getBillingAddress()->getData('city');
						$row['billing_region'] = $order->getBillingAddress()->getData('region');
						$row['billing_country'] = $order->getBillingAddress()->getData('country_id');
						$row['billing_postcode'] = $order->getBillingAddress()->getData('postcode');
						$row['billing_telephone'] = $order->getBillingAddress()->getData('telephone');
						$row['billing_company'] = $order->getBillingAddress()->getData('company');
						$row['billing_fax'] = $order->getBillingAddress()->getData('fax');
					} else {
						$row['prefix'] = $order->getData('prefix');
						$row['firstname'] = $order->getData('firstname');
						$row['middlename'] = $order->getData('middlename');
						$row['lastname'] = $order->getData('lastname');	
						$row['suffix'] = $order->getData('suffix');	
						$row['billing_prefix'] = $order->getData('prefix');
						$row['billing_firstname'] = $order->getData('firstname');
						$row['billing_middlename'] = $order->getData('middlename');
						$row['billing_lastname'] = $order->getData('lastname');
						$row['billing_suffix'] = $order->getData('suffix');
						$row['billing_street'] = $order->getData('street');
						#$row['billing_street2'] = $order->getStreet(2);
						#$row['billing_street3'] = $order->getStreet(3);
						#$row['billing_street4'] = $order->getStreet(4);
						$row['billing_city'] = $order->getData('city');
						$row['billing_region'] = $order->getData('region');
						$row['billing_country'] = $order->getData('country_id');
						$row['billing_postcode'] = $order->getData('postcode');
						$row['billing_telephone'] = $order->getData('telephone');
						$row['billing_company'] = $order->getData('company');
						$row['billing_fax'] = $order->getData('fax');
					}
					//THIS CHECKS TO  MAKE SURE WE ALSO HAVE A SHIPPING ADDDRESS FOR THIS ORDER IN SOMECASE WE MAY NOT.
					if(method_exists($order, 'getShippingAddress') && method_exists($order->getShippingAddress(), 'getData')) {
						$row['shipping_prefix'] = $order->getShippingAddress()->getData('prefix');
						$row['shipping_firstname'] = $order->getShippingAddress()->getData('firstname');
						$row['shipping_middlename'] = $order->getShippingAddress()->getData('middlename');
						$row['shipping_lastname'] = $order->getShippingAddress()->getData('lastname');
						$row['shipping_suffix'] = $order->getShippingAddress()->getData('suffix');
						$row['shipping_street'] = $order->getShippingAddress()->getData('street');
					    #$row['shipping_street2'] = $order->getShippingAddress()->getStreet(2);
					    #$row['shipping_street3'] = $order->getShippingAddress()->getStreet(3);
					    #$row['shipping_street4'] = $order->getShippingAddress()->getStreet(4);
						$row['shipping_city'] = $order->getShippingAddress()->getData('city');
						$row['shipping_region'] = $order->getShippingAddress()->getData('region');
						$row['shipping_country'] = $order->getShippingAddress()->getData('country_id');
						$row['shipping_postcode'] = $order->getShippingAddress()->getData('postcode');
						$row['shipping_telephone'] = $order->getShippingAddress()->getData('telephone');
						$row['shipping_company'] = $order->getShippingAddress()->getData('company');
						$row['shipping_fax'] = $order->getShippingAddress()->getData('fax');
					} else {
						$row['shipping_prefix'] = $order->getData('prefix');
						$row['shipping_firstname'] = $order->getData('firstname');
						$row['shipping_middlename'] = $order->getData('middlename');
						$row['shipping_lastname'] = $order->getData('lastname');
						$row['shipping_suffix'] = $order->getData('suffix');
						$row['shipping_street'] = $order->getData('street');
						#$row['shipping_street2'] = $order->getStreet(2);
						#$row['shipping_street3'] = $order->getStreet(3);
						#$row['shipping_street4'] = $order->getStreet(4);
						$row['shipping_city'] = $order->getData('city');
						$row['shipping_region'] = $order->getData('region');
						$row['shipping_country'] = $order->getData('country_id');
						$row['shipping_postcode'] = $order->getData('postcode');
						$row['shipping_telephone'] = $order->getData('telephone');
						$row['shipping_company'] = $order->getData('company');
						$row['shipping_fax'] = $order->getData('fax');
					}
					$row['email'] = $order->getCustomerEmail();
					$row['customer_id'] = "0";
					
			} else {
			
            $customer = $this->getCustomerModel()
                ->setData(array())
                ->load($customerId);
            /* @var $customer Mage_Customer_Model_Customer */

			$customerdataarray = $customer->getData();
			//this is when orders had a customer but NOT longer have that customer
			if(!empty($customerdataarray)){
            foreach ($customer->getData() as $field => $value) {
						//echo "FIELDS: " . $field;
                if ($field == 'website_id') {
                    $website = $this->getWebsiteById($value);
                    if ($website === false) {
                        $website = $this->getWebsiteById(0);
                    }
                    $row['website'] = $website->getCode();
					#$row['website'] = $storefromorder->getCode();
                    continue;
                }
                if (in_array($field, $systemFields) || is_object($value)) {
                    continue;
                }

                $attribute = $this->getAttribute($field);
                if (!$attribute) {
                    continue;
                }

                if ($attribute->usesSource() && $field != "store_id") {
                    $option = $attribute->getSource()->getOptionText($value);
                    if ($value && empty($option)) {
                        #$message = Mage::helper('catalog')->__("Invalid option id specified for %s (%s), skipping the record", $field, $value);
                        #$this->addException($message, Mage_Dataflow_Model_Convert_Exception::ERROR);
                        continue;
                    }
                    if (is_array($option)) {
                        $value = join(self::MULTI_DELIMITER, $option);
                    } else {
                        $value = $option;
                    }
                    unset($option);
                }
                elseif (is_array($value)) {
                    continue;
                }
                $row[$field] = $value;
            }	
          	$row['is_guest'] = "0";
			$row['email'] = $order->getCustomerEmail();
			$row['group_id'] = $this->_getCustomerGroupCode($order->getData('customer_group_id'));
						
			if($params['export_order_address'] == "true") {
									 
					  if(method_exists($order, 'getBillingAddress') && method_exists($order->getBillingAddress(), 'getData')) {
						$row['prefix'] = $order->getBillingAddress()->getData('prefix');
						$row['firstname'] = $customer->getData('firstname'); //$order->getBillingAddress()->getData('firstname');
						$row['middlename'] = $customer->getData('middlename'); //$order->getBillingAddress()->getData('middlename');
						$row['lastname'] = $customer->getData('lastname'); //$order->getBillingAddress()->getData('lastname');	
						$row['suffix'] = $order->getBillingAddress()->getData('suffix');
						$row['billing_prefix'] = $order->getBillingAddress()->getData('prefix');
						$row['billing_firstname'] = $order->getBillingAddress()->getData('firstname');
						$row['billing_middlename'] = $order->getBillingAddress()->getData('middlename');
						$row['billing_lastname'] = $order->getBillingAddress()->getData('lastname');
						$row['billing_suffix'] = $order->getBillingAddress()->getData('suffix');
						$row['billing_street'] = $order->getBillingAddress()->getData('street');
						#$row['billing_street2'] = $order->getBillingAddress()->getStreet(2);
						#$row['billing_street3'] = $order->getBillingAddress()->getStreet(3);
						#$row['billing_street4'] = $order->getBillingAddress()->getStreet(4);
						$row['billing_city'] = $order->getBillingAddress()->getData('city');
						$row['billing_region'] = $order->getBillingAddress()->getData('region');
						$row['billing_country'] = $order->getBillingAddress()->getData('country_id');
						$row['billing_postcode'] = $order->getBillingAddress()->getData('postcode');
						$row['billing_telephone'] = $order->getBillingAddress()->getData('telephone');
						$row['billing_company'] = $order->getBillingAddress()->getData('company');
						$row['billing_fax'] = $order->getBillingAddress()->getData('fax');
					} else {
						$row['prefix'] = $order->getData('prefix');
						$row['firstname'] = $order->getData('firstname');
						$row['middlename'] = $order->getData('middlename');
						$row['lastname'] = $order->getData('lastname');	
						$row['suffix'] = $order->getData('suffix');	
						$row['billing_prefix'] = $order->getData('prefix');
						$row['billing_firstname'] = $order->getData('firstname');
						$row['billing_middlename'] = $order->getData('middlename');
						$row['billing_lastname'] = $order->getData('lastname');
						$row['billing_suffix'] = $order->getData('suffix');
						$row['billing_street'] = $order->getData('street');
						#$row['billing_street2'] = $order->getStreet(2);
						#$row['billing_street3'] = $order->getStreet(3);
						#$row['billing_street4'] = $order->getStreet(4);
						$row['billing_city'] = $order->getData('city');
						$row['billing_region'] = $order->getData('region');
						$row['billing_country'] = $order->getData('country_id');
						$row['billing_postcode'] = $order->getData('postcode');
						$row['billing_telephone'] = $order->getData('telephone');
						$row['billing_company'] = $order->getData('company');
						$row['billing_fax'] = $order->getData('fax');
					}
					//THIS CHECKS TO  MAKE SURE WE ALSO HAVE A SHIPPING ADDDRESS FOR THIS ORDER IN SOMECASE WE MAY NOT.
					if(method_exists($order, 'getShippingAddress') && method_exists($order->getShippingAddress(), 'getData')) {
						$row['shipping_prefix'] = $order->getShippingAddress()->getData('prefix');
						$row['shipping_firstname'] = $order->getShippingAddress()->getData('firstname');
						$row['shipping_middlename'] = $order->getShippingAddress()->getData('middlename');
						$row['shipping_lastname'] = $order->getShippingAddress()->getData('lastname');
						$row['shipping_suffix'] = $order->getShippingAddress()->getData('suffix');
						$row['shipping_street'] = $order->getShippingAddress()->getData('street');
						#$row['shipping_street2'] = $order->getShippingAddress()->getStreet(2);
						#$row['shipping_street3'] = $order->getShippingAddress()->getStreet(3);
						#$row['shipping_street4'] = $order->getShippingAddress()->getStreet(4);
						$row['shipping_city'] = $order->getShippingAddress()->getData('city');
						$row['shipping_region'] = $order->getShippingAddress()->getData('region');
						$row['shipping_country'] = $order->getShippingAddress()->getData('country_id');
						$row['shipping_postcode'] = $order->getShippingAddress()->getData('postcode');
						$row['shipping_telephone'] = $order->getShippingAddress()->getData('telephone');
						$row['shipping_company'] = $order->getShippingAddress()->getData('company');
						$row['shipping_fax'] = $order->getShippingAddress()->getData('fax');
					} else {
						$row['shipping_prefix'] = $order->getData('prefix');
						$row['shipping_firstname'] = $order->getData('firstname');
						$row['shipping_middlename'] = $order->getData('middlename');
						$row['shipping_lastname'] = $order->getData('lastname');
						$row['shipping_suffix'] = $order->getData('suffix');
						$row['shipping_street'] = $order->getData('street');
						#$row['shipping_street2'] = $order->getStreet(2);
						#$row['shipping_street3'] = $order->getStreet(3);
						#$row['shipping_street4'] = $order->getStreet(4);
						$row['shipping_city'] = $order->getData('city');
						$row['shipping_region'] = $order->getData('region');
						$row['shipping_country'] = $order->getData('country_id');
						$row['shipping_postcode'] = $order->getData('postcode');
						$row['shipping_telephone'] = $order->getData('telephone');
						$row['shipping_company'] = $order->getData('company');
						$row['shipping_fax'] = $order->getData('fax');
					}
									 
			} else {
			
				$defaultBillingId  = $customer->getDefaultBilling();
				$defaultShippingId = $customer->getDefaultShipping();
	
				$customerAddress = $this->getCustomerAddressModel();
	
				if (!$defaultBillingId) {
					//$row['billing_'.$code] = null;
					if(method_exists($order, 'getBillingAddress') && method_exists($order->getBillingAddress(), 'getData')) {
						$row['prefix'] = $order->getBillingAddress()->getData('prefix');
						$row['firstname'] = $customer->getData('firstname'); //$order->getBillingAddress()->getData('firstname');
						$row['middlename'] = $customer->getData('middlename'); //$order->getBillingAddress()->getData('middlename');
						$row['lastname'] = $customer->getData('lastname'); //$order->getBillingAddress()->getData('lastname');	
						$row['suffix'] = $order->getBillingAddress()->getData('suffix');	
						$row['billing_prefix'] = $order->getBillingAddress()->getData('prefix');
						$row['billing_firstname'] = $order->getBillingAddress()->getData('firstname');
						$row['billing_middlename'] = $order->getBillingAddress()->getData('middlename');
						$row['billing_lastname'] = $order->getBillingAddress()->getData('lastname');
						$row['billing_suffix'] = $order->getBillingAddress()->getData('suffix');
						$row['billing_street'] = $order->getBillingAddress()->getData('street');
						#$row['billing_street2'] = $order->getBillingAddress()->getStreet(2);
						#$row['billing_street3'] = $order->getBillingAddress()->getStreet(3);
						#$row['billing_street4'] = $order->getBillingAddress()->getStreet(4);
						$row['billing_city'] = $order->getBillingAddress()->getData('city');
						$row['billing_region'] = $order->getBillingAddress()->getData('region');
						$row['billing_country'] = $order->getBillingAddress()->getData('country_id');
						$row['billing_postcode'] = $order->getBillingAddress()->getData('postcode');
						$row['billing_telephone'] = $order->getBillingAddress()->getData('telephone');
						$row['billing_company'] = $order->getBillingAddress()->getData('company');
						$row['billing_fax'] = $order->getBillingAddress()->getData('fax');
					} else {
						$row['prefix'] = $order->getData('prefix');
						$row['firstname'] = $order->getData('firstname');
						$row['middlename'] = $order->getData('middlename');
						$row['lastname'] = $order->getData('lastname');	
						$row['suffix'] = $order->getData('suffix');	
						$row['billing_prefix'] = $order->getData('prefix');
						$row['billing_firstname'] = $order->getData('firstname');
						$row['billing_middlename'] = $order->getData('middlename');
						$row['billing_lastname'] = $order->getData('lastname');
						$row['billing_suffix'] = $order->getData('suffix');
						$row['billing_street'] = $order->getData('street');
						#$row['billing_street2'] = $order->getStreet(2);
						#$row['billing_street3'] = $order->getStreet(3);
						#$row['billing_street4'] = $order->getStreet(4);
						$row['billing_city'] = $order->getData('city');
						$row['billing_region'] = $order->getData('region');
						$row['billing_country'] = $order->getData('country_id');
						$row['billing_postcode'] = $order->getData('postcode');
						$row['billing_telephone'] = $order->getData('telephone');
						$row['billing_company'] = $order->getData('company');
						$row['billing_fax'] = $order->getData('fax');
					}
				} else {
					$customerAddress->load($defaultBillingId);
					foreach ($this->getFields() as $code=>$node) {
						#if ($node->is('billing')) {
						if($code == "street") {
							 $streetArray = $customerAddress->getDataUsingMethod("street");
							 $row['billing_street'] = $streetArray[0];
							 #$row['billing_street2'] = $customerAddress->getDataUsingMethod("street2");
							 #$row['billing_street3'] = $customerAddress->getDataUsingMethod("street3");
							 #$row['billing_street4'] = $customerAddress->getDataUsingMethod("street4");
						} else {
							 $row['billing_'.$code] = $customerAddress->getDataUsingMethod($code);
						}
						#}
					}
				}
				if (!$defaultShippingId) {
					#foreach ($this->getFields() as $code=>$node) {
						#if ($node->is('shipping')) {
							//$row['shipping_'.$code] = null;
							//THIS CHECKS TO  MAKE SURE WE ALSO HAVE A SHIPPING ADDDRESS FOR THIS ORDER IN SOMECASE WE MAY NOT.
							//BASICALLY ITS GRABBING THE BILLLING ADDRESS SINCE ORDER EITHER HAS SAME OR NO SHIPPING ADDRESS
							if(method_exists($order, 'getShippingAddress') && method_exists($order->getShippingAddress(), 'getData')) {
								$row['shipping_prefix'] = $order->getShippingAddress()->getData('prefix');
								$row['shipping_firstname'] = $order->getShippingAddress()->getData('firstname');
								$row['shipping_middlename'] = $order->getShippingAddress()->getData('middlename');
								$row['shipping_lastname'] = $order->getShippingAddress()->getData('lastname');
								$row['shipping_suffix'] = $order->getShippingAddress()->getData('suffix');
								$row['shipping_street'] = $order->getShippingAddress()->getData('street');
								#$row['shipping_street2'] = $order->getShippingAddress()->getStreet(2);
								#$row['shipping_street3'] = $order->getShippingAddress()->getStreet(3);
								#$row['shipping_street4'] = $order->getShippingAddress()->getStreet(4);
								$row['shipping_city'] = $order->getShippingAddress()->getData('city');
								$row['shipping_region'] = $order->getShippingAddress()->getData('region');
								$row['shipping_country'] = $order->getShippingAddress()->getData('country_id');
								$row['shipping_postcode'] = $order->getShippingAddress()->getData('postcode');
								$row['shipping_telephone'] = $order->getShippingAddress()->getData('telephone');
								$row['shipping_company'] = $order->getShippingAddress()->getData('company');
								$row['shipping_fax'] = $order->getShippingAddress()->getData('fax');
							} else {
								$row['shipping_prefix'] = $order->getData('prefix');
								$row['shipping_firstname'] = $order->getData('firstname');
								$row['shipping_middlename'] = $order->getData('middlename');
								$row['shipping_lastname'] = $order->getData('lastname');
								$row['shipping_suffix'] = $order->getData('suffix');
								$row['shipping_street'] = $order->getData('street');
								#$row['shipping_street2'] = $order->getStreet(2);
								#$row['shipping_street3'] = $order->getStreet(3);
								#$row['shipping_street4'] = $order->getStreet(4);
								$row['shipping_city'] = $order->getData('city');
								$row['shipping_region'] = $order->getData('region');
								$row['shipping_country'] = $order->getData('country_id');
								$row['shipping_postcode'] = $order->getData('postcode');
								$row['shipping_telephone'] = $order->getData('telephone');
								$row['shipping_company'] = $order->getData('company');
								$row['shipping_fax'] = $order->getData('fax');
							}
						#}
					#}
				} else {
					if ($defaultShippingId != $defaultBillingId) {
						$customerAddress->load($defaultShippingId);
					}
					foreach ($this->getFields() as $code=>$node) {
						#if ($node->is('shipping')) {
							if($code == "street") {
								 $streetArray = $customerAddress->getDataUsingMethod("street");
								 $row['shipping_street'] = $streetArray[0];
								 #$row['shipping_street2'] = $customerAddress->getDataUsingMethod("street2");
								 #$row['shipping_street3'] = $customerAddress->getDataUsingMethod("street3");
								 #$row['shipping_street4'] = $customerAddress->getDataUsingMethod("street4");
							} else {
								 $row['shipping_'.$code] = $customerAddress->getDataUsingMethod($code);
							}
						#}
					}
				}
			} //ends check on if we want to use customer address or order addresses
			//this is when orders had a customer but NOT longer have that customer
			} else {
					#$valueid = $store->getData('website_id');
					$valueid = $storefromorder->getData('website_id');
					$website = $this->getWebsiteById($valueid);
					//print($website);
					$row['website'] = $website->getCode();
          			$row['is_guest'] = "0";
					$row['email'] = $order->getCustomerEmail();
					if($row['email'] == "") {
						$payment = $order->getPayment();
						$paymentadditionalinformation = $order->getPayment()->getAdditionalInformation();
						$looopthisdata = $paymentadditionalinformation['paypal_payer_email'];
						$row['email'] = $paymentadditionalinformation['paypal_payer_email'];
					}
					#$row['website'] = $storefromorder->getCode();
					$row['group_id'] = $this->_getCustomerGroupCode($order->getData('customer_group_id'));
					
					if(method_exists($order->getBillingAddress(), 'getData')) {
						$row['prefix'] = $order->getBillingAddress()->getData('prefix');
						$row['firstname'] = $order->getBillingAddress()->getData('firstname');
						$row['middlename'] = $order->getBillingAddress()->getData('middlename');
						$row['lastname'] = $order->getBillingAddress()->getData('lastname');	
						$row['suffix'] = $order->getBillingAddress()->getData('suffix');	
						$row['billing_prefix'] = $order->getBillingAddress()->getData('prefix');
						$row['billing_firstname'] = $order->getBillingAddress()->getData('firstname');
						$row['billing_middlename'] = $order->getBillingAddress()->getData('middlename');
						$row['billing_lastname'] = $order->getBillingAddress()->getData('lastname');
						$row['billing_suffix'] = $order->getBillingAddress()->getData('suffix');
						$row['billing_street'] = $order->getBillingAddress()->getData('street');
						#$row['billing_street2'] = $order->getBillingAddress()->getStreet(2);
						#$row['billing_street3'] = $order->getBillingAddress()->getStreet(3);
						#$row['billing_street4'] = $order->getBillingAddress()->getStreet(4);
						$row['billing_city'] = $order->getBillingAddress()->getData('city');
						$row['billing_region'] = $order->getBillingAddress()->getData('region');
						$row['billing_country'] = $order->getBillingAddress()->getData('country_id');
						$row['billing_postcode'] = $order->getBillingAddress()->getData('postcode');
						$row['billing_telephone'] = $order->getBillingAddress()->getData('telephone');
						$row['billing_company'] = $order->getBillingAddress()->getData('company');
						$row['billing_fax'] = $order->getBillingAddress()->getData('fax');
					} else {
						$row['prefix'] = $order->getData('prefix');
						$row['firstname'] = $order->getData('firstname');
						$row['middlename'] = $order->getData('middlename');
						$row['lastname'] = $order->getData('lastname');	
						$row['suffix'] = $order->getData('suffix');	
						$row['billing_prefix'] = $order->getData('prefix');
						$row['billing_firstname'] = $order->getData('firstname');
						$row['billing_middlename'] = $order->getData('middlename');
						$row['billing_lastname'] = $order->getData('lastname');
						$row['billing_suffix'] = $order->getData('suffix');
						$row['billing_street'] = $order->getData('street');
						#$row['billing_street2'] = $order->getStreet(2);
						#$row['billing_street3'] = $order->getStreet(3);
						#$row['billing_street4'] = $order->getStreet(4);
						$row['billing_city'] = $order->getData('city');
						$row['billing_region'] = $order->getData('region');
						$row['billing_country'] = $order->getData('country_id');
						$row['billing_postcode'] = $order->getData('postcode');
						$row['billing_telephone'] = $order->getData('telephone');
						$row['billing_company'] = $order->getData('company');
						$row['billing_fax'] = $order->getData('fax');
					}
					//THIS CHECKS TO  MAKE SURE WE ALSO HAVE A SHIPPING ADDDRESS FOR THIS ORDER IN SOMECASE WE MAY NOT.
					if(method_exists($order->getShippingAddress(), 'getData')) {
						$row['shipping_prefix'] = $order->getShippingAddress()->getData('prefix');
						$row['shipping_firstname'] = $order->getShippingAddress()->getData('firstname');
						$row['shipping_middlename'] = $order->getShippingAddress()->getData('middlename');
						$row['shipping_lastname'] = $order->getShippingAddress()->getData('lastname');
						$row['shipping_suffix'] = $order->getShippingAddress()->getData('suffix');
						$row['shipping_street'] = $order->getShippingAddress()->getData('street');
						#$row['shipping_street2'] = $order->getShippingAddress()->getStreet(2);
						#$row['shipping_street3'] = $order->getShippingAddress()->getStreet(3);
						#$row['shipping_street4'] = $order->getShippingAddress()->getStreet(4);
						$row['shipping_city'] = $order->getShippingAddress()->getData('city');
						$row['shipping_region'] = $order->getShippingAddress()->getData('region');
						$row['shipping_country'] = $order->getShippingAddress()->getData('country_id');
						$row['shipping_postcode'] = $order->getShippingAddress()->getData('postcode');
						$row['shipping_telephone'] = $order->getShippingAddress()->getData('telephone');
						$row['shipping_company'] = $order->getShippingAddress()->getData('company');
						$row['shipping_fax'] = $order->getShippingAddress()->getData('fax');
					} else {
						$row['shipping_prefix'] = $order->getData('prefix');
						$row['shipping_firstname'] = $order->getData('firstname');
						$row['shipping_middlename'] = $order->getData('middlename');
						$row['shipping_lastname'] = $order->getData('lastname');
						$row['shipping_suffix'] = $order->getData('suffix');
						$row['shipping_street'] = $order->getData('street');
						#$row['shipping_street2'] = $order->getStreet(2);
						#$row['shipping_street3'] = $order->getStreet(3);
						#$row['shipping_street4'] = $order->getStreet(4);
						$row['shipping_city'] = $order->getData('city');
						$row['shipping_region'] = $order->getData('region');
						$row['shipping_country'] = $order->getData('country_id');
						$row['shipping_postcode'] = $order->getData('postcode');
						$row['shipping_telephone'] = $order->getData('telephone');
						$row['shipping_company'] = $order->getData('company');
						$row['shipping_fax'] = $order->getData('fax');
					}
					$row['customer_id'] = "0";
				}		
				
			} //if guest end if statement
			$customerId = $order->getData('customer_id');
			$customer = $this->getCustomerModel()
                ->setData(array())
                ->load($customerId);
						//print($customer);
            $store = $this->getStoreById($customer->getStoreId());
            if ($store === false) {
                $store = $this->getStoreById(0);
            }
            $row['created_in'] = $store->getCode();
			/*
            $newsletter = $this->getNewsletterModel()
                ->loadByCustomer($customer);
            $row['is_subscribed'] = ($newsletter->getId()
                && $newsletter->getSubscriberStatus() == Mage_Newsletter_Model_Subscriber::STATUS_SUBSCRIBED)
                ? 1 : 0;
			*/
			/* ADDITIONAL ORDER DETAILS TO EXPORT */
			#print_r($order);
			/*
			$payment = $order->getPayment();
			#print_r($payment->getData('po_number'));
			$row['order_po_number'] = $payment->getData('po_number');
			*/
			$row['customer_id'] = $customerId;
            $row['order_id'] = $order->getData('increment_id');
            $row['created_at'] = $order->getData('created_at');
            $row['updated_at'] = $order->getData('updated_at');
            $row['tax_amount'] = $order->getData('tax_amount');
			
			if($params['export_product_tax_percent'] == "true") {
				$tax_info = $order->getFullTaxInfo();
				$tax_rate = $tax_info[0]['percent'];
				if($tax_rate != "") {
					$row['tax_percent'] = $tax_rate;
				} else {
					$select_qry = "SELECT * FROM ".$sales_order_item." WHERE order_id ='".$order->getId()."' LIMIT 1";
                	$newrowItemId = $connection->fetchAll($select_qry);
					$row['tax_percent'] = round($newrowItemId[0]['tax_percent'],0);
				}
			}
			
			$row['shipping_method'] = $order->getData('shipping_method');
            $row['shipping_amount'] = $order->getData('shipping_amount');
            $row['discount_amount'] = $order->getData('discount_amount');
            $row['subtotal'] = $order->getData('subtotal');
            $row['grand_total'] = $order->getData('grand_total');
            $row['total_paid'] = $order->getData('total_paid');
            $row['total_refunded'] = $order->getData('total_refunded');
            $row['total_qty_ordered'] = $order->getData('total_qty_ordered');
            $row['total_canceled'] = $order->getData('total_canceled');
            $row['total_invoiced'] = $order->getData('total_invoiced');
            $row['total_online_refunded'] = $order->getData('total_online_refunded');
            $row['total_offline_refunded'] = $order->getData('total_offline_refunded');
            $row['base_tax_amount'] = $order->getData('base_tax_amount');
            $row['base_shipping_amount'] = $order->getData('base_shipping_amount');
            $row['base_discount_amount'] = $order->getData('base_discount_amount');
            $row['base_subtotal'] = $order->getData('base_subtotal');
            $row['base_grand_total'] = $order->getData('base_grand_total');
            $row['base_total_paid'] = $order->getData('base_total_paid');
            $row['base_total_refunded'] = $order->getData('base_total_refunded');
            $row['base_total_qty_ordered'] = $order->getData('base_total_qty_ordered');
            $row['base_total_canceled'] = $order->getData('base_total_canceled');
            $row['base_total_invoiced'] = $order->getData('base_total_invoiced');
            $row['base_total_online_refunded'] = $order->getData('base_total_online_refunded');
            $row['base_total_offline_refunded'] = $order->getData('base_total_offline_refunded');
            $row['subtotal_refunded'] = $order->getData('subtotal_refunded');
            $row['subtotal_canceled'] = $order->getData('subtotal_canceled');
            $row['discount_refunded'] = $order->getData('discount_refunded');
            $row['discount_invoiced'] = $order->getData('discount_invoiced');
            $row['tax_refunded'] = $order->getData('tax_refunded');
            $row['tax_canceled'] = $order->getData('tax_canceled');
            $row['shipping_refunded'] = $order->getData('shipping_refunded');
            $row['shipping_canceled'] = $order->getData('shipping_canceled');
            $row['base_subtotal_refunded'] = $order->getData('base_subtotal_refunded');
            $row['base_subtotal_canceled'] = $order->getData('base_subtotal_canceled');
            $row['base_discount_refunded'] = $order->getData('base_discount_refunded');
            $row['base_discount_canceled'] = $order->getData('base_discount_canceled');
            $row['base_discount_invoiced'] = $order->getData('base_discount_invoiced');
            $row['base_tax_refunded'] = $order->getData('base_tax_refunded');
            $row['base_tax_canceled'] = $order->getData('base_tax_canceled');
            $row['base_shipping_refunded'] = $order->getData('base_shipping_refunded');
            $row['base_shipping_canceled'] = $order->getData('base_shipping_canceled');
            $row['subtotal_invoiced'] = $order->getData('subtotal_invoiced');
            $row['tax_invoiced'] = $order->getData('tax_invoiced');
            $row['shipping_invoiced'] = $order->getData('shipping_invoiced');
            $row['base_subtotal_invoiced'] = $order->getData('base_subtotal_invoiced');
            $row['base_tax_invoiced'] = $order->getData('base_tax_invoiced');
            $row['base_shipping_invoiced'] = $order->getData('base_shipping_invoiced');
            $row['shipping_tax_amount'] = $order->getData('shipping_tax_amount');
            $row['base_shipping_tax_amount'] = $order->getData('base_shipping_tax_amount');
            $row['shipping_tax_refunded'] = $order->getData('shipping_tax_refunded');
            $row['base_shipping_tax_refunded'] = $order->getData('base_shipping_tax_refunded');
			
			
						if($order->getStoreId() !="") {
							#$row['store_id'] = $this->getStoreId();
							$row['store_id'] = $order->getStoreId();
						} else {
							$row['store_id'] = "0";
						}
						
						#if(method_exists($order->getPayment(), 'getMethod')) {
							$row['payment_method'] = $order->getPayment()->getMethod();
						#} else {
							#$row['payment_method'] = "";
						#}
						$items = $order->getAllItems();
						$itemscount = 1;
						$finalproductsorder = "";
						$finalvaluesfromoptions="";
						$nonconfigurablesku="";
						$theseproductsarebundlesimples="";
						foreach ($items as $itemId => $item)
						{
							$finalvaluesfromoptions=""; //need to reset options values on each successive item
							/*
							 $row['product_name_'.$itemscount.''] = $item->getName();
							 $row['product_price_'.$itemscount.''] = $item->getPrice();
							 $row['product_sku_'.$itemscount.''] = $item->getSku();
							 $row['product_id_'.$itemscount.''] = $item->getProductId();
							 $row['product_qty_'.$itemscount.''] = $item->getQtyToInvoice(); 
							 #$row['product_qty_'.$itemscount.''] = $item->getQtyOrdered(); 
							 //echo "TEST: " . $item->getData('product_options');
							 $itemscount++;
							 */
							 #echo "SKU: " . $item->getSku();
							 #echo "TYPE: " . $item->getProductType();
							 if(!is_array($item->getData('product_options'))) {
							 	$productoptionsfromconfigurables = unserialize($item->getData('product_options'));
							 } else {
								$productoptionsfromconfigurables = $item->getData('product_options');
							 }
							 
							 if(isset($productoptionsfromconfigurables['attributes_info'])) {
								foreach ($productoptionsfromconfigurables['attributes_info'] as $configurablesitemId => $configurablesitem)
								{
							 	 #print_r($configurablesitem);
								 $finalvaluesfromoptions .= $configurablesitem['value'] . ":";
								}
							 }
							 if ($item->getProductType() == "configurable") {
							 	 #print_r($item->getData('product_id'));
								 //oddly this works if its not finding configurable sku
								 #$productconfigItem = Mage::getModel('catalog/product')->load($item->getData('product_id')); 
								 #$configskuforexport =  $productconfigItem->getSku();
								 $configskuforexport = $item->getSku();
								 $configskuforexport1 =  $item->getSku(). "config"; //for when oddly simple and config skus match
								 #echo "CONFIG SKU: " . $configskuforexport . "<br/>";
								 $nonconfigurablesku = $item->getProductOptionByCode('simple_sku');
							 }
							 if ($item->getProductType() == "bundle") {
							 	 
								 #print_r($item->getData());
								 $order_prodID = $item->getData('product_id');
								 $finalbundleoptions = "";
								 $theseproductsarebundlesimples = false;
								 
								 $bundle_options_serialize = $item->getData('product_options');
								 if(!is_array($bundle_options_serialize)) {
								 	$bundle_json_array = unserialize($bundle_options_serialize);
								 } else {
								 	$bundle_json_array = $bundle_options_serialize;
								 	#$bundle_json_array = ($bundle_options_serialize);
								 }
								
								$optionModel = $this->_objectManager->get('Magento\Bundle\Model\Option')->getResourceCollection()->setProductIdFilter($order_prodID);
								
								if(is_array($optionModel)) {
									
								 $productbundleItem = $this->_objectManager->get('Magento\Catalog\Model\Product')->load($order_prodID);
								 $productbundleItemSku =  $productbundleItem->getSku();
								 $bundleskuforexport =  trim($productbundleItemSku, '() ');
									foreach($optionModel as $eachOption) {
										$eachOptionArray = $eachOption->getData();
										$ordered_bundle_options = $bundle_json_array['bundle_options'][$eachOption->getData('option_id')];
										#$selectionModel = Mage::getModel('bundle/selection')->setOptionId($eachOption->getData('option_id'))->getResourceCollection();
										
										$selectionCollection = $productbundleItem->getTypeInstance(true)->getSelectionsCollection(
										$productbundleItem->getTypeInstance(true)->getOptionsIds($productbundleItem), $productbundleItem);
								 
										foreach($selectionCollection as $option)
										{
											if($option->getName() == $ordered_bundle_options['value'][0]['title']) {
												
												if($params['export_product_pricing'] == "true") {
												$finalbundleoptions .= $option->getData('sku') . "~" .$ordered_bundle_options['value'][0]['qty']. "^" . $ordered_bundle_options['value'][0]['price']. "^" . $ordered_bundle_options['value'][0]['title']. "^";
												} else {
												$finalbundleoptions .= $option->getData('sku') . "~" .$ordered_bundle_options['value'][0]['qty']. "^";
												}
												$theseproductsarebundlesimples = true;
											}
										}
										
									}
								} else {
									$bundleskuforexport = $item->getSku();
									foreach($bundle_json_array['bundle_options'] as $option)
									{
									  if($params['export_product_pricing'] == "true") {
										$finalbundleoptions .= "nosku~" .$option['value'][0]['qty']. "^" . $option['value'][0]['price']. "^" . $option['value'][0]['title']. "^";
									  } else {
										$finalbundleoptions .= "nosku~" .$option['value'][0]['qty']. "^";
									  }
									}
									$theseproductsarebundlesimples = true;
								}
								
								$final_bundle_simple_skus = substr_replace($finalbundleoptions,"",-1);
							 }
							 //for when simple and config oddly match
							 if($finalvaluesfromoptions !="" && $item->getProductType() == "configurable" && $nonconfigurablesku != $configskuforexport1) {
							 #if($finalvaluesfromoptions !="" && $item->getProductType() == "configurable" && $nonconfigurablesku != $configskuforexport) {
							 	 $okcleanedfinalvalues = substr_replace($finalvaluesfromoptions,"",-1);
								 
								 if($params['export_product_pricing'] == "true") {
								 	$finalproductsorder .= $configskuforexport . ":" . $item->getQtyOrdered() . ":configurable:" . $okcleanedfinalvalues . "^" . $item->getPrice() . "^" . $item->getName() . "|";
								 } else {
									$finalproductsorder .= $configskuforexport . ":" . $item->getQtyOrdered() . ":configurable:" . $okcleanedfinalvalues . "|";
								 }
								 
							 } else if($item->getProductType() == "bundle") {
							   if($params['export_product_pricing'] == "true") {
								 $finalproductsorder .= $bundleskuforexport . ":" . $item->getQtyOrdered() . ":bundle:" . $final_bundle_simple_skus. "|";
							   } else {
								 $finalproductsorder .= $bundleskuforexport . ":" . $item->getQtyOrdered() . ":bundle:" . $final_bundle_simple_skus. "|";
							   }
							 } else if($nonconfigurablesku != $item->getSku() && $theseproductsarebundlesimples != true) {
								 
								 #$arrayofsimpleproductcustomoptios = $item->getProductOptions();
								 $currentoptionscount=0;
								 #if($productsimplecustomItem['has_options']) {
								 $itemsOptions = $item->getProductOptions();
								 #print_r($itemsOptions);
								 if(is_array($itemsOptions) && !empty($itemsOptions['options'])) {
								 #if($productsimplecustomItem->getTypeInstance(true)->hasOptions($productsimplecustomItem)) {
									$finalsimpleoptionsexport = "";
									foreach ($item->getProductOptions() as $option) 
									{
										#print_r($option);
										#echo "SKU: " . $item->getSku() . " ID: " . $order->getData('increment_id') .  "<br/>";
										if (isset($option[$currentoptionscount]['label']) && isset($option[$currentoptionscount]['value'])) {
										$itemsoptionscount=1;
											foreach ($option as $optionchoices) {
												if (isset($optionchoices['label'])) {
													//echo $optionchoices['label'];
													#$finalsimpleoptionsexport .= $optionchoices['value']  .":";
													if($optionchoices['option_type'] == "file") {
														if(is_array($optionchoices['option_value'])) {
															$finalsimpleoptionsexport .= $optionchoices['option_value']['title']  .":";
														} else {
															$value_unserialized = unserialize($optionchoices['option_value']);
															$finalsimpleoptionsexport .= $value_unserialized['title']  .":";
														}
													} else {
														$finalsimpleoptionsexport .= $optionchoices['value']  .":";
													}
													$itemsoptionscount++;
												}
											}
										$currentoptionscount++;
										
									 if($params['export_product_pricing'] == "true") {
									 	$finalproductsorder .= $item->getSku() . ":" . $item->getQtyOrdered() . ":simple:" . substr_replace($finalsimpleoptionsexport,"",-1) . "^" . $item->getPrice() . "^" . $item->getName() . "|";
									 } else {
									 	$finalproductsorder .= $item->getSku() . ":" . $item->getQtyOrdered() . ":simple:" . substr_replace($finalsimpleoptionsexport,"",-1) . "|";
									 }
										}
									}
								 } else {
									 
									 if($params['export_product_pricing'] == "true") {
										$finalproductsorder .= $item->getSku() . ":" . $item->getQtyOrdered() . ":" . $item->getPrice() . ":" . $item->getName() . "|";
									 } else {
									 	$finalproductsorder .= $item->getSku() . ":" . $item->getQtyOrdered() . "|";
									 }
								 }
							 } else {
							 	//problem  here is if this code is enabled could pick up simples that are otherwise missing (matching config skus so skipping but actually shouldn't be.. or when this is on cause it needs to be then the bundle related simples start showing here even thou they shouldn't be
							 	/*
								$currentoptionscount=0;
								$itemsOptions = $item->getProductOptions();
								#print_r($itemsOptions);
								
							 	if ($item->getProductType() == "simple" && is_array($itemsOptions) && !empty($itemsOptions['options'])) {
									
									$finalsimpleoptionsexport = "";
									foreach ($item->getProductOptions() as $option) 
									{
										#print_r($option);
										#echo "SKU: " . $item->getSku() . " ID: " . $order->getData('increment_id') .  "<br/>";
										if (isset($option[$currentoptionscount]['label']) && isset($option[$currentoptionscount]['value'])) {
										$itemsoptionscount=1;
											foreach ($option as $optionchoices) {
												if (isset($optionchoices['label'])) {
													//echo $optionchoices['label'];
													#$finalsimpleoptionsexport .= $optionchoices['value']  .":";
													if($optionchoices['option_type'] == "file") {
														$value_unserialized = unserialize($optionchoices['option_value']);
														$finalsimpleoptionsexport .= $value_unserialized['title']  .":";
													} else {
														$finalsimpleoptionsexport .= $optionchoices['value']  .":";
													}
													$itemsoptionscount++;
												}
											}
										$currentoptionscount++;
										
										 if($params['export_product_pricing'] == "true") {
											$finalproductsorder .= $item->getSku() . ":" . $item->getQtyOrdered() . ":simple:" . substr_replace($finalsimpleoptionsexport,"",-1) . "^" . $item->getPrice() . "^" . $item->getName() . "|";
										 } else {
											$finalproductsorder .= $item->getSku() . ":" . $item->getQtyOrdered() . ":simple:" . substr_replace($finalsimpleoptionsexport,"",-1) . "|";
										 }
										 #print_r($finalproductsorder);
										}
									}
								
								} else {
									if($params['export_product_pricing'] == "true") {
										$finalproductsorder .= $item->getSku() . ":" . $item->getQtyOrdered() . ":" . $item->getPrice() . ":" . $item->getName() . "|";
									} else {
										$finalproductsorder .= $item->getSku() . ":" . $item->getQtyOrdered() . "|";
									}
								}
								*/
							 
							 }
							 
							 #$finalproductsorder .= $item->getSku() .":" . $item->getQtyOrdered() . "|";
						}
						$row['products_ordered'] = substr_replace($finalproductsorder,"",-1);
						$row['order_comments'] = $order->getCustomerNote();
						
						/*FETCH ALL COMMNETS */
						
						$all_comments = "";
						foreach ($order->getAllStatusHistory() as $orderComment) {
							$finalOrderComment = str_replace(",","&#44;",$orderComment['comment']);
							#$finalOrderComment1 = str_replace("\n"," ",$finalOrderComment);
							$all_comments .= $orderComment['is_customer_notified'] . "^^" . $orderComment['is_visible_on_front']. "^^" . $finalOrderComment . "^^" . $orderComment['status']. "^^" . $orderComment['created_at'] . "||";
						}
						#$finalComments = preg_replace('/^\s+|\n|\r|\t|\s+$/m', '', $all_comments); 
						$row['additional_comments'] = substr_replace($all_comments,"",-2);
						
						/*FETCH ALL COMMNETS */
						
						#$payment = $order->getPayment();
						#$row['order_po_number'] = $payment->getData('po_number');
						//CUSTOM ORDER TRACKING EXPORT	
						$shipmethod = "";
						$shipmentdates = "";
						$carriercodes = "";			
								
						$shipmentCollection = $this->_objectManager->get('Magento\Sales\Model\ResourceModel\Order\Shipment\Track\CollectionFactory')->create()->setOrderFilter($order)->load();
						foreach ($shipmentCollection as $tracknum){
							#print_r($tracknum->getData());
							#echo "CODE:" . $tracknum->getCarrierCode();
							$shipmentdates = $tracknum->getCreatedAt();
							#$shipmentdates .= $tracknum->getCreatedAt() . ",";
							#$carriertitles[]=$tracknum->getTitle();
							$carriercodes .= $tracknum->getNumber() . ",";
							$shipmethod = $tracknum->getCarrierCode();
							#$tracknums[]=$tracknum->getNumber();
						}
						#$row['tracking_date'] = substr_replace($shipmentdates,"",-1);
						$row['tracking_date'] = $shipmentdates;
						$row['tracking_ship_method'] = $shipmethod;
						$row['tracking_codes'] = substr_replace($carriercodes,"",-1);
						//END CUSTOM ORDER TRACKING EXPORT
						
			$row['order_status'] = $order->getStatus();
			
			$order->addData($row);
			$content .= $order->toString($template) . "\n";
			
		}
		#exit;
        //$content .= $template . "\n";
        
        return $this->fileFactory->create('export_orders.csv', $content, DirectoryList::VAR_DIR);
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(
            'CommerceExtensions_OrderImportExport::import_export'
        );

    }
}
