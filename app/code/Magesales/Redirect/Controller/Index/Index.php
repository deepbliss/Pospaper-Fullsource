<?php
namespace Magesales\Redirect\Controller\Index;
class Index extends \Magento\Cms\Controller\Noroute\Index
{
	public function execute()
	{
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
		$storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
		$configValue = $objectManager->create('Magesales\Redirect\Helper\Data')->isEnabled();
		if($configValue)
		{
			header("HTTP/1.1 301 Moved Permanently"); 
			header("Location: ".$storeManager->getStore()->getBaseUrl());
			exit();
		}
		else
		{
			$pageId = $this->_objectManager->get(
            'Magento\Framework\App\Config\ScopeConfigInterface',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
			)->getValue(
				\Magento\Cms\Helper\Page::XML_PATH_NO_ROUTE_PAGE,
				\Magento\Store\Model\ScopeInterface::SCOPE_STORE
			);
			/** @var \Magento\Cms\Helper\Page $pageHelper */
			$pageHelper = $this->_objectManager->get('Magento\Cms\Helper\Page');
			$resultPage = $pageHelper->prepareResultPage($this, $pageId);
			if ($resultPage) {
				$resultPage->setStatusHeader(404, '1.1', 'Not Found');
				$resultPage->setHeader('Status', '404 File not found');
				return $resultPage;
			} else {
				/** @var \Magento\Framework\Controller\Result\Forward $resultForward */
				$resultForward = $this->resultForwardFactory->create();
				$resultForward->setController('index');
				$resultForward->forward('defaultNoRoute');
				return $resultForward;
			}
		}
		//return parent::noRouteAction($coreRoute = null);
    }
}
