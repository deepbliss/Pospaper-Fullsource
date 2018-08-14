<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Plugin\Magento\Framework\View\Layout\ScheduledStructure;

/**
 * Class HelperPlugin
 *
 * @package Aheadworks\RewardPoints\Plugin\Magento\Framework\View\Layout\ScheduledStructure
 */
class HelperPlugin
{
    /**
     * Rename containers with duplicate name - "customer.form.newsletter.extra"
     *
     * @param \Magento\Framework\View\Layout\ScheduledStructure\Helper $subject
     * @param \Closure $proceed
     * @param \Magento\Framework\View\Layout\ScheduledStructure $scheduledStructure
     * @param \Magento\Framework\View\Layout\Element $currentNode
     * @param \Magento\Framework\View\Layout\Element $parentNode
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundScheduleStructure(
        $subject,
        $proceed,
        $scheduledStructure,
        $currentNode,
        $parentNode
    ) {
        $renameDuplicate = 'customer.form.newsletter.extra';
        if ($currentNode->getAttribute('name') == $renameDuplicate) {
            if ($scheduledStructure->hasStructureElement($renameDuplicate)) {
                $currentNode->setAttribute('name', '');
            }
        }
        return $proceed($scheduledStructure, $currentNode, $parentNode);
    }
}
