<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Controller\Adminhtml\Rule;

use Amasty\Acart\Controller\RegistryConstants;

class Edit extends \Amasty\Acart\Controller\Adminhtml\Rule
{
    /**
     * @var \Amasty\Acart\Model\RuleFactory
     */
    private $ruleFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    private $coreRegistry;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Amasty\Acart\Model\RuleFactory $ruleFactory,
        \Magento\Framework\Registry $coreRegistry
    ) {
        parent::__construct($context);
        $this->ruleFactory = $ruleFactory;
        $this->coreRegistry = $coreRegistry;
    }

    /**
     * Customer edit action
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $ruleId = (int)$this->getRequest()->getParam('id');

        $ruleData = [];

        /** @var \Amasty\Acart\Model\Rule $rule */
        $rule = $this->ruleFactory->create();
        $isExistingRule = (bool)$ruleId;

        if ($isExistingRule) {
            $rule = $rule->load($ruleId);

            if (!$rule->getId()) {
                $this->messageManager->addErrorMessage(__('Something went wrong while editing the campaign.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath('amasty_acart/*/index');

                return $resultRedirect;
            }
        }

        $this->initCurrentRule($rule);

        $rule->getSalesRule()
            ->getConditions()->setJsFormObject('rule_conditions_fieldset');

        $ruleData['rule_id'] = $ruleId;

        $this->_getSession()->setRuleData($ruleData);

        $resultPage = $this->_initAction();
        if ($isExistingRule) {
            $resultPage->getConfig()->getTitle()->prepend($rule->getName());
        } else {
            $resultPage->getConfig()->getTitle()->prepend(__('New Campaign'));
        }

        return $resultPage;
    }

    /**
     * @param \Amasty\Acart\Model\Rule $rule
     *
     * @return \Amasty\Acart\Model\Rule
     */
    private function initCurrentRule($rule)
    {
        $this->coreRegistry->register(RegistryConstants::CURRENT_AMASTY_ACART_RULE, $rule);

        return $rule;
    }
}
