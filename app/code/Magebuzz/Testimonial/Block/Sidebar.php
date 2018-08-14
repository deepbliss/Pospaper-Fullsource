<?php
/**
 * @copyright Copyright (c) 2016 www.magebuzz.com
 */
namespace Magebuzz\Testimonial\Block;

use Magebuzz\Testimonial\Model\TestimonialFactory;
use Magento\Framework\View\Element\Template;

class Sidebar extends Template
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

    protected $_helper;

    protected $_filter;

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
        \Magebuzz\Testimonial\Helper\Data $helper,
        \Magebuzz\Testimonial\Model\ResourceModel\Testimonial\CollectionFactory $collection,
        \Magento\Framework\Filter\FilterManager $filter,
        array $data)
    {
        $this->_filter = $filter;
        parent::__construct($context, $data);
        $this->_helper = $helper;
        $this->testimonialCollection = $collection;
        $this->testimonialFactory = $testimonialFactory;
        $this->setTemplate('sidebar.phtml');
    }

    protected function _toHtml()
    {
        if ($this->_helper->isEnabledInSidebar()) {
            return parent::_toHtml();
        }

        return '';
    }

    public function setLimitChacracter($string)
    {
        return $this->_filter->truncate($string, ['length' => 100, 'etc' => '...', 'remainder' => 'Read more..']);
    }

    public function getTestimonial()
    {
        $fieldSelected = array();
        if ($this->getIsShowEmail() == 1) {
            $fieldSelected[] = 'email';

        }

        $fieldSelected[] = 'created_time';
        $fieldSelected[] = 'testimonial';
        $fieldSelected[] = 'avatar';

        $collection = $this->testimonialFactory->create()->getCollection()
            ->addFieldToFilter('is_active', 2)
            ->addFieldToSelect($fieldSelected)
            ->setPageSize(4)
            ->addOrder('created_time', 'DESC');

        return $collection;
    }

    public function getLinkIndexAction()
    {
        return $this->getUrl('testimonial/index/index');
    }

    public function getImage($image)
    {
        return $this->_helper->getImageUrl($image);
    }
    
    public function _formatDate($dateString)
    {
        $date = new \DateTime($dateString);
        if ($date == new \DateTime('today')) {
            return $this->_localeDate->formatDateTime(
                $date,
                \IntlDateFormatter::NONE,
                \IntlDateFormatter::SHORT
            );
        }
        return $this->_localeDate->formatDateTime(
            $date,
            \IntlDateFormatter::MEDIUM,
            \IntlDateFormatter::MEDIUM
        );
    }
}