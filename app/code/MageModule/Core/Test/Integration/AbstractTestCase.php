<?php

namespace MageModule\Core\Test\Integration;

class AbstractTestCase extends \Magento\Framework\TestFramework\Unit\BaseTestCase
{
    /**
     * @var \MageModule\Core\Helper\Data
     */
    private $coreHelper;

    /**
     * @var \MageModule\Core\Helper\File
     */
    private $fileHelper;

    /**
     * @var \Magento\Store\Model\StoreRepositoryFactory
     */
    private $storeRepositoryFactory;

    /**
     * @var \Magento\Store\Model\StoreManager
     */
    private $storeManager;

    /**
     * @var \Magento\Store\Model\StoreFactory
     */
    private $storeFactory;

    /**
     * @var \Magento\Store\Model\WebsiteFactory
     */
    private $websiteFactory;

    /**
     * @var \Magento\Store\Model\WebsiteRepositoryFactory
     */
    private $websiteRepositoryFactory;

    /**
     * @var \Magento\Store\Model\GroupFactory
     */
    private $groupFactory;

    /**
     * @var \Magento\Framework\Module\Dir\Reader
     */
    private $directoryReader;

    /**
     * @var \MageModule\Core\Framework\File\Csv
     */
    private $csvProcessor;

    public function setUp()
    {
        parent::setUp();

        $this->objectManager            = \Magento\TestFramework\ObjectManager::getInstance();
        $this->coreHelper               = $this->objectManager->create(\MageModule\Core\Helper\Data::class);
        $this->fileHelper               = $this->objectManager->create(\MageModule\Core\Helper\File::class);
        $this->storeRepositoryFactory   = $this->objectManager->create(\Magento\Store\Model\StoreRepositoryFactory::class);
        $this->storeManager             = $this->objectManager->create(\Magento\Store\Model\StoreManager::class);
        $this->storeFactory             = $this->objectManager->create(\Magento\Store\Model\StoreFactory::class);
        $this->websiteFactory           = $this->objectManager->create(\Magento\Store\Model\WebsiteFactory::class);
        $this->websiteRepositoryFactory = $this->objectManager->create(\Magento\Store\Model\WebsiteRepositoryFactory::class);
        $this->groupFactory             = $this->objectManager->create(\Magento\Store\Model\GroupFactory::class);
        $this->directoryReader          = $this->objectManager->create(\Magento\Framework\Module\Dir\Reader::class);
        $this->csvProcessor             = $this->objectManager->create(\MageModule\Core\Framework\File\Csv::class);

        /** @var \Magento\Framework\Registry $registry */
        $registry = $this->objectManager->get(\Magento\Framework\Registry::class);
        $registry->unregister('isSecureArea');
        $registry->register('isSecureArea', true);
    }

    /**
     * @return \MageModule\Core\Helper\Data
     */
    protected function getCoreHelper()
    {
        return $this->coreHelper;
    }

    /**
     * @return \MageModule\Core\Helper\File
     */
    protected function getFileHelper()
    {
        return $this->fileHelper;
    }

    /**
     * @return \Magento\Store\Model\StoreRepository
     */
    protected function getStoreRepository()
    {
        return $this->storeRepositoryFactory->create();
    }

    /**
     * @return \Magento\Store\Model\StoreManager
     */
    protected function getStoreManager()
    {
        return $this->storeManager;
    }

    /**
     * @return \Magento\Store\Model\StoreFactory
     */
    protected function getStoreFactory()
    {
        return $this->storeFactory;
    }

    /**
     * @return \Magento\Store\Model\WebsiteFactory
     */
    protected function getWebsiteFactory()
    {
        return $this->websiteFactory;
    }

    /**
     * @return \Magento\Store\Model\WebsiteRepository
     */
    protected function getWebsiteRepository()
    {
        return $this->websiteRepositoryFactory->create();
    }

    /**
     * @return \Magento\Store\Model\GroupFactory
     */
    protected function getGroupFactory()
    {
        return $this->groupFactory;
    }

    /**
     * @return \Magento\Framework\Module\Dir\Reader
     */
    protected function getDirectoryReader()
    {
        return $this->directoryReader;
    }

    /**
     * @return \MageModule\Core\Framework\File\Csv
     */
    protected function getCsvProcessor()
    {
        return $this->csvProcessor;
    }
}
