<?php

/**
 * Magestore.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Magestore
 * @package     Magestore_Megamenu
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */
namespace Magestore\Megamenu\Model;

class ImageUploaderFactory
{
    /**
     * Object Manager instance.
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager = null;

    /**
     * Instance name to create.
     *
     * @var string
     */
    protected $_instanceName = null;

    /**
     * ImageUploaderFactory constructor.
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param string $instanceName
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        $instanceName = 'Magento\MediaStorage\Model\File\Uploader'
    ) {
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
    }


    public function create(array $data = [])
    {
        $uploader = $this->_objectManager->create($this->_instanceName, $data);

        if (!$uploader instanceof \Magento\MediaStorage\Model\File\Uploader) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('The class uploader is invalid !')
            );
        }

        $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);

        /** @var \Magento\Framework\Image\Adapter\AdapterInterface $imageAdapter */
        $imageAdapter = $this->_objectManager->get('Magento\Framework\Image\AdapterFactory')->create();
        $uploader->addValidateCallback('megamenu', $imageAdapter, 'validateUploadFile');
        $uploader->setAllowRenameFiles(true);
        $uploader->setFilesDispersion(true);

        return $uploader;
    }
}
