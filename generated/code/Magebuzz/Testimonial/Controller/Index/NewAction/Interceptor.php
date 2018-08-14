<?php
namespace Magebuzz\Testimonial\Controller\Index\NewAction;

/**
 * Interceptor class for @see \Magebuzz\Testimonial\Controller\Index\NewAction
 */
class Interceptor extends \Magebuzz\Testimonial\Controller\Index\NewAction implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $pageFactory, \Magebuzz\Testimonial\Helper\Data $dataHelper, \Magebuzz\Testimonial\Model\TestimonialFactory $testimonialFactory)
    {
        $this->___init();
        parent::__construct($context, $pageFactory, $dataHelper, $testimonialFactory);
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
