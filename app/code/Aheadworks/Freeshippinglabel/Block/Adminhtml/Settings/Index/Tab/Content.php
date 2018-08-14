<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Freeshippinglabel\Block\Adminhtml\Settings\Index\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Store\Model\System\Store as SystemStore;
use Magento\Framework\Registry;

/**
 * Class Content
 * @package Aheadworks\Freeshippinglabel\Block\Adminhtml\Settings\Index\Tab
 *
 * @method string getLabel()
 * @method string getContentType()
 */
class Content extends \Magento\Backend\Block\Template
{
    /**
     * {@inheritdoc}
     */
    protected $_template = 'Aheadworks_Freeshippinglabel::label/edit/content.phtml';

    /**
     * @var SystemStore
     */
    private $systemStore;

    /**
     * @var Registry
     */
    private $coreRegistry;

    /**
     * @param Context $context
     * @param SystemStore $systemStore
     * @param Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        Context $context,
        SystemStore $systemStore,
        Registry $coreRegistry,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->systemStore = $systemStore;
        $this->coreRegistry = $coreRegistry;
    }

    /**
     * Is in single store mode
     *
     * @return bool
     */
    public function isSingleStoreMode()
    {
        return $this->_storeManager->isSingleStoreMode();
    }

    /**
     * Get stores options
     *
     * @return array
     */
    public function getStoresOptions()
    {
        return $this->systemStore->getStoreValuesForForm(false, true);
    }

    /**
     * Get content items
     *
     * @return array
     */
    public function getContentItems()
    {
        $contentItems = $this->coreRegistry->registry('aw_fslabel_label_content') ? : [];
        $contentTypeItems = [];
        foreach ($contentItems as $contentItem) {
            if ($contentItem['content_type'] == $this->getContentType()) {
                $contentTypeItems[] = $contentItem;
            }
        }
        return $contentTypeItems;
    }
}
