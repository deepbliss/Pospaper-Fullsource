<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Block\Adminhtml\Rule\Edit;

use Amasty\Acart\Controller\RegistryConstants;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected $_coreRegistry = null;

    /**
     * @var \Amasty\Acart\Model\ConfigProvider
     */
    private $configProvider;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Framework\Registry $registry,
        \Amasty\Acart\Model\ConfigProvider $configProvider,
        array $data = []
    ) {
        $this->setId('rule_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Rule View'));

        $this->_coreRegistry = $registry;
        $this->configProvider = $configProvider;

        parent::__construct($context, $jsonEncoder, $authSession, $data);
    }

    protected function _getRule()
    {
        return $this->_coreRegistry->registry(RegistryConstants::CURRENT_AMASTY_ACART_RULE);
    }

    protected function _prepareLayout()
    {
        $this->addTab(
            'general',
            [
                'label' => __('General'),
                'content' => $this->getLayout()->createBlock(
                    'Amasty\Acart\Block\Adminhtml\Rule\Edit\Tab\General'
                )->toHtml(),
            ]
        );

        if ($this->_getRule()->getId()) {
            $recipientEmail = $this->configProvider->getRecipientEmailForTest();

            $recipientValidated = !empty($recipientEmail) && \Zend_Validate::is($recipientEmail, 'EmailAddress');

            if ($recipientValidated) {
                $this->addTabAfter(
                    'test',
                    [
                        'label' => __('Test'),
                        'url' => $this->getUrl('*/*/grid', ['_current' => true]),
                        'class' => 'ajax'
                    ],
                    'amasty_acart_rule_edit_tab_analytics'
                );
            } else {
                $url = $this->getUrl('adminhtml/system_config/edit/section/amasty_acart');
                $link = '<a target="_blank" href="' . $url . '">' . __('test email') . '</a>';

                $label = __(
                    'Before sending test messages, please fill in the %1 in the extension configuration section',
                    $link
                );

                $this->addTabAfter(
                    'test',
                    [
                        'label' => __('Test'),
                        'content' => '<div class="message message-warning warning"><div>' . $label . '</div></div>',
                    ],
                    'amasty_acart_rule_edit_tab_analytics'
                );
            }
        }

        $this->setActiveTab('amasty_acart_rule_edit_tab_general');

        return parent::_prepareLayout();
    }
}
