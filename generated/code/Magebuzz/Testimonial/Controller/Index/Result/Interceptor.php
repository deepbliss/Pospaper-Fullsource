<?php
namespace Magebuzz\Testimonial\Controller\Index\Result;

/**
 * Interceptor class for @see \Magebuzz\Testimonial\Controller\Index\Result
 */
class Interceptor extends \Magebuzz\Testimonial\Controller\Index\Result implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magebuzz\Testimonial\Model\TestimonialFactory $testimonialFactory, \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList, \Magento\Framework\Image\AdapterFactory $adapterFactory, \Magento\MediaStorage\Model\File\UploaderFactory $uploader, \Magento\Framework\Filesystem $filesystem, \Magebuzz\Testimonial\Helper\Data $helper, \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder, \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Framework\Escaper $escaper, \Magento\Framework\Image\AdapterFactory $imageFactory)
    {
        $this->___init();
        parent::__construct($context, $resultPageFactory, $testimonialFactory, $cacheTypeList, $adapterFactory, $uploader, $filesystem, $helper, $transportBuilder, $inlineTranslation, $scopeConfig, $escaper, $imageFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        if (!$pluginInfo) {
            return parent::dispatch($request);
        } else {
            return $this->___callPlugins('dispatch', func_get_args(), $pluginInfo);
        }
    }
}
