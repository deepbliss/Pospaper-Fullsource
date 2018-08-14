<?php

/**
 * Copyright © 2015 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Controller\Adminhtml\Feeds;

/**
 * Generate sample action
 */
class Sample extends \Wyomind\SimpleGoogleShopping\Controller\Adminhtml\Feeds
{

    /**
     * Execute Action
     */
    public function execute()
    {

        $request = $this->getRequest();
        $id = $request->getParam('simplegoogleshopping_id');

        $model = $this->sgsModel;
        $model->limit = $this->coreHelper->getStoreConfig('simplegoogleshopping/system/preview');

        $model->setDisplay(true);

        if ($id != 0) {
            try {
                $model->load($id);
                $content = $model->generateXml($request);
                $data = ["data" => $content];
            } catch (\Exception $e) {
                $data = ['error' => __("Unable to generate the data feed<br/>") . nl2br($e->getMessage())];
            }
            $this->getResponse()->representJson($this->_objectManager->create('Magento\Framework\Json\Helper\Data')->jsonEncode($data));
        }
    }
}
