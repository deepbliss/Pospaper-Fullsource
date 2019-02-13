<?php
/**
 * Paradox Labs, Inc.
 * http://www.paradoxlabs.com
 * 717-431-3330
 *
 * Need help? Open a ticket in our support system:
 *  http://support.paradoxlabs.com
 *
 * @author      Ryan Hoerr <info@paradoxlabs.com>
 * @license     http://store.paradoxlabs.com/license.html
 */

namespace ParadoxLabs\Subscriptions\Model\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * Agent Class
 */
class Agent extends AbstractSource implements OptionSourceInterface
{
    /**
     * @var \Magento\User\Model\ResourceModel\User\CollectionFactory
     */
    protected $agentCollectionFactory;

    /**
     * @var array
     */
    protected $agents;

    /**
     * Agent constructor.
     *
     * @param \Magento\User\Model\ResourceModel\User\CollectionFactory $agentCollectionFactory
     */
    public function __construct(
        \Magento\User\Model\ResourceModel\User\CollectionFactory $agentCollectionFactory
    ) {
        $this->agentCollectionFactory = $agentCollectionFactory;
    }

    /**
     * Get possible period values.
     *
     * @return array
     */
    public function getOptionArray()
    {
        if ($this->agents === null) {
            $this->agents = [
                '-1' => 'Customer',
                '0'  => '-',
            ];

            /** @var \Magento\User\Model\ResourceModel\User\Collection $agentsCollection */
            $agentsCollection = $this->agentCollectionFactory->create();
            /** @var \Magento\User\Model\User $user */
            foreach ($agentsCollection as $user) {
                $this->agents[$user->getId()] = sprintf('%s (%s)', $user->getName(), $user->getUserName());
            }
        }

        return $this->agents;
    }

    /**
     * Retrieve option array with empty value
     *
     * @return string[]
     */
    public function getAllOptions()
    {
        return $this->toOptionArray();
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $opts = [];

        foreach ($this->getOptionArray() as $key => $value) {
            $opts[] = [
                'label' => $value,
                'value' => $key,
            ];
        }

        return $opts;
    }
}
