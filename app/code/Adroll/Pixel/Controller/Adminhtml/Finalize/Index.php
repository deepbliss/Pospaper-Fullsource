<?php
namespace Adroll\Pixel\Controller\Adminhtml\Finalize;

use Adroll\Pixel\Helper\Config as ConfigHelper;
use Magento\Backend\App\Action;
use Magento\Backend\Model\UrlInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Store\Model\StoreManagerInterface;

class Index extends Action
{
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        ConfigHelper $configHelper
    )
    {
        $this->_storeManager = $storeManager;
        $this->_configHelper = $configHelper;
        parent::__construct($context);
    }

    public function execute()
    {
        $r = $this->getRequest();

        $group = $this->_storeManager->getGroup($r->getPostValue('store_group_id', -1));
        $advertisableName = $r->getPostValue('advertisable_name');
        $advertisableEid = $r->getPostValue('advertisable');
        $pixelEid = $r->getPostValue('pixel');

        if ($r->isPost() && $advertisableEid && $pixelEid && $group !== false) {
            $existentGroup = $this->_configHelper->getGroupForAdvertisableEid($advertisableEid);

            if ($existentGroup !== null && $existentGroup->getId() != $group->getId()) {
                $this->_configHelper->uninstallPixel($existentGroup->getId());
            }

            $this->_configHelper->setAdvertisableName($group->getId(), $advertisableName);
            $this->_configHelper->setAdvertisableEid($group->getId(), $advertisableEid);
            $this->_configHelper->setPixelEid($group->getId(), $pixelEid);

            $url = ConfigHelper::ADROLL_BASE_URL . '/ecommerce/magento2/finalize?' . http_build_query(array(
                    'advertisable' => $advertisableEid,
                    'store_group_id' => $group->getId()
                ));
        } else {
            $url = $this->_backendUrl->getUrl('adminhtml/dashboard/index');
        }

        $result = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $result->setUrl($url);
        return $result;
    }
}
