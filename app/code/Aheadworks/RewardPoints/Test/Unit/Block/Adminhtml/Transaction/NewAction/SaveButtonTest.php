<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Block\Adminhtml\Transaction\NewAction;

use Aheadworks\RewardPoints\Block\Adminhtml\Transaction\NewAction\SaveButton;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\UrlInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class Aheadworks\RewardPoints\Test\Unit\Block\Adminhtml\Transaction\NewAction\SaveButtonTest
 */
class SaveButtonTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var SaveButton
     */
    private $object;

    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->object = $objectManager->getObject(SaveButton::class, []);
    }

    /**
     * Test getButtonData method
     */
    public function testGetButtonDataMethod()
    {
        $expectsData = [
            'label' => __('Save Transaction'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 90,
        ];
        $this->assertEquals($expectsData, $this->object->getButtonData());
    }
}
