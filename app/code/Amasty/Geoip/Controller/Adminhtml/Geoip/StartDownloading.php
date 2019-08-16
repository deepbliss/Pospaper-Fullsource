<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Geoip
 */

namespace Amasty\Geoip\Controller\Adminhtml\Geoip;

use Amasty\Geoip\Controller\Adminhtml\GeoipAbstract;

/**
 * Class StartDownloading
 */
class StartDownloading extends GeoipAbstract
{
    public function execute()
    {
        $result = [];
        try {
            $type = $this->getRequest()->getParam('type');
            $url = $this->_getFileUrl($type);
            $dir = $this->geoipHelper->getDirPath();
            $newFilePath = $this->geoipHelper->getCsvFilePath($type);
            $needToUpdate = true;
            $needToDownload = true;

            if ($this->driverFile->isExists($newFilePath)) {
                $hashUrl = $this->getHashUrl($type);
                if ($hashUrl
                    && hash_file('md5', $newFilePath) == trim($this->driverFile->fileGetContents($hashUrl))
                ) {
                    $needToDownload = false;
                    if ($this->geoipHelper->isDone(false)) {
                        $needToUpdate = false;
                    }
                } else {
                    $this->driverFile->deleteFile($newFilePath);
                }
            }

            if ($needToUpdate && !$this->driverFile->isExists($dir)) {
                $this->driverFile->createDirectory($dir, 0770);
            }

            if ($needToUpdate) {
                if ($needToDownload) {
                    $source = $this->driverFile->fileOpen($url, 'r');
                    $dest   = $this->driverFile->fileOpen($newFilePath, 'w');
                    //@codingStandardsIgnoreStart
                    stream_copy_to_stream($source, $dest);
                    //@codingStandardsIgnoreEnd
                }
                $result['status'] = 'finish_downloading';
            } else {
                $result['status'] = 'done';
            }
            $result['file'] = $this->geoipHelper->_geoipCsvFiles[$type];

        } catch (\Exception $e) {
            $result['error'] = $e->getMessage();
        }

        $this->getResponse()->setBody($this->jsonHelper->jsonEncode($result));
    }

    protected function _getFileUrl($type)
    {
        $url = '';
        switch ($type) {
            case 'block':
                $url = $this->geoipHelper->getUrlBlockFile();
                break;
            case 'location':
                $url = $this->geoipHelper->getUrlLocationFile();
                break;
            case 'block_v6':
                $url = $this->geoipHelper->getUrlBlockV6File();
                break;
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
            case 'block_v6':
                return $this->geoipHelper->getHashUrlBlockV6();
        }

        return '';
    }
}
