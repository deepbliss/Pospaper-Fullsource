<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Freeshippinglabel\Model\Label;

use Aheadworks\Freeshippinglabel\Api\Data\LabelInterface;
use Aheadworks\Freeshippinglabel\Model\Source\ContentType;
use Magento\Framework\Validator\AbstractValidator;

/**
 * Class Validator
 * @package Aheadworks\Freeshippinglabel\Model\Label
 */
class Validator extends AbstractValidator
{
    /**
     * Returns true if label entity meets the validation requirements
     *
     * @param LabelInterface $label
     * @return bool
     */
    public function isValid($label)
    {
        $this->_clearMessages();
        if (!$this->isContentDataValid($label)) {
            return false;
        }

        return empty($this->getMessages());
    }

    /**
     * Returns true if label content data is correct
     *
     * @param LabelInterface $label
     * @return bool
     */
    private function isContentDataValid(LabelInterface $label)
    {
        $isAllStoreViewsDataPresents = true;
        $uniqueContentData = [
            ContentType::EMPTY_CART => [],
            ContentType::NOT_EMPTY_CART => [],
            ContentType::GOAL_REACHED => [],
        ];
        if ($label->getContent()) {
            foreach ($label->getContent() as $contentItem) {
                $contentType = $contentItem->getContentType();
                if (!in_array($contentItem->getStoreId(), $uniqueContentData[$contentType])) {
                    $uniqueContentData[$contentType][] =  $contentItem->getStoreId();
                } else {
                    $this->_addMessages(['Duplicated store view in label content found.']);
                    return false;
                }
                if (!\Zend_Validate::is($contentItem->getMessage(), 'NotEmpty')) {
                    $this->_addMessages(['Content message can not be empty.']);
                    return false;
                }
            }
        }
        foreach ($uniqueContentData as $contentDataForType) {
            if (!in_array(0, $contentDataForType)) {
                    $isAllStoreViewsDataPresents = false;
            }
        }
        if (!$isAllStoreViewsDataPresents) {
            $this->_addMessages(
                ['Default values for label content (for All Store Views option) aren\'t set.']
            );
            return false;
        }
        return true;
    }
}
