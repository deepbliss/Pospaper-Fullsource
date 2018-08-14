<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Freeshippinglabel\Controller\Label;

use Aheadworks\Freeshippinglabel\Api\LabelRepositoryInterface;
use Magento\Framework\App\Action\Context;

/**
 * Class Render
 * @package Aheadworks\Freeshippinglabel\Controller\Label
 */
class Render extends \Magento\Framework\App\Action\Action
{
    /**
     * @var LabelRepositoryInterface
     */
    private $labelRepository;

    /**
     * @param Context $context
     * @param LabelRepositoryInterface $labelRepository
     */
    public function __construct(
        Context $context,
        LabelRepositoryInterface $labelRepository
    ) {
        $this->labelRepository = $labelRepository;
        parent::__construct($context);
    }

    /**
     * Returns label content
     *
     * @return \Magento\Framework\Controller\Result\Redirect|void
     */
    public function execute()
    {
        if (!$this->getRequest()->isAjax()) {
            /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setRefererOrBaseUrl();
        }
        /** @var \Aheadworks\Freeshippinglabel\Model\Label $label */
        $label = $this->labelRepository->get(1);
        $data = ['labelContent' => $label->getMessage()];

        $this->getResponse()->appendBody(json_encode($data));
    }
}
