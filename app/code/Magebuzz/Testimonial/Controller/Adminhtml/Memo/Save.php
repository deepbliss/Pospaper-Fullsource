<?php
/**
 * @copyright Copyright (c) 2016 www.magebuzz.com
 */

namespace Magebuzz\Testimonial\Controller\Adminhtml\Memo;

use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $cacheTypeList;

    /**
     * @var \Magento\Backend\Helper\Js
     */
    protected $jsHelper;

    const ADMIN_RESOURCE = 'Magebuzz_Testimonial::save';

    /**
     * @var \Magento\Framework\Image\AdapterFactory
     */
    protected $adapterFactory;
    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $uploader;
    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;


    protected $_filesystem;
    protected $_storeManager;
    protected $_directory;
    protected $_imageFactory;

    /**
     * @param Action\Context $context
     * @param \Magento\Backend\Helper\Js $jsHelper
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Backend\Helper\Js $jsHelper,
        \Magento\Framework\Image\AdapterFactory $adapterFactory,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploader,
        \Magento\Framework\Filesystem $filesystem,

        \Magento\Store\Model\StoreManagerInterface $storeManager,
        //\Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Image\AdapterFactory $imageFactory
    )
    {
        $this->_filesystem = $filesystem;
        $this->_storeManager = $storeManager;
        $this->_directory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $this->_imageFactory = $imageFactory;

        $this->adapterFactory = $adapterFactory;
        $this->uploader = $uploader;
        $this->filesystem = $filesystem;
        $this->cacheTypeList = $cacheTypeList;
        parent::__construct($context);
        $this->jsHelper = $jsHelper;
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(self::ADMIN_RESOURCE);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {

            //start block upload image
            if (isset($_FILES['avatar']) && isset($_FILES['avatar']['name']) && strlen($_FILES['avatar']['name'])) {
                /*
                * Save image upload
                */
                try {
                    $base_media_path = 'magebuzz/testimonial/images/';
                    $uploader = $this->uploader->create(
                        ['fileId' => 'avatar']
                    );
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                    $uploader->setAllowRenameFiles(true);
                    $imageAdapter = $this->adapterFactory->create();
                    $uploader->addValidateCallback('avatar', $imageAdapter, 'validateUploadFile');
                    $uploader->setAllowRenameFiles(true);
                    $mediaDirectory = $this->filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);

                    $result = $uploader->save(
                        $mediaDirectory->getAbsolutePath($base_media_path)
                    );
                    // Resize and keep save new folder //
                    $this->imageResize($_FILES['avatar']['name']);
                    // Resize and keep save new folder //

                    $data['avatar'] = $result['name'];
                } catch (\Exception $e) {
                    if ($e->getCode() == 0) {
                        $this->messageManager->addError($e->getMessage());
                    }
                }
            }
            //end block upload image

            /** @var \Magebuzz\Testimonial\Model\Testimonial $model */
            $model = $this->_objectManager->create('Magebuzz\Testimonial\Model\Testimonial');

            $id = $this->getRequest()->getParam('mb_testimonial_id');
            if ($id) {
                $model->load($id);
            }

            $model->setData($data);

            try {
                $model->save();
                $this->cacheTypeList->invalidate('full_page');
                $this->messageManager->addSuccess(__('You saved this Testimonial.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['mb_testimonial_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Testimonial.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['mb_testimonial_id' => $this->getRequest()->getParam('mb_testimonial_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    public function imageResize($data)
    {
        $image = $data;

        $absPath = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('magebuzz/testimonial/images/') . $image;

        $imageResized = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('magebuzz/testimonial/images/resized/') . $image;

        $imageResize = $this->_imageFactory->create();
        
        $imageResize->open($absPath);

        $resizeImage = 400;

        // get origin width image
        $widthOld = $imageResize->getOriginalWidth();

        // get origin height image
        $heightOld = $imageResize->getOriginalHeight();
        $scaleWidth = ($widthOld/$heightOld);
        $scaleHeight = ($heightOld/$widthOld);

        if($widthOld > $heightOld)
        {
            $imageResize->resize(null, $resizeImage);
            $newWidth = round($scaleWidth * $resizeImage);
            $imageResize->crop(0, ($newWidth - $resizeImage) / 2, ($newWidth - $resizeImage) / 2, 0);
        }else{
            $imageResize->resize($resizeImage, null);
            $newHeight = round($scaleHeight * $resizeImage);
            $imageResize->crop(($newHeight - $resizeImage) / 2, 0, 0, ($newHeight - $resizeImage) / 2);
        }
        $dest = $imageResized;

        $imageResize->save($dest);


        $resizedURL = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'magebuzz/testimonial/images/resized/' . $image;

    }
}