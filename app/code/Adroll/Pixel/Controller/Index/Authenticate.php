<?php
namespace Adroll\Pixel\Controller\Index;

use Magento\Backend\Model\UrlInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;

class Authenticate extends Action
{
    public function __construct(Context $context, UrlInterface $urlInterface)
    {
        $this->_context = $context;
        $this->_urlInterface = $urlInterface;
        parent::__construct($context);
    }

    public function execute()
    {
        $query = $this->getRequest()->getQuery();
        if (!isset($query['advertisable'], $query['pixel'], $query['name'])) {
            $url = $this->_urlInterface->getUrl('adminhtml/dashboard/index');
        } else {
            $url = $this->_urlInterface->getUrl('adroll/authenticate', array('_query' => http_build_query($query)));
        }
        $result = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $result->setUrl($url);
        return $result;
    }
}
