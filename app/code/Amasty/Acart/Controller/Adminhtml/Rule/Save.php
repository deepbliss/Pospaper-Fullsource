<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Controller\Adminhtml\Rule;

class Save extends \Amasty\Acart\Controller\Adminhtml\Rule
{
    /**
     * @var \Amasty\Acart\Model\RuleFactory
     */
    private $ruleFactory;

    /**
     * @var \Amasty\Acart\Model\SalesRuleFactory
     */
    private $salesRuleFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Amasty\Base\Model\Serializer
     */
    private $serializer;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Amasty\Acart\Model\RuleFactory $ruleFactory,
        \Amasty\Acart\Model\SalesRuleFactory $salesRuleFactory,
        \Amasty\Base\Model\Serializer $serializer,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->ruleFactory = $ruleFactory;
        $this->salesRuleFactory = $salesRuleFactory;
        $this->serializer = $serializer;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * @param string $key
     * @param array $data
     */
    private function normalizeArray($key, &$data)
    {
        if (isset($data[$key]) && is_array($data[$key])) {
            $data[$key] = implode(',', $data[$key]);
        } else {
            $data[$key] = '';
        }
    }

    public function execute()
    {
        if ($this->getRequest()->getPostValue()) {
            $data = $this->getRequest()->getPostValue();

            try {
                foreach ($data['schedule'] as &$item) {
                    $this->prepareSchedule($item);
                }

                /** @var \Amasty\Acart\Model\Rule $model */
                $model = $this->ruleFactory->create();

                $id = $this->getRequest()->getParam('rule_id');

                if ($id) {
                    $model->load($id);
                    if ($id != $model->getId()) {
                        throw new \Magento\Framework\Exception\LocalizedException(__('The wrong campaign is specified.'));
                    }
                }

                if (isset($data['rule']) && isset($data['rule']['conditions'])) {
                    $data['conditions'] = $data['rule']['conditions'];

                    unset($data['rule']);

                    /** @var \Amasty\Acart\Model\SalesRule $salesRule */
                    $salesRule = $this->salesRuleFactory->create();
                    $salesRule->loadPost($data);

                    $data['conditions_serialized'] = $this->serializer
                        ->serialize($salesRule->getConditions()->asArray());
                    unset($data['conditions']);
                }

                $this->normalizeArray('store_ids', $data);
                $this->normalizeArray('customer_group_ids', $data);
                $this->normalizeArray('cancel_condition', $data);

                $model->setData($data);

                $this->_getSession()->setPageData($model->getData());

                $model->save();
                $model->saveSchedule();

                $this->messageManager->addSuccessMessage(__('You saved the campaign.'));
                $this->_getSession()->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('amasty_acart/*/edit', ['id' => $model->getId()]);

                    return;
                }
                $this->_redirect('amasty_acart/*/');

                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $id = (int)$this->getRequest()->getParam('rule_id');
                if (!empty($id)) {
                    $this->_redirect('amasty_acart/*/edit', ['id' => $id]);
                } else {
                    $this->_redirect('amasty_acart/*/new');
                }

                return;
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(
                    __('Something went wrong while saving the campaign data. Please review the error log.')
                );
                $this->logger->critical($e);
                $this->_getSession()->setPageData($data);
                $this->_redirect('amasty_acart/*/edit', ['id' => $this->getRequest()->getParam('rule_id')]);

                return;
            }
        }
        $this->_redirect('amasty_acart/*/');
    }

    /**
     * @param array $data
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function prepareSchedule(&$data)
    {
        if (empty($data['sales_rule_id'])) {
            if (isset($data['use_shopping_cart_rule'])) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Shopping Cart Rule is required')
                );
            }
        }
        if (!isset($data['use_shopping_cart_rule'])) {
            $data['use_shopping_cart_rule'] = false;
        }

        if (!isset($data['send_same_coupon'])) {
            $data['send_same_coupon'] = 0;
        }

        if (!(int)$data['schedule_id']) {
            unset($data['schedule_id']);
        }
    }
}
