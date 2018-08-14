<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Block\Product\View;

use Aheadworks\RewardPoints\Api\CustomerRewardPointsManagementInterface;
use Aheadworks\RewardPoints\Model\Calculator\RateCalculator;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\RewardPoints\Block\Product\View\Share;
use Magento\Backend\Block\Template\Context;
use Magento\Customer\Model\Session;
use Magento\Framework\Registry;

/**
 * Class Aheadworks\RewardPoints\Test\Unit\Block\Product\View\ShareTest
 */
class ShareTest extends \PHPUnit\Framework\TestCase
{
    /** @var  Context|\PHPUnit_Framework_MockObject_MockObject */
    private $context;

    /**
     * @var  CustomerRewardPointsManagementInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $customerRewardPointsService;

    /**
     * @var RateCalculator|\PHPUnit_Framework_MockObject_MockObject
     */
    private $rateCalculator;

    /**
     * @var Session|\PHPUnit_Framework_MockObject_MockObject
     */
    private $customerSession;

    /**
     * @var Registry|\PHPUnit_Framework_MockObject_MockObject
     */
    private $coreRegistry;

    /**
     * @var Share
     */
    private $object;

    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->context = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerRewardPointsService = $this->getMockBuilder(CustomerRewardPointsManagementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->rateCalculator = $this->getMockBuilder(RateCalculator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerSession = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->coreRegistry = $this->getMockBuilder(Registry::class)
            ->disableOriginalConstructor()
            ->getMock();

        $data = [
            'context' => $this->context,
            'customerRewardPointsService' => $this->customerRewardPointsService,
            'rateCalculator' => $this->rateCalculator,
            'customerSession' => $this->customerSession,
            'coreRegistry' => $this->coreRegistry
        ];

        $this->object = $objectManager->getObject(Share::class, $data);
    }

    /**
     * Test template property
     */
    public function testTemplateProperty()
    {
        $class = new \ReflectionClass(Share::class);
        $property = $class->getProperty('_template');
        $property->setAccessible(true);

        $this->assertEquals('Aheadworks_RewardPoints::product/view/share.phtml', $property->getValue($this->object));
    }
}
