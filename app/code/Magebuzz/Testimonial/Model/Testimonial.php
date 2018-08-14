<?php
/**
 * @copyright Copyright (c) 2016 www.magebuzz.com
 */
namespace Magebuzz\Testimonial\Model;

use Magento\Framework\Model\AbstractModel;

class Testimonial extends AbstractModel
{
    const XML_PATH_SUBMITTED_EMAIL_TEMPLATE = 'testimonial_config/group_testimonial_email_configuration/config_email_template_posting';
    const XML_PATH_APPROVED_EMAIL_TEMPLATE = 'testimonial_config/group_testimonial_email_configuration/config_email_template_approving';

    const STATUS_APPROVE = 2;
    const STATUS_PENDING = 1;
    const STATUS_DENY    = 0;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    protected $scopeConfig;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    protected $inlineTranslation;

    protected $_testimonialHelper;

    /**
     * Testimonial constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param \Magebuzz\Testimonial\Helper\Data $testimonialHelper
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magebuzz\Testimonial\Helper\Data $testimonialHelper,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = [])
    {
        $this->_storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->_transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->_testimonialHelper = $testimonialHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
        $this->_init('Magebuzz\Testimonial\Model\ResourceModel\Testimonial');
    }

    public function getAvailableStatuses(){
        return [
            self::STATUS_APPROVE => __('Approve'),
            self::STATUS_PENDING => __('Pending'),
            self::STATUS_DENY    => __('Deny')
        ];
    }

    public function sendSubmittedEmailToCustomer()
    {
        $storeId = $this->_storeManager->getStore()->getId();
        $this->_testimonialHelper->setStoreId($storeId);

        $this->inlineTranslation->suspend();

        try {
            //load store amdmin email
            $sender = [
                'email' => $this->scopeConfig->getValue(
                    'trans_email/ident_support/email',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                ),
                'name' => $this->scopeConfig->getValue(
                    'trans_email/ident_support/name',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
            ];

            $replyTo = $this->scopeConfig->getValue(
                'trans_email/ident_support/email',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );

            $transport = $this->_transportBuilder
                ->setTemplateIdentifier($this->scopeConfig->getValue(
                    self::XML_PATH_SUBMITTED_EMAIL_TEMPLATE,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                    $storeId))
                ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $storeId])
                ->setTemplateVars([
                    'testimonial' => $this,
                    'subject' => __('About Your Testimonial')
                ])
                ->setFrom($sender)
                ->addTo($this->getMbTestimonialEmail(), $this->getMbTestimonialName())
                ->setReplyTo($replyTo)
                ->getTransport();

            $transport->sendMessage();
            $this->inlineTranslation->resume();
        }
        catch (Exception $e) {
            //silence is gold
        }
    }

    public function sendApprovedEmailToCustomer()
    {
        $storeId = $this->_storeManager->getStore()->getId();
        $this->_testimonialHelper->setStoreId($storeId);

        $this->inlineTranslation->suspend();

        try {
            //load store amdmin email
            $sender = [
                'email' => $this->scopeConfig->getValue(
                    'trans_email/ident_support/email',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                ),
                'name' => $this->scopeConfig->getValue(
                    'trans_email/ident_support/name',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
            ];

            $replyTo = $this->scopeConfig->getValue(
                'trans_email/ident_support/email',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );

            $transport = $this->_transportBuilder
                ->setTemplateIdentifier($this->scopeConfig->getValue(
                    self::XML_PATH_APPROVED_EMAIL_TEMPLATE,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                    $storeId))
                ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $storeId])
                ->setTemplateVars([
                    'testimonial' => $this,
                    'subject' => __('About Your Testimonial')
                ])
                ->setFrom($sender)
                ->addTo($this->getMbTestimonialEmail(), $this->getMbTestimonialName())
                ->setReplyTo($replyTo)
                ->getTransport();

            $transport->sendMessage();
            $this->inlineTranslation->resume();
        }
        catch (Exception $e) {
            //silence is gold
        }
    }
}