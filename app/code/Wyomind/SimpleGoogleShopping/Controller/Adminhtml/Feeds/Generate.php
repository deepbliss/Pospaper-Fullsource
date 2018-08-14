<?php

/**
 * Copyright © 2015 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Controller\Adminhtml\Feeds;

/**
 * Generate data feed action
 */
class Generate extends \Wyomind\SimpleGoogleShopping\Controller\Adminhtml\Feeds
{

    /**
     * Execute action
     */
    public function execute()
    {

        $request = $this->getRequest();

        $id = $request->getParam('id');
        if ($id === null) {
            $id = $request->getParam('simplegoogleshopping_id');
        }

        $model = $this->_objectManager->create('Wyomind\SimpleGoogleShopping\Model\Feeds');
        $model->limit = 0;

        $model->load($id);

        try {
            $model->generateXml($request);
            $this->messageManager->addSuccess(
                __("The data feed ") . "\"" . $model->getSimplegoogleshoppingFilename() . "\"" . __(" has been generated.")
                . '<br/>' . $this->sgsHelper->generationStats($model)
            );
        } catch (\Exception $e) {
            $this->messageManager->addError(__('Unable to generate the data feed.') . '<br/><br/>' . nl2br($e->getMessage()));
        }


        if ($request->getParam('generate_i')) {
            $resultForward = $this->resultForwardFactory->create();
            $resultForward->setParams(['id' => $id]);
            return $resultForward->forward("edit");
        } else {
            return $this->resultForwardFactory->create()->forward("index");
        }
    }
}
