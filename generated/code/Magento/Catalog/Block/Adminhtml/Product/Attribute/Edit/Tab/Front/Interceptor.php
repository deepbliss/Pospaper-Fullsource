<?php
namespace Magento\Catalog\Block\Adminhtml\Product\Attribute\Edit\Tab\Front;

/**
 * Interceptor class for @see \Magento\Catalog\Block\Adminhtml\Product\Attribute\Edit\Tab\Front
 */
class Interceptor extends \Magento\Catalog\Block\Adminhtml\Product\Attribute\Edit\Tab\Front implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\Block\Template\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Data\FormFactory $formFactory, \Magento\Config\Model\Config\Source\Yesno $yesNo, \Magento\Eav\Block\Adminhtml\Attribute\PropertyLocker $propertyLocker, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $registry, $formFactory, $yesNo, $propertyLocker, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function setForm(\Magento\Framework\Data\Form $form)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setForm');
        if (!$pluginInfo) {
            return parent::setForm($form);
        } else {
            return $this->___callPlugins('setForm', func_get_args(), $pluginInfo);
        }
    }
}
