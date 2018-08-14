<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Geoip
 */

namespace Amasty\Geoip\Controller\Adminhtml\Geoip;

class Process extends \Amasty\Geoip\Controller\Adminhtml\Geoip
{
    public function execute()
    {
        $result = [];
        try {
            $type = $this->getRequest()->getParam('type');
            $action = $this->getRequest()->getParam('action');
            if ($action == 'import') {
                $filePath = $this->geoipHelper->getCsvFilePath($type, $action);
            } else {
                $filePath = $this->geoipHelper->getFilePath($type, $action);
            }
            $ret = $this->importModel->doProcess($type, $filePath, $action);
            $result['type'] = $type;
            $result['status'] = 'processing';
            $result['position'] = ceil($ret['current_row'] / $ret['rows_count'] * 100);
            if ($action == 'import') {
                if ($type == 'block' && $result['position'] == 100 && $ret['current_row'] + 3 < $ret['rows_count']) {
                    $result['position'] = 99;
                }
            } else {
                if ($result['position'] > 100) {
                    $result['position'] = 100;
                }
            }
        } catch (\Exception $e) {
            $result['error'] = $e->getMessage();
        }

        $this->getResponse()->setBody($this->jsonHelper->jsonEncode($result));
    }
}
