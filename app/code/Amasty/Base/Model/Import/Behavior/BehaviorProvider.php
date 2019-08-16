<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Model\Import\Behavior;

/**
 * Class BehaviorProvider
 * @package Amasty\Base\Model\Import\Behavior
 */
class BehaviorProvider implements BehaviorProviderInterface
{
    /**
     * @var \Amasty\Base\Model\Import\Behavior\BehaviorInterface[]
     */
    private $behaviors;

    public function __construct($behaviors)
    {
        $this->behaviors = [];
        foreach ($behaviors as $behaviorCode => $behavior) {
            if (!($behavior instanceof BehaviorInterface)) {
                throw new \Amasty\Base\Exceptions\WrongBehaviorInterface();
            }

            $this->behaviors[$behaviorCode] = $behavior;
        }
    }

    /**
     * @inheritdoc
     */
    public function getBehavior($behaviorCode)
    {
        if (!isset($this->behaviors[$behaviorCode])) {
            throw new \Amasty\Base\Exceptions\NonExistentImportBehavior();
        }
        return $this->behaviors[$behaviorCode];
    }
}
