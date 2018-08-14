<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Geoip
 */

namespace Amasty\Geoip\Controller\Adminhtml\Geoip;

class StartDownloading extends \Amasty\Geoip\Controller\Adminhtml\Geoip
{
    public function execute()
    {
        $result = [];
        try {
            $actionType = 'download_and_import';
            $type = $this->getRequest()->getParam('type');
            $url = $this->_getFileUrl($type);
            $dir = $this->geoipHelper->getDirPath($actionType);
            $newFilePath = $this->geoipHelper->getFilePath($type, $actionType);
            $needToUpdate = true;

            if (file_exists($newFilePath)) {
                $hashUrl = $this->getHashUrl($type);
                if ($hashUrl
                    && hash_file('md5', $newFilePath) == file_get_contents($hashUrl)
                    && $this->geoipHelper->isDone(false)
                ) {
                    $needToUpdate = false;
                } else {
                    unlink($newFilePath);
                }
            }

            if ($needToUpdate && !file_exists($dir)) {
                mkdir($dir, 0770, true);
            }

            if ($needToUpdate) {
                $source = fopen($url, 'r');
                $dest   = fopen($newFilePath, 'w');
                stream_copy_to_stream($source, $dest);
                $result['status'] = 'finish_downloading';
            } else {
                $result['status'] = 'done';
            }
            $result['file'] = $this->geoipHelper->_geoipRequiredFiles[$type];

        } catch (\Exception $e) {
            $result['error'] = $e->getMessage();
        }

        $this->getResponse()->setBody($this->jsonHelper->jsonEncode($result));
    }

    protected function _getFileUrl($type)
    {
        $url = '';
        if ($type == 'block') {
            $url = $this->geoipHelper->getUrlBlockFile();
        } elseif ($type == 'location') {
            $url = $this->geoipHelper->getUrlLocationFile();
        }

        return $url;
    }

    /**
     * @param string $type
     *
     * @return string
     */
    private function getHashUrl($type)
    {
        switch ($type) {
            case 'block':
                return $this->geoipHelper->getHashUrlBlock();
            case 'location':
                return $this->geoipHelper->getHashUrlLocation();
        }

        return '';
    }
}
