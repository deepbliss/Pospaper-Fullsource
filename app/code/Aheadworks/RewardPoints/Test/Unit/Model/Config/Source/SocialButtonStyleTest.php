<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Model\Config\Source;

use Aheadworks\RewardPoints\Model\Config\Source\SocialButtonStyle;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class Aheadworks\RewardPoints\Test\Unit\Model\Config\Source\SocialButtonStyleTest
 */
class SocialButtonStyleTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var SocialButtonStyle
     */
    private $object;

    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->object = $objectManager->getObject(SocialButtonStyle::class);
    }

    /**
     * Test toOptionArray method
     */
    public function testToOptionArray()
    {
        $expectedResult = [
            'icons_only' => 'Icons Only',
            'icons_with_counter_v' => 'Icons with Counter (vertical)',
            'icons_with_counter_h' => 'Icons with Counter (horizontal)',
        ];

        $this->assertEquals($expectedResult, $this->object->toOptionArray());
    }
}
