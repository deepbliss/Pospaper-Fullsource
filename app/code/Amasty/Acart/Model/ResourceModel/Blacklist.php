<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\VersionControl\AbstractDb;

class Blacklist extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('amasty_acart_blacklist', 'blacklist_id');
    }

    /**
     * @param $emails
     */
    public function saveImportData($emails)
    {
        if ($emails) {
            $this->getConnection()->insertOnDuplicate($this->getMainTable(), $emails, ["customer_email"]);
        }
    }
}
