<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Freeshippinglabel\Model;

use Aheadworks\Freeshippinglabel\Api\Data\LabelInterface;
use Aheadworks\Freeshippinglabel\Api\LabelRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\EntityManager\EntityManager;
use Aheadworks\Freeshippinglabel\Model\LabelFactory;
use Aheadworks\Freeshippinglabel\Model\Label as LabelModel;
use Aheadworks\Freeshippinglabel\Api\Data\LabelInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

/**
 * Class LabelRepository
 * @package Aheadworks\Freeshippinglabel\Model
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class LabelRepository implements LabelRepositoryInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var LabelFactory
     */
    private $labelFactory;

    /**
     * @var LabelInterfaceFactory
     */
    private $labelDataFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @param EntityManager $entityManager
     * @param LabelFactory $labelFactory
     * @param LabelInterfaceFactory $labelDataFactory
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        EntityManager $entityManager,
        LabelFactory $labelFactory,
        LabelInterfaceFactory $labelDataFactory,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->entityManager = $entityManager;
        $this->labelFactory = $labelFactory;
        $this->labelDataFactory = $labelDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function save(LabelInterface $label)
    {
        /** @var LabelModel $labelModel */
        $labelModel = $this->labelFactory->create();
        if ($labelId = $label->getId()) {
            $this->entityManager->load($labelModel, $labelId);
        }
        $labelModel->addData($label->getData(), LabelInterface::class);

        $this->entityManager->save($labelModel);
        return $label;
    }

    /**
     * {@inheritdoc}
     */
    public function get($labelId)
    {
        $labelModel = $this->labelDataFactory->create();
        $this->entityManager->load($labelModel, $labelId);
        if (!$labelModel->getId()) {
            throw NoSuchEntityException::singleField('labelId', $labelId);
        }
        return $labelModel;
    }
}
