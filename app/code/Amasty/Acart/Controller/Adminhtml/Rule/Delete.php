<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Controller\Adminhtml\Rule;

class Delete extends \Amasty\Acart\Controller\Adminhtml\Rule
{
    /**
     * @var \Amasty\Acart\Model\RuleFactory
     */
    private $ruleFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Amasty\Acart\Model\RuleFactory $ruleFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->ruleFactory = $ruleFactory;
        $this->logger = $logger;
    }

    /**
     * Delete promo quote action
     *
     * @return void
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            try {
                /** @var \Amasty\Acart\Model\Rule $model */
                $model = $this->ruleFactory->create();
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccessMessage(__('You deleted the campaign.'));
                $this->_redirect('amasty_acart/*/');

                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(
                    __('We can\'t delete the rule right now. Please review the log and try again.')
                );
                $this->logger->critical($e);
                $this->_redirect('amasty_acart/*/edit', ['id' => $this->getRequest()->getParam('id')]);

                return;
            }
        }
        $this->messageManager->addError(__('We can\'t find a rule to delete.'));
        $this->_redirect('amasty_acart/*/');
    }
}
