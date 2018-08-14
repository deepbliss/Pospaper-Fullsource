<?php

namespace Magehit\Bestsellerproducts\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper {

    /**
     * System configuration values
     *
     * @var array
     */
    protected $_config;

    /**
     * Store manager interface
     *
     */
    protected $_storeManager;

    /**
     * Product interface
     *
     */
    protected $_productFactory;

    /**
     * Initialize
     *
     * @param Magento\Framework\App\Helper\Context $context
     * @param Magento\Catalog\Model\ProductFactory $productFactory
     * @param Magento\Store\Model\StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(
    \Magento\Framework\App\Helper\Context $context, \Magento\Catalog\Model\ProductFactory $productFactory, \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->_productFactory = $productFactory;
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * Get Breadcrumbs for current controller action
     *
     * @param \Magento\Framework\View\Result\Page $resultPage
     * @return void
     */
    public function getBreadcrumbs(\Magento\Framework\View\Result\Page $resultPage) {
        $breadcrumbs = $resultPage->getLayout()->getBlock('breadcrumbs');
        $breadcrumbs->addCrumb(
			'home', [
					'label' => __('Home'),
					'title' => __('Go to Home Page'),
					'link' => $this->_storeManager->getStore()->getBaseUrl()
					]
        );
        $breadcrumbs->addCrumb(
                'cms_page', ['label' => __('Bestseller Product'), 'title' => __('Bestseller Product')]
        );
    }

}
