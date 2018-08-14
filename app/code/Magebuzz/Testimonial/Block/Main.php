<?php
/**
 * @copyright Copyright (c) 2016 www.magebuzz.com
 */
namespace Magebuzz\Testimonial\Block;

use Magebuzz\Testimonial\Model\TestimonialFactory;
use Magento\Framework\View\Element\Template;

class Main extends Template
{
    /**
     * @var TestimonialFactory
     * @return object
     */
    protected $testimonialFactory;

    /**
     * @var \Magebuzz\Testimonial\Model\ResourceModel\Testimonial\CollectionFactory
     * @return array
     */
    protected $testimonialCollection;

    /**
     * @var \Magebuzz\Testimonial\Helper\Data
     */
    protected $_helper;

    /**
     * Main constructor.
     * @param Template\Context $context
     * @param TestimonialFactory $testimonialFactory
     * @param \Magebuzz\Testimonial\Model\ResourceModel\Testimonial\CollectionFactory $collection
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        TestimonialFactory $testimonialFactory,
        \Magebuzz\Testimonial\Model\ResourceModel\Testimonial\CollectionFactory $collection,
        \Magebuzz\Testimonial\Helper\Data $helper,
        array $data
    )
    {
        parent::__construct($context, $data);
        $this->testimonialCollection = $collection;
        $this->testimonialFactory = $testimonialFactory;
        $this->_helper = $helper;
        $this->setTemplate('index.phtml');

        $fieldSelect = [
            'avatar',
            'testimonial',
            'company',
            'name',
            'created_time'
        ];

        $collection = $this->testimonialCollection->create()
            ->setOrder('name', 'ASC')
            ->addFieldToSelect($fieldSelect)
            ->addFieldToFilter('is_active', 2);
        
        $this->setCollection($collection);
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        /** @var \Magento\Theme\Block\Html\Pager */
        $pager = $this->getLayout()->createBlock(
            'Magento\Theme\Block\Html\Pager',
            'magebuzz.testimonial.pager'
        );

        $pager->setAvailableLimit($this->getPerPage());
        $pager->setShowPerPage(true);
        $pager->setCollection($this->getCollection());

        $this->setChild('pager', $pager);
        $this->getCollection()->load();

        return $this;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function isEnabledInPaging()
    {
        return $this->_helper->isEnabledInPaging();
    }

    public function getPerPage()
    {
        $values = trim($this->_helper->getPerPage());
        $tmp = explode(",", $values);
        $tmp2 = [];
        foreach ($tmp as $key => $value) {
            $tmp2[$value] = $value;
        }
        return $tmp2;
    }

    public function isAllowSubmit()
    {
        return $this->_helper->isAllowSubmit();
    }

    public function checkLogin()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->create('Magento\Customer\Model\Session');

        if ($customerSession->isLoggedIn()) {
            $customerSession->getCustomerId();  // get Customer Id
            $customerSession->getCustomerGroupId();
            $customerSession->getCustomer();
            $customerSession->getCustomerData();

            return true;
        }
        return false;
    }

    public function getImage($image)
    {
        $html = '';
        if ($image) {
            $html .= '<img src="' . $this->_helper->getImageUrl($image) . '" />';
        }
        return $html;
    }

    public function getNewUrl()
    {
        return $this->getUrl('testimonial/index/new');
    }
}