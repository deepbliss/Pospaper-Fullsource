<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Controller\Adminhtml\Notification;

use Amasty\Base\Model\Feed;
use Magento\Backend\App\Action;

class Frequency extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Backend\App\ConfigInterface
     */
    private $config;

    /**
     * @var \Magento\Framework\App\Config\ReinitableConfigInterface
     */
    private $reinitableConfig;

    /**
     * @var \Magento\Framework\App\Config\Storage\WriterInterface
     */
    private $configWriter;

    /**
     * @var \Amasty\Base\Model\Source\Frequency
     */
    private $frequency;

    public function __construct(
        Action\Context $context,
        \Magento\Backend\App\ConfigInterface $config,
        \Magento\Framework\App\Config\ReinitableConfigInterface $reinitableConfig,
        \Magento\Framework\App\Config\Storage\WriterInterface $configWriter,
        \Amasty\Base\Model\Source\Frequency $frequency
    ) {
        parent::__construct($context);
        $this->config = $config;
        $this->reinitableConfig = $reinitableConfig;
        $this->configWriter = $configWriter;
        $this->frequency = $frequency;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $action = $this->getRequest()->getParam('action');

        switch ($action) {
            case 'less':
                $this->increaseFrequency();
                break;
            case 'more':
                $this->decreaseFrequency();
                break;
            default:
                $this->messageManager->addErrorMessage(
                    __(
                        'An error occurred while changing the frequency.'
                    )
                );
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(
            'Amasty_Base::config'
        );
    }

    private function decreaseFrequency()
    {
        $currentValue = $this->getCurrentValue();
        $allValues = $this->frequency->toOptionArray();
        $resultValue = null;
        foreach ($allValues as $option) {
            if ($option['value'] != $currentValue) {
                $resultValue = $option['value'];
            } else {
                if ($resultValue) {
                    $this->changeFrequency($resultValue);
                }

                break;
            }
        }

        $this->messageManager->addSuccessMessage(
            __(
                'You will get more messages of this type. Notification frequency has been updated.'
            )
        );
    }

    private function increaseFrequency()
    {
        $currentValue = $this->getCurrentValue();
        $allValues = $this->frequency->toOptionArray();
        $resultValue = null;
        foreach ($allValues as $option) {
            if ($option['value'] == $currentValue) {
                $resultValue = $option['value'];
            }

            if ($resultValue && $option['value'] != $resultValue) {
                $this->changeFrequency($option['value']);//save next option
                break;
            }
        }

        $this->messageManager->addSuccessMessage(
            __(
                'You will get less messages of this type. Notification frequency has been updated.'
            )
        );
    }

    private function changeFrequency($value)
    {
        $this->configWriter->save(Feed::XML_FREQUENCY_PATH, $value);
        $this->reinitableConfig->reinit();

        return $this;
    }

    private function getCurrentValue()
    {
        return $this->config->getValue(Feed::XML_FREQUENCY_PATH);
    }
}
