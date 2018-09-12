<?php

namespace Pos\Custommodule\Controller\Adminhtml\Tiers;

use Magento\Framework\Controller\ResultFactory;

class Index extends \Pos\Custommodule\Controller\Adminhtml\Index
{
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $resultPage->addContent(
            $resultPage->getLayout()->createBlock('Pos\Custommodule\Block\Adminhtml\Tiers\RemoveHeader')
        );
        $resultPage->addContent(
            $resultPage->getLayout()->createBlock('Pos\Custommodule\Block\Adminhtml\Tiers\Remove')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Remove Pricing Tiers'));
        return $resultPage;
    }
}
