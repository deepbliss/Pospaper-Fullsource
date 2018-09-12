<?php
namespace Magento\Framework\View\Layout\ScheduledStructure\Helper;

/**
 * Interceptor class for @see \Magento\Framework\View\Layout\ScheduledStructure\Helper
 */
class Interceptor extends \Magento\Framework\View\Layout\ScheduledStructure\Helper implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Psr\Log\LoggerInterface $logger, \Magento\Framework\App\State $state)
    {
        $this->___init();
        parent::__construct($logger, $state);
    }

    /**
     * {@inheritdoc}
     */
    public function scheduleStructure(\Magento\Framework\View\Layout\ScheduledStructure $scheduledStructure, \Magento\Framework\View\Layout\Element $currentNode, \Magento\Framework\View\Layout\Element $parentNode)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'scheduleStructure');
        if (!$pluginInfo) {
            return parent::scheduleStructure($scheduledStructure, $currentNode, $parentNode);
        } else {
            return $this->___callPlugins('scheduleStructure', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function scheduleElement(\Magento\Framework\View\Layout\ScheduledStructure $scheduledStructure, \Magento\Framework\View\Layout\Data\Structure $structure, $key)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'scheduleElement');
        if (!$pluginInfo) {
            return parent::scheduleElement($scheduledStructure, $structure, $key);
        } else {
            return $this->___callPlugins('scheduleElement', func_get_args(), $pluginInfo);
        }
    }
}
