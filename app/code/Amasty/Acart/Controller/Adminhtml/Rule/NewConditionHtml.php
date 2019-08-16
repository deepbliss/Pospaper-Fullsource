<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Controller\Adminhtml\Rule;

use Magento\Rule\Model\Condition\AbstractCondition;

class NewConditionHtml extends \Amasty\Acart\Controller\Adminhtml\Rule
{
    /**
     * @var \Amasty\Acart\Model\SalesRuleFactory
     */
    private $salesRuleFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Amasty\Acart\Model\SalesRuleFactory $salesRuleFactory
    ) {
        $this->salesRuleFactory = $salesRuleFactory;
        parent::__construct($context);
    }

    /**
     * @return void
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $typeArr = explode('|', str_replace('-', '/', $this->getRequest()->getParam('type')));
        $type = $typeArr[0];

        $model = $this->_objectManager->create(
            $type
        )->setId(
            $id
        )->setType(
            $type
        )->setRule(
            $this->salesRuleFactory->create()
        )->setPrefix(
            'conditions'
        );
        if (!empty($typeArr[1])) {
            $model->setAttribute($typeArr[1]);
        }

        if ($model instanceof AbstractCondition) {
            $model->setJsFormObject($this->getRequest()->getParam('form'));
            $html = $model->asHtmlRecursive();
        } else {
            $html = '';
        }
        $this->getResponse()->setBody($html);
    }
}
