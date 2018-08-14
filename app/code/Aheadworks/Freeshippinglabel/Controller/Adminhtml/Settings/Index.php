<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Freeshippinglabel\Controller\Adminhtml\Settings;

use Aheadworks\Freeshippinglabel\Api\Data\LabelInterface;
use Aheadworks\Freeshippinglabel\Api\LabelRepositoryInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Registry;
use Magento\Framework\Reflection\DataObjectProcessor;

/**
 * Class Index
 * @package Aheadworks\Freeshippinglabel\Controller\Adminhtml\Settings
 */
class Index extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Aheadworks_Freeshippinglabel::settings';

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @var LabelRepositoryInterface
     */
    private $labelRepository;

    /**
     * @var Registry
     */
    private $coreRegistry;

    /**
     * @var DataObjectProcessor
     */
    private $dataObjectProcessor;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @param Context $context
     * @param LabelRepositoryInterface $labelRepository
     * @param PageFactory $resultPageFactory
     * @param Registry $coreRegistry
     * @param DataObjectProcessor $dataObjectProcessor
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Context $context,
        LabelRepositoryInterface $labelRepository,
        PageFactory $resultPageFactory,
        Registry $coreRegistry,
        DataObjectProcessor $dataObjectProcessor,
        DataPersistorInterface $dataPersistor
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->labelRepository = $labelRepository;
        $this->coreRegistry = $coreRegistry;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $label = $this->labelRepository->get(1);
        $this->registerLabelContentData($label);
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->getRequest()->setParams(['id' => 1]);
        $resultPage
            ->setActiveMenu('Aheadworks_Freeshippinglabel::settings')
            ->getConfig()->getTitle()->prepend(__('Settings'));

        return $resultPage;
    }

    /**
     * Register label content data
     *
     * @param LabelInterface $label
     * @return void
     */
    private function registerLabelContentData(LabelInterface $label)
    {
        $labelData = $this->dataPersistor->get('aw_fslabel_label')
            ? $this->dataPersistor->get('aw_fslabel_label')
            : $this->dataObjectProcessor->buildOutputDataArray(
                $label,
                LabelInterface::class
            );
        $labelContentData = isset($labelData['content'])
            ? $labelData['content']
            : [];
        if ($this->dataPersistor->get('aw_fslabel_label')) {
            unset($labelData['content']);
            $this->dataPersistor->set('aw_fslabel_label', $labelData);
        }
        $this->coreRegistry->register('aw_fslabel_label_content', $labelContentData);
    }
}
