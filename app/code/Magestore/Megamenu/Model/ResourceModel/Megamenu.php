<?php

/**
 * Magestore.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Magestore
 * @package     Magestore_Megamenu
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */
namespace Magestore\Megamenu\Model\ResourceModel;

/**
 * Resource Model Megamenu
 */
class Megamenu extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    const IMAGE_GALLERY_PATH = 'magestore/megamenu/images/megamenu/gallery';
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('magestore_megamenu_megamenu','megamenu_id');
    }
}
