<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class FileUploader
 *
 * @package Aheadworks\RewardPoints\Model
 */
class FileUploader
{
    /**
     * @var UploaderFactory
     */
    private $uploaderFactory;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var string
     */
    const FILE_DIR = 'aw_rewardpoints/imports';

    /**
     * @param UploaderFactory $uploaderFactory
     * @param Filesystem $filesystem
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        UploaderFactory $uploaderFactory,
        Filesystem $filesystem,
        StoreManagerInterface $storeManager
    ) {
        $this->uploaderFactory = $uploaderFactory;
        $this->storeManager = $storeManager;
        $this->filesystem = $filesystem;
    }

    /**
     * Save file to temp directory
     *
     * @param string $fileId
     * @return array
     * @throws LocalizedException
     */
    public function saveToTmpFolder($fileId)
    {
        try {
            $result = ['file' => '', 'size' => '', 'name' => '', 'path' => ''];
            /** @var \Magento\Framework\Filesystem\Directory\Read $mediaDirectory */
            $mediaDirectory = $this->filesystem
                ->getDirectoryRead(DirectoryList::MEDIA)
                ->getAbsolutePath(self::FILE_DIR);
            /** @var \Magento\MediaStorage\Model\File\Uploader $uploader */
            $uploader = $this->uploaderFactory->create(['fileId' => $fileId]);
            $uploader
                ->setAllowRenameFiles(true)
                ->setAllowedExtensions($this->getAllowedExtensions());
            $result = array_intersect_key($uploader->save($mediaDirectory), $result);

            $result['url'] = $this->getMediaUrl($result['file']);
            $result['full_path'] = $this->getFullPath($result);
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $result;
    }

    /**
     * Get file url
     *
     * @param string $file
     * @return string
     */
    public function getMediaUrl($file)
    {
        $file = ltrim(str_replace('\\', '/', $file), '/');
        return $this->storeManager
            ->getStore()
            ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . self::FILE_DIR . '/' . $file;
    }

    /**
     * Get full file path
     *
     * @param string[] $data
     * @return string
     */
    public function getFullPath($data)
    {
        $DS = DIRECTORY_SEPARATOR;

        $fullPath = '';
        if (isset($data['path']) && $data['path'] && isset($data['file']) && $data['file']) {
            $fullPath = $data['path'] . $DS . $data['file'];
        }
        return $fullPath;
    }

    /**
     * Retrieve allowed extensions
     *
     * @return string[]
     */
    public function getAllowedExtensions()
    {
        return ['csv'];
    }
}
