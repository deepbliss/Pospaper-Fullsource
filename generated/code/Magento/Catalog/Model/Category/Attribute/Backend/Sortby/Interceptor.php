<?php
namespace Magento\Catalog\Model\Category\Attribute\Backend\Sortby;

/**
 * Interceptor class for @see \Magento\Catalog\Model\Category\Attribute\Backend\Sortby
 */
class Interceptor extends \Magento\Catalog\Model\Category\Attribute\Backend\Sortby implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->___init();
        parent::__construct($scopeConfig);
    }

    /**
     * {@inheritdoc}
     */
    public function validate($object)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'validate');
        if (!$pluginInfo) {
            return parent::validate($object);
        } else {
            return $this->___callPlugins('validate', func_get_args(), $pluginInfo);
        }
    }
}
