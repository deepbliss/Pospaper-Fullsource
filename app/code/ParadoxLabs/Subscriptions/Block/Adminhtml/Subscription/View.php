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

namespace ParadoxLabs\Subscriptions\Block\Adminhtml\Subscription;

use ParadoxLabs\Subscriptions\Model\Source\Status;

/**
 * View Class
 */
class View extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var Status
     */
    protected $statusSource;

    /**
     * View constructor.
     *
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param Status $statusSource
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        Status $statusSource,
        array $data
    ) {
        parent::__construct($context, $data);

        $this->registry = $registry;
        $this->statusSource = $statusSource;
    }

    /**
     * Internal constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->buttonList->update('save', 'label', __('Save Subscription'));

        $this->_objectId = 'entity_id';
        $this->_blockGroup = 'ParadoxLabs_Subscriptions';
        $this->_controller = 'adminhtml_subscription';
        $this->_mode = 'view';
    }

    /**
     * Get header text
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        return __('Edit Subscription');
    }

    /**
     * Prepare layout.
     *
     * @return $this
     */
    protected function _preparelayout()
    {
        /** @var \ParadoxLabs\Subscriptions\Model\Subscription $subscription */
        $subscription = $this->registry->registry('current_subscription');

        $this->addButton(
            'save_and_edit',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => ['button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form']],
                ]
            ],
            0,
            1000
        );

        if ($subscription->getStatus() === Status::STATUS_ACTIVE) {
            $this->addButton(
                'bill',
                [
                    'label' => __('Bill Now'),
                    'class' => 'bill',
                    'onclick' => 'setLocation(\'' . $this->escapeUrl($this->getUrl(
                        '*/*/bill',
                        [
                            'entity_id' => $subscription->getId(),
                        ]
                    )) . '\')',
                ],
                0,
                100
            );
        }

        if ($this->statusSource->canSetStatus($subscription, Status::STATUS_ACTIVE)) {
            $this->addButton(
                'activate',
                [
                    'label' => __('Reactivate'),
                    'class' => 'activate',
                    'onclick' => 'setLocation(\'' . $this->escapeUrl($this->getUrl(
                        '*/*/changeStatus',
                        [
                            'status'    => Status::STATUS_ACTIVE,
                            'entity_id' => $subscription->getId(),
                        ]
                    )) . '\')',
                ],
                0,
                200
            );
        }

        if ($this->statusSource->canSetStatus($subscription, Status::STATUS_PAUSED)) {
            $this->addButton(
                'pause',
                [
                    'label' => __('Pause'),
                    'class' => 'pause',
                    'onclick' => 'setLocation(\'' . $this->escapeUrl($this->getUrl(
                        '*/*/changeStatus',
                        [
                            'status'    => Status::STATUS_PAUSED,
                            'entity_id' => $subscription->getId(),
                        ]
                    )) . '\')',
                ],
                0,
                200
            );
        }

        if ($this->statusSource->canSetStatus($subscription, Status::STATUS_CANCELED)) {
            $this->addButton(
                'cancel',
                [
                    'label' => __('Cancel'),
                    'class' => 'cancel',
                    'onclick' => 'setLocation(\'' . $this->escapeUrl($this->getUrl(
                        '*/*/changeStatus',
                        [
                            'status'    => Status::STATUS_CANCELED,
                            'entity_id' => $subscription->getId(),
                        ]
                    )) . '\')',
                ],
                0,
                300
            );
        }

        parent::_prepareLayout();

        return $this;
    }
}
