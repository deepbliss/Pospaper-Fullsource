<?php
/**
 * Copyright � 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Nextopia\Search\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;
use \Magento\Framework\App\ObjectManager;
use \Magento\Store\Model\ScopeInterface;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class Data extends AbstractHelper
{

    /**
     * Config paths for using throughout the code
     */
    const XML_PATH_PUBLIC_CLIENT_ID = 'nextopia_search/general/public_client_id';

    const XML_PATH_SEARCHSTATUS = 'nextopia_search/general/searchstatus';

    const XML_PATH_SEARCHDEMO = 'nextopia_search/general/searchdemo';

    const XML_PATH_NAVSTATUS = 'nextopia_search/general/navstatus';

    const XML_PATH_CONTENT_DIV_ID = 'nextopia_search/general/content_div_id';

    const XML_PATH_REFINEMENT_DIV_ID = 'nextopia_search/general/refinement_div_id';

    const XML_PATH_SELECTED_LAYOUT = 'nextopia_search/general/selected_layout';

    const XML_PATH_DEFAULT_CONTENT = 'nextopia_search/general/default_content';

    const XML_PATH_AJAX_VESION = 'nextopia_search/general/ajax_version';
    
    const XML_PATH_PERSONAS_STATUS  = 'nextopia_search/general/personas_status';
    
    const XML_PATH_CRON_NEXTOPIA_STATUS  = 'nextopia_search/general/cron_nextopia_status';

    const XML_PATH_AUTH_USER = 'nextopia_search/general/auth_user';

    const XML_PATH_AUTH_PW = 'nextopia_search/general/auth_pw';

    const XML_PATH_LABEL_SEARCH_RESULT_PAGE = 'nextopia_search/general/label_search_result_page';

    /**
     * @return bool
     */
    public function isEnabled($storeId = null) {
        return (bool)$this->scopeConfig->getValue(self::XML_PATH_SEARCHSTATUS, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @return bool
     */
    public function isNavEnabled($storeId = null) {
        return (bool)$this->scopeConfig->getValue(self::XML_PATH_NAVSTATUS, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @return bool
     */
    public function isDemo($storeId = null) {
        return (bool)$this->scopeConfig->getValue(self::XML_PATH_SEARCHDEMO, ScopeInterface::SCOPE_STORE, $storeId);
    }
    
    
    public function getNxtId($storeId = null)
    {
        return $this->scopeConfig->getValue(self::XML_PATH_PUBLIC_CLIENT_ID, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getLoadingContent($storeId = null)
    {
        return $this->scopeConfig->getValue(self::XML_PATH_DEFAULT_CONTENT, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getLayoutHandlePostfix($storeId = null)
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SELECTED_LAYOUT, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getAjaxVersion($storeId = null)
    {
        $ajaxVersion =  $this->scopeConfig->getValue(self::XML_PATH_AJAX_VESION, ScopeInterface::SCOPE_STORE, $storeId);
        
        $ajaxVersion =  ($ajaxVersion === '1.5.1')? "v$ajaxVersion": $ajaxVersion;
        
        return $ajaxVersion;
    }

    public function getContentDivId($storeId = null)
    {
        return $this->scopeConfig->getValue(self::XML_PATH_CONTENT_DIV_ID, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getRefinementDivId($storeId = null)
    {
        return $this->scopeConfig->getValue(self::XML_PATH_REFINEMENT_DIV_ID, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * This is only to determine if the search should be visible on our page or not.
     */
    public function showInNxtSearchPage($storeId = null)
    {
        return ($this->isEnabled($storeId = null) || $this->isDemo($storeId = null));
    }

    /**
     * Determines if the search box takeover happens everywhere or not.
     */
    public function showEverywhere($storeId = null)
    {
        return $this->isEnabled($storeId = null);
    }

    public function getResultUrl($storeId = null)
    {
        return $this->_urlBuilder->getUrl("nsearch");
    }

    public function getGroupCode()
    {
        $om = ObjectManager::getInstance();
        $customerSession = $om->get('Magento\Customer\Model\Session');
        $group = $om->get('Magento\Customer\Model\Customer\Source\Group');
        $groupList = $group->toOptionArray();
        $groupId = $customerSession->getCustomer()->getGroupId();
        foreach($groupList as $groupItem) {
            if($groupItem['value'] == $groupId) {
                return $groupItem['label'];
            }
        }
        return "Unknown";
    }

    public function getFormKey() {
        /** @var \Magento\Framework\Data\Form\FormKey $formKey */
        $om = $om = ObjectManager::getInstance();
        $formKey = $om->get('Magento\Framework\Data\Form\FormKey');
        return $formKey->getFormKey();
    }
    
    /**
     * @return bool
     */
    public function isCronNextopiaEnabled($storeId = null) {
        
        return (bool)$this->scopeConfig->getValue(self::XML_PATH_CRON_NEXTOPIA_STATUS, ScopeInterface::SCOPE_STORE, $storeId);
    }
    
    /**
     * 
     * @return String
     */
    public function getAuthUser($storeId = null) {
        
        return (string)trim($this->scopeConfig->getValue(self::XML_PATH_AUTH_USER, ScopeInterface::SCOPE_STORE, $storeId));
    }
    
    /**
     * 
     * @return String
     */
    public function getAuthPw($storeId = null) {
        
        return (string)trim($this->scopeConfig->getValue(self::XML_PATH_AUTH_PW, ScopeInterface::SCOPE_STORE, $storeId));
    }

    /**
     * @return bool
     */
    public function isPersonasEnabled($storeId = null) {
        
        return (bool)$this->scopeConfig->getValue(self::XML_PATH_PERSONAS_STATUS, ScopeInterface::SCOPE_STORE, $storeId);
    }
    
    public function isClientOneLineCodeAvailable() {
        
        $url = "cdn.nextopia.net/" . $this->getAjaxVersion() . "/" . $this->getNxtId() . ".js";
        $handle = curl_init($url);
        curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
        curl_exec($handle);
        
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        curl_close($handle);
        
        return ($httpCode === 200)? true: false;
    }
    
    public function getLabelSearchResultPage($storeId = null) {

        return $this->scopeConfig->getValue(self::XML_PATH_LABEL_SEARCH_RESULT_PAGE, ScopeInterface::SCOPE_STORE, $storeId);
    }
}