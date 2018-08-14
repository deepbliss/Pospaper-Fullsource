<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Freeshippinglabel\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Label CRUD interface
 * @api
 */
interface LabelRepositoryInterface
{
    /**
     * Save label
     *
     * @param \Aheadworks\Freeshippinglabel\Api\Data\LabelInterface $label
     * @return \Aheadworks\Freeshippinglabel\Api\Data\LabelInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Aheadworks\Freeshippinglabel\Api\Data\LabelInterface $label);

    /**
     * Retrieve label
     *
     * @param int $labelId
     * @return \Aheadworks\Freeshippinglabel\Api\Data\LabelInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($labelId);
}
