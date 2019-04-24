<?php


namespace Experius\PageNotFound\Controller\Adminhtml\Pagenotfound;

class NewAction extends \Experius\PageNotFound\Controller\Adminhtml\Pagenotfound
{
    /**
     * New action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Forward $resultForward */
        $resultForward = $this->resultForwardFactory->create();
        return $resultForward->forward('edit');
    }
}
