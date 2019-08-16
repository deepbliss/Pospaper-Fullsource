<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Controller\Adminhtml\Rule;

class NewAction extends \Amasty\Acart\Controller\Adminhtml\Rule
{
    /**
     * Create new Rule
     */
    public function execute()
    {
        $this->_forward('edit');
    }
}
