<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Model\Source;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;

class Import extends \Magento\Config\Model\Config\Backend\File
{
    protected $_blacklistFactory;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Config\Model\Config\Backend\File\RequestData\RequestDataInterface $requestData,
        Filesystem $filesystem,
        \Amasty\Acart\Model\ResourceModel\BlacklistFactory $blacklistFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_blacklistFactory = $blacklistFactory;

        return parent::__construct(
            $context,
            $registry,
            $config,
            $cacheTypeList,
            $uploaderFactory,
            $requestData,
            $filesystem,
            $resource,
            $resourceCollection,
            $data
        );
    }

    public function beforeSave()
    {
        return $this;
    }

    public function save()
    {
        $value = $this->getValue();
        $tmpName = $this->_requestData->getTmpName($this->getPath());

        $directory = $this->_filesystem->getDirectoryRead(DirectoryList::SYS_TMP);

        $file = $directory->openFile($directory->getRelativePath($tmpName), 'r');

        $emails = [];

        while (($csvLine = $file->readCsv()) !== false) {
            foreach ($csvLine as $email) {
                if (\Zend_Validate::is($email, 'NotEmpty')
                    && \Zend_Validate::is($email, 'EmailAddress')
                ) {
                    $emails[]['customer_email'] = $email;
                }
            }
        }

        $this->_blacklistFactory->create()
            ->saveImportData($emails);

        //        return parent::save();
    }


    protected function _getAllowedExtensions()
    {
        return ['csv', 'txt'];
    }
}
