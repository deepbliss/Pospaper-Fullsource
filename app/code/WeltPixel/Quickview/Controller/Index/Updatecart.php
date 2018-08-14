<?php
namespace WeltPixel\Quickview\Controller\Index;

class Updatecart extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {
        if (!$this->getRequest()->isAjax()) {
            $this->_redirect('/');
            return;
        }

        $jsonData = json_encode(array('result' => true));
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody($jsonData);
    }
}
