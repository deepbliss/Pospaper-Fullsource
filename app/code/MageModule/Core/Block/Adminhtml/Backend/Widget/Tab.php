<?php
/**
 * Copyright (c) 2018 MageModule: All rights reserved
 *
 * LICENSE: This source file is subject to our standard End User License 
 * Agreeement (EULA) that is available through the world-wide-web at the 
 * following URI: http://www.magemodule.com/magento2-ext-license.html.  
 *
 * If you did not receive a copy of the EULA and are unable to obtain it through 
 * the web, please send a note to admin@magemodule.com so that we can mail 
 * you a copy immediately.
 *
 * @author       MageModule admin@magemodule.com 
 * @copyright   2018 MageModule
 * @license       http://www.magemodule.com/magento2-ext-license.html
 *
 */

namespace MageModule\Core\Block\Adminhtml\Backend\Widget;

class Tab extends \Magento\Backend\Block\Widget\Tab
{
    /**
     * @var string|array|null
     */
    private $resources;

    /**
     * Any string|array|null resource strings passed in will be check for ACL perms before allowing tab to display
     *
     * @param $resources
     *
     * @return $this
     */
    public function setAclResources($resources)
    {
        $this->resources = $resources;

        return $this;
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        $canShow = parent::canShowTab();
        if (is_string($this->resources) && $canShow) {
            $canShow = $this->_authorization->isAllowed($this->resources);
        }

        if (is_array($this->resources) && $canShow) {
            foreach ($this->resources as $resource) {
                if (!$this->_authorization->isAllowed($resource)) {
                    return false;
                }
            }
        }

        return $canShow;
    }
}
