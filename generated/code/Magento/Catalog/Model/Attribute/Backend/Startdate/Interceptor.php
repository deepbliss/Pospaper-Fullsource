<?php
namespace Magento\Catalog\Model\Attribute\Backend\Startdate;

/**
 * Interceptor class for @see \Magento\Catalog\Model\Attribute\Backend\Startdate
 */
class Interceptor extends \Magento\Catalog\Model\Attribute\Backend\Startdate implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate, \Magento\Framework\Stdlib\DateTime\DateTime $date)
    {
        $this->___init();
        parent::__construct($localeDate, $date);
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
