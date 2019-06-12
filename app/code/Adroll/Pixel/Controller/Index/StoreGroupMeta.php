<?php
namespace Adroll\Pixel\Controller\Index;

use Adroll\Pixel\Helper\Meta as MetaHelper;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;

class StoreGroupMeta extends Action
{
    public function __construct(Context $context, MetaHelper $metaHelper)
    {
        $this->_metaHelper = $metaHelper;
        parent::__construct($context);
    }

    public function execute()
    {
        $r = $this->getRequest();
        $data = $this->_metaHelper->generateStoreGroupMeta($r->getParam('storeGroupId', '0'));
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($data);
    }
}
