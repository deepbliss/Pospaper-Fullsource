<?php

/**
 * Copyright © 2015 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Controller\Adminhtml\Feeds;

/**
 * Action to retrieve the categories in autocomplete form
 */
class Categories extends \Wyomind\SimpleGoogleShopping\Controller\Adminhtml\Feeds
{

    /**
     * Execute action
     */
    public function execute()
    {

        $file = $this->getRequest()->getParam("file");

        if (!$this->directoryRead->isFile("data/Google/Taxonomies/" . $file)) {
            $msg = "File data/Google/taxonomy.txt doesn't exist !";
            $this->getResponse()->representJson($this->_objectManager->get('Magento\Framework\Json\Helper\Data')->jsonEncode([['id' => $msg, 'label' => $msg, 'value' => $msg]]));
        }

        if (!$this->directoryRead->isReadable("data/Google/Taxonomies/" . $file)) {
            $msg = "File data/Google/taxonomy.txt is not readable !";
            $this->getResponse()->representJson($this->_objectManager->get('Magento\Framework\Json\Helper\Data')->jsonEncode([['id' => $msg, 'label' => $msg, 'value' => $msg]]));
        }

        $content = explode("\n", $this->directoryRead->readFile("data/Google/Taxonomies/" . $file));
        $res = [];
        foreach ($content as $line) {
            if ($this->getRequest()->getParam('term') === "" || stripos($line, $this->getRequest()->getParam('term')) !== false) {
                $res[] = ['id' => addslashes(trim($line)), 'label' => (trim($line)), 'value' => (trim($line))];
            }
        }

        $this->getResponse()->representJson($this->_objectManager->get('Magento\Framework\Json\Helper\Data')->jsonEncode($res));
    }
}
