<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Freeshippinglabel\Controller\Adminhtml\Settings;

use Magento\Backend\App\Action\Context;
use Aheadworks\Freeshippinglabel\Api\Data\LabelInterfaceFactory;
use Aheadworks\Freeshippinglabel\Api\Data\LabelInterface;
use Aheadworks\Freeshippinglabel\Api\LabelRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * Class Save
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @package Aheadworks\Freeshippinglabel\Controller\Adminhtml\Settings
 */
class Save extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Aheadworks_Freeshippinglabel::settings';

    /**
     * @var LabelRepositoryInterface
     */
    private $labelRepository;

    /**
     * @var LabelInterfaceFactory
     */
    private $labelDataFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @param Context $context
     * @param LabelRepositoryInterface $labelRepository
     * @param LabelInterfaceFactory $labelDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Context $context,
        LabelRepositoryInterface $labelRepository,
        LabelInterfaceFactory $labelDataFactory,
        DataObjectHelper $dataObjectHelper,
        DataPersistorInterface $dataPersistor
    ) {
        parent::__construct($context);
        $this->labelRepository = $labelRepository;
        $this->labelDataFactory = $labelDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->prepareData($this->getRequest()->getPostValue());

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            try {
                $labelDataObject = $this->labelRepository->get(1);
                $this->dataObjectHelper->populateWithArray(
                    $labelDataObject,
                    $data,
                    LabelInterface::class
                );
                $this->labelRepository->save($labelDataObject);
                $this->dataPersistor->clear('aw_fslabel_label');
                $this->messageManager->addSuccessMessage(__('Settings were successfully saved'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving label settings'));
            }
            $this->dataPersistor->set('aw_fslabel_label', $data);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Prepare post data
     *
     * @param array $data
     * @return array
     */
    private function prepareData($data)
    {
        $contentFormatted = [];
        if (isset($data['content']) && is_array($data['content'])) {
            foreach ($data['content'] as $contentTypeItems) {
                $contentFormatted = array_merge($contentFormatted, $contentTypeItems);
            }
            foreach ($contentFormatted as $index => $contentItem) {
                if (isset($contentItem['removed']) && $contentItem['removed'] == 1) {
                    unset($contentFormatted[$index]);
                }
            }
            $data['content'] = $contentFormatted;
        }
        return $data;
    }
}
