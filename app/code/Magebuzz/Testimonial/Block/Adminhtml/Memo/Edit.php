<?php
/**
 * @copyright Copyright (c) 2016 www.magebuzz.com
 */
namespace Magebuzz\Testimonial\Block\Adminhtml\Memo;

use Magento\Backend\Block\Widget\Form\Container;

class Edit extends Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize staff grid edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'mb_testimonial_id';
        $this->_blockGroup = 'Magebuzz_Testimonial';
        $this->_controller = 'adminhtml_memo';

        parent::_construct();


        if ($this->_isAllowedAction('Magebuzz_Testimonial::save')) {

            $this->buttonList->update('save', 'label', __('Save Testimonial'));
            $this->buttonList->add(
                'saveandcontinue',
                [
                    'label' => __('Save and Continue Edit'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                        ],
                    ]
                ],
                -100
            );
            
        } else {
            $this->buttonList->remove('save');
        }

        if ($this->_isAllowedAction('Magebuzz_Testimonial::post_delete')) {
            $this->buttonList->update('delete', 'label', __('Delete Testimonial'));
        } else {
            $this->buttonList->remove('delete');
        }

        if ($this->_coreRegistry->registry('testimonial_memo')->getId()) {
            $this->buttonList->remove('reset');
        }
    }

    /**
     * Retrieve text for header element depending on loaded blocklist
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('testimonial_memo')->getId()) {
            return __("Edit Testimonial '%1'", $this->escapeHtml($this->_coreRegistry->registry('testimonial_memo')->getTitle()));
        } else {
            return __('New Testimonial');
        }
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('testimonial/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '']);
    }
}