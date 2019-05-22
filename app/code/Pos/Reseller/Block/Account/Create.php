<?php

namespace Pos\Reseller\Block\Account;

class Create extends \Magento\Customer\Block\Form\Register
{
    protected function _prepareLayout()
    {
        $this->pageConfig->getTitle()->set(__('Create New Reseller Account'));
        return $this;
    }

    public function getPostActionUrl()
    {
        return $this->getUrl('reseller/account/createPost');
    }
}
