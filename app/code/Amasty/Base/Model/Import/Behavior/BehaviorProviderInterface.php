<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Model\Import\Behavior;

interface BehaviorProviderInterface
{
    /**
     * @param string $behaviorCode
     *
     * @throws \Amasty\Base\Exceptions\NonExistentImportBehavior
     * @return \Amasty\Base\Model\Import\Behavior\BehaviorInterface
     */
    public function getBehavior($behaviorCode);
}
