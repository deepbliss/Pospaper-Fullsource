<?php

/**
 * Copyright © 2015 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Controller\Adminhtml\Feeds;

/**
 * Show report action
 */
class Samplereport extends \Wyomind\SimpleGoogleShopping\Controller\Adminhtml\Feeds
{

    /**
     * Execute action
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
                $model->generateXml($request);
                $data = ['data' => $this->sgsHelper->reportToHtml($model->errorReport)];
                $this->getResponse()->representJson($this->_objectManager->create('Magento\Framework\Json\Helper\Data')->jsonEncode($data));
            } catch (\Exception $e) {
                $this->getResponse()->representJson($this->_objectManager->create('Magento\Framework\Json\Helper\Data')->jsonEncode(['error' => $e->getMessage()]));
            }
        } else {
            $this->getResponse()->representJson($this->_objectManager->create('Magento\Framework\Json\Helper\Data')->jsonEncode(['data' => ""]));
        }
    }
}
