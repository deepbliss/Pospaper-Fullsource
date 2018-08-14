<?php
/**
 * @copyright Copyright (c) 2016 www.magebuzz.com
 */
namespace Magebuzz\Testimonial\Block;

use Magento\Framework\View\Element\Template;

class CaptchaForm extends Template
{

    protected $_helper;

    protected $_customerSession;

    protected $_testmonialFactory;

    public function __construct(
        Template\Context $context,
        \Magebuzz\Testimonial\Helper\Data $helper,
        \Magento\Customer\Model\Session $customerSession,
        \Magebuzz\Testimonial\Model\TestimonialFactory $testimonialFactory,
        array $data
    ) {
        parent::__construct($context, $data);
        $this->_helper = $helper;
        $this->_customerSession = $customerSession;
        $this->_testmonialFactory = $testimonialFactory;
    }

    protected function _toHtml()
    {
        $html = parent::_toHtml();
        $this->unsetFormData('testimonial_form_data');
        return $html;
    }

    public function getFormAction()
    {
        return $this->getUrl('testimonial/index/result', ['_secure' => true]);
    }

    public function getFormData()
    {
        $formData = $this->_customerSession->getData('testimonial_form_data');
        if(!$formData) {
            $formData = $this->_testmonialFactory->create();
        }
        $formData->setData('', '');

        return $formData;
    }
    
    public function unsetFormData($formData){
        return $this->_customerSession->clearStorage();
    }


    public function getAmountWord()
    {
        return $this->_helper->getAmountWord();
    }


    public function isCaptcha(){
        if($this->_helper->isCaptcha() == 0){
            return 'display: none;';
        }
        return '';
    }
}