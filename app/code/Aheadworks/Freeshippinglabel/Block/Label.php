<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Freeshippinglabel\Block;

use Aheadworks\Freeshippinglabel\Model\Source\PageType;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;
use Aheadworks\Freeshippinglabel\Api\Data\LabelInterface as LabelModel;
use Aheadworks\Freeshippinglabel\Api\LabelRepositoryInterface;

/**
 * Class Label
 * @package Aheadworks\Freeshippinglabel\Block
 */
class Label extends \Magento\Framework\View\Element\Template
{
    /**
     * Path to template file in theme
     * @var string
     */
    protected $_template = 'Aheadworks_Freeshippinglabel::label.phtml';

    /**
     * @var LabelModel
     */
    private $labelModel;

    /**
     * @var LabelRepositoryInterface
     */
    private $labelRepository;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var Session
     */
    private $customerSession;

    /**
     * @param Registry $registry
     * @param LabelRepositoryInterface $labelRepository
     * @param Session $customerSession
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Registry $registry,
        LabelRepositoryInterface $labelRepository,
        Session $customerSession,
        Context $context,
        array $data = []
    ) {
        $this->labelRepository = $labelRepository;
        $this->registry = $registry;
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    /**
     * Render block
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->isLabelEnabled()) {
            return '';
        }
        return parent::_toHtml();
    }

    /**
     * Is label enabled
     *
     * @return string
     */
    public function isLabelEnabled()
    {
        $label = $this->getLabelModel();

        return $label->getIsEnabled()
            && $this->isCustomerGroupMatch($label->getCustomerGroupIds())
            && $this->isPageTypeMatch($label->getPageType())
            && $this->isPositionMatch($label->getPosition())
            && $label->getMessage();
    }

    /**
     * Get label instance
     *
     * @return LabelModel|mixed
     */
    public function getLabelModel()
    {
        if (!$this->labelModel) {
            if (!$label = $this->registry->registry('aw_fslabel_label')) {
                $label = $this->labelRepository->get(1);
                $this->registry->register('aw_fslabel_label', $label);
            }
            $this->labelModel = $label;
        }
        return $this->labelModel;
    }

    /**
     * Is customer group match
     *
     * @param array $allowedGroupIds
     * @return bool
     */
    private function isCustomerGroupMatch($allowedGroupIds)
    {
        return in_array($this->customerSession->getCustomerGroupId(), $allowedGroupIds);
    }

    /**
     * Is page type match
     *
     * @param string $pageType
     * @return bool
     */
    private function isPageTypeMatch($pageType)
    {
        $currentPageType = PageType::getTypeByActionName($this->getRequest()->getFullActionName());

        return $pageType == PageType::ALL_PAGES || $pageType == $currentPageType;
    }

    /**
     * Is position match
     *
     * @param string $position
     * @return bool
     */
    private function isPositionMatch($position)
    {
        return $position == $this->getNameInLayout();
    }

    /**
     * Get padding for label block
     *
     * @return int
     */
    public function getPadding()
    {
        $padding = (int)($this->getLabelModel()->getFontSize() / 3);
        $padding > 15 ?: $padding = 15 ;
        return $padding;
    }

    /**
     * Retrieve script options encoded to json
     *
     * @return string
     */
    public function getScriptOptions()
    {
        $stickyClass = null;
        if (strpos($this->getLabelModel()->getPosition(), 'top_fixed') !== false) {
            $stickyClass = 'top_fixed';
        } elseif (strpos($this->getLabelModel()->getPosition(), 'bottom_fixed')  !== false) {
            $stickyClass = 'bottom_fixed';
        }
        $params = [
            'url' => $this->getUrl(
                'aw_fslabel/label/render/',
                [
                    '_current' => true,
                    '_secure' => $this->templateContext->getRequest()->isSecure()
                ]
            ),
            'stickyClass' => $stickyClass,
            'font' => $this->getLabelModel()->getFontName(),
            'delay' => $this->getLabelModel()->getDelay()
        ];
        return json_encode($params);
    }
}
