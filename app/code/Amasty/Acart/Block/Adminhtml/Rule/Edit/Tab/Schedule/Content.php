<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Block\Adminhtml\Rule\Edit\Tab\Schedule;

use Magento\Backend\Block\Widget;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;
use Amasty\Acart\Controller\RegistryConstants;

class Content extends Widget implements RendererInterface
{
    const CRON_FAQ = 'https://amasty.com/blog/configure-magento-cron-job?utm_source=extension&utm_medium=tooltip'
        .'&utm_campaign=abandoned-cart-m2-cron-recommended-settings';

    const MAX_SALES_RULES = 100;

    const QUOTE_LIFETIMES_CONFIG_PATH = 'checkout/cart/delete_quote_after';

    protected $_template = 'rule/schedule.phtml';

    protected $_salesRuleCollection;

    protected $_emailTemplateCollection;

    protected $_coreRegistry;

    protected $_moduleManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $storesConfig;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Email\Model\ResourceModel\Template\CollectionFactory $templatesFactory,
        \Magento\Framework\Registry $registry,
        \Magento\SalesRule\Model\Rule $rule,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;

        $this->_emailTemplateCollection = $templatesFactory->create()
            ->addFilter('orig_template_code', 'amasty_acart_template');

        $this->_salesRuleCollection = $rule->getCollection()
            ->addFilter('use_auto_generation', 1)
            ->addFilter('is_active', 1);

        $this->_moduleManager = $moduleManager;
        $this->storesConfig = $context->getScopeConfig();

        parent::__construct($context, $data);
    }

    protected function _prepareLayout()
    {
        $button = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Button'
        )->setData(
            [
                'label' => __('Add Record'),
                'onclick' => 'return amastyAcartSchedule.addItem();',
                'class' => 'add amasty-add-row'
            ]
        );

        $button->setName('add_record_button');

        $this->setChild('add_record_button', $button);


        return parent::_prepareLayout();
    }

    public function getAddRecordButtonHtml()
    {
        return $this->getChildHtml('add_record_button');
    }

    /**
     * @return bool
     */
    public function quoteLifetimeNoticeIsAvailable()
    {
        $quoteLifetimes = $this->storesConfig->getValue(
            self::QUOTE_LIFETIMES_CONFIG_PATH,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        $scheduleCollection = $this->getScheduleCollection();

        if ($scheduleCollection->getSize() > 0 && $quoteLifetimes) {
            /** @var \Amasty\Acart\Model\Schedule $schedule */
            foreach ($scheduleCollection->load() as $schedule) {
                if ($schedule->getDays() >= $quoteLifetimes) {
                    return true;
                }
            }
        }

        return false;
    }

    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $this->setElement($element);

        return $this->toHtml();
    }

    public function getNumberOptions($number)
    {
        $ret = ['<option value="">-</option>'];
        for ($index = 1; $index <= $number; $index++) {
            $ret[] = '<option value="' . $index . '" >' . $index . '</option>';
        }

        return implode('', $ret);
    }

    public function getEmailTemplateCollection()
    {
        return $this->_emailTemplateCollection;
    }

    public function getSalesRuleCollection()
    {
        return $this->_salesRuleCollection;
    }

    public function isShowSalesRuleSelect()
    {
        return $this->getSalesRuleCollection()->getSize() < self::MAX_SALES_RULES;
    }

    protected function _getRule()
    {
        return $this->_coreRegistry->registry(RegistryConstants::CURRENT_AMASTY_ACART_RULE);
    }

    public function getScheduleCollection()
    {
        return $this->_getRule()->getScheduleCollection();
    }

    public function moduleEnabled($module)
    {
        return $this->_moduleManager->isEnabled($module) && $this->_moduleManager->isOutputEnabled($module);
    }

    public function getCronUrl()
    {
        return self::CRON_FAQ;
    }
}
