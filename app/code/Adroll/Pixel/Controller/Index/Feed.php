<?php
namespace Adroll\Pixel\Controller\Index;

use Adroll\Pixel\Helper\Feed as FeedHelper;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;

class Feed extends Action
{
    public function __construct(Context $context, FeedHelper $feedHelper)
    {
        $this->_feedHelper = $feedHelper;
        parent::__construct($context);
    }

    public function execute()
    {
        $r = $this->getRequest();
        $data = $this->_feedHelper->generateProductFeed(
            $r->getParam('currencyCode', 'USD'),
            $r->getParam('storeCode', 'default'),
            (int) $r->getParam('page', '1')
        );
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($data);
    }
}
