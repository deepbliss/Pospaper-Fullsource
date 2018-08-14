<?php
/**
 * @copyright Copyright (c) 2016 www.magebuzz.com
 */
namespace Magebuzz\Testimonial\Controller\Index;

use Magebuzz\Testimonial\Model\TestimonialFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Filesystem;
use Magento\Framework\View\Result\PageFactory;

class Result extends Action
{

    /**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $cacheTypeList;

    protected $resultPageFactory;
    protected $testimonialFactory;
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
    


    /**
     * Recipient email config path
     */
    const XML_PATH_EMAIL_RECIPIENT = 'contact/email/recipient_email';
    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;

    
    
    protected $directory;
    protected $imageFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        TestimonialFactory $testimonialFactory,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\Image\AdapterFactory $adapterFactory,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploader,
        \Magento\Framework\Filesystem $filesystem,
        \Magebuzz\Testimonial\Helper\Data $helper,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        
        \Magento\Framework\Escaper $escaper,
        \Magento\Framework\Image\AdapterFactory $imageFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        
        $this->testimonialFactory = $testimonialFactory;
        $this->cacheTypeList = $cacheTypeList;
        $this->adapterFactory = $adapterFactory;
        $this->uploader = $uploader;
        $this->_filesystem = $filesystem;
        $this->_helper = $helper;
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
        
        $this->escaper = $escaper;
        $this->imageFactory = $imageFactory;

    }

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
                    $imageAdapter = $this->adapterFactory->create();
                    $uploader->addValidateCallback('image', $imageAdapter, 'validateUploadFile');
                    $uploader->setAllowRenameFiles(true);
                    $mediaDirectory = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
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

            // Check approved
            if ($this->_helper->isApprove() == 1) {
                $model->setIsActive(2);
                if($this->_helper->isApproveEmail() ==1){
                    //$model->sendApprovedEmailToCustomer();
                }
            }

            try {
                $model->save();

                // Start send email
                if($this->_helper->isSubmitEmail() ==1){
                    //$model->sendSubmittedEmailToCustomer();
                }
                // End send email

                $this->cacheTypeList->invalidate('full_page');
                $this->messageManager->addSuccess(__($this->_helper->getMessageThankYou()));
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

            return $resultRedirect->setPath('*/*/index', ['mb_testimonial_id' => $this->getRequest()->getParam('mb_testimonial_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    public function imageResize($data)
    {
        $image = $data;

        $absPath = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('magebuzz/testimonial/images/') . $image;

        $imageResized = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('magebuzz/testimonial/images/resized/') . $image;

        $imageResize = $this->imageFactory->create();

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


        $resizedURL = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'magebuzz/testimonial/images/resized/' . $image;

    }
}