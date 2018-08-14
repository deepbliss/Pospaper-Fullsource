<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Block\Adminhtml\Transaction\NewAction;

use Aheadworks\RewardPoints\Block\Adminhtml\Transaction\NewAction\ResetButton;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\UrlInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class Aheadworks\RewardPoints\Test\Unit\Block\Adminhtml\Transaction\NewAction\ResetButtonTest
 */
class ResetButtonTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ResetButton
     */
    private $object;

    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->object = $objectManager->getObject(ResetButton::class, []);
    }

    /**
     * Test getButtonData method
     */
    public function testGetButtonDataMethod()
    {
        $expectsData = [
            'label' => 'Reset',
            'class' => 'reset',
            'on_click' => 'location.reload();',
            'sort_order' => 30
        ];
        $this->assertEquals($expectsData, $this->object->getButtonData());
    }
}
