<?php

namespace Shreeji\ImportExportReviews\Block\Adminhtml\ImportExport;

class File extends \Magento\Backend\Block\Template {

    /**
     * @var string
     */
    protected $_template = 'importexport/container.phtml';

    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    protected $_backendUrl;

    /**
     * @var \Magento\Framework\App\Request\Http 
     */
    protected $_request;

    /**
     * 
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Model\UrlInterface $backendUrl
     * @param \Magento\Framework\App\Request\Http $_request
     * @param array $data
     */
    public function __construct(
    \Magento\Backend\Block\Template\Context $context, \Magento\Backend\Model\UrlInterface $backendUrl, \Magento\Framework\App\Request\Http $_request, array $data = []
    ) {
        $this->_backendUrl = $backendUrl;
        $this->_request = $_request;
        parent::__construct($context, $data);
    }

    /**
     * return form post action URL
     * 
     * @return string
     */
    public function getPostUrl() {
        return $this->_backendUrl->getUrl("*/*/Importreviews");
    }

    /**
     * return form post action URL
     * 
     * @return string
     */
    public function getExportUrl() {
        return $this->_backendUrl->getUrl("*/*/Exportreviews");
    }

    /**
     * return all website list with it's code
     * 
     * @return array()
     */
    public function getAllWebsites() {
        $websites = $this->_storeManager->getWebsites();
        $websitearray = array();
        foreach (array_keys($websites) as $websiteId) {
            $website = $websites[$websiteId];
            $websitearray[$website->getId()] = $website->getName();
        }
        return $websitearray;
    }

}
