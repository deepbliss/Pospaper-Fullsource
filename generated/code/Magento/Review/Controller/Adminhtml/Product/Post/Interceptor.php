<?php
namespace Magento\Review\Controller\Adminhtml\Product\Post;

/**
 * Interceptor class for @see \Magento\Review\Controller\Adminhtml\Product\Post
 */
class Interceptor extends \Magento\Review\Controller\Adminhtml\Product\Post implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Registry $coreRegistry, \Magento\Review\Model\ReviewFactory $reviewFactory, \Magento\Review\Model\RatingFactory $ratingFactory)
    {
        $this->___init();
        parent::__construct($context, $coreRegistry, $reviewFactory, $ratingFactory);
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
