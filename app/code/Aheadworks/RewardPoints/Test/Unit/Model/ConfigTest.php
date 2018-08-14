<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Model;

use Aheadworks\RewardPoints\Model\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class Aheadworks\RewardPoints\Test\Unit\Model\ConfigTest
 */
class ConfigTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Config
     */
    private $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ScopeConfigInterface
     */
    private $scopeConfigMock;

    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->scopeConfigMock = $this->getMockBuilder(ScopeConfigInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getValue', 'isSetFlag'])
            ->getMockForAbstractClass();

        $data = [
            'scopeConfig' => $this->scopeConfigMock,
        ];
        $this->object = $objectManager->getObject(Config::class, $data);
    }

    /**
     * Test getCalculationExpireRewardPoints method
     */
    public function testGetCalculationExpireRewardPointsMethod()
    {
        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with('aw_rewardpoints/calculation/expire_reward_points', 'website')
            ->willReturn(2);

        $this->assertEquals(2, $this->object->getCalculationExpireRewardPoints());
    }

    /**
     * Test getAwardedPointsForRegistration method
     */
    public function testGetAwardedPointsForRegistrationMethod()
    {
        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with('aw_rewardpoints/awarded/registration', 'website')
            ->willReturn(10);

        $this->assertEquals(10, $this->object->getAwardedPointsForRegistration());
    }

    /**
     * Test getAwardedPointsForRegistration method
     */
    public function testGetAwardedPointsForRegistrationMethodNullConfigValue()
    {
        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with('aw_rewardpoints/awarded/registration', 'website')
            ->willReturn(null);

        $this->assertEquals(0, $this->object->getAwardedPointsForRegistration());
    }

    /**
     * Test getAwardedPointsForReview method
     */
    public function testGetAwardedPointsForReviewMethod()
    {
        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with('aw_rewardpoints/awarded/product_review', 'website')
            ->willReturn(10);

        $this->assertEquals(10, $this->object->getAwardedPointsForReview());
    }

    /**
     * Test getAwardedPointsForRegistration method
     */
    public function testGetAwardedPointsForReviewMethodNullConfigValue()
    {
        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with('aw_rewardpoints/awarded/product_review', 'website')
            ->willReturn(null);

        $this->assertEquals(0, $this->object->getAwardedPointsForReview());
    }

    /**
     * Test getDailyLimitPointsForReview method
     */
    public function testGetDailyLimitPointsForReviewMethod()
    {
        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with('aw_rewardpoints/awarded/product_review_daily_limit', 'website')
            ->willReturn(10);

        $this->assertEquals(10, $this->object->getDailyLimitPointsForReview());
    }

    /**
     * Test getDailyLimitPointsForReview method
     */
    public function testGetDailyLimitPointsForReviewMethodNullConfigValue()
    {
        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with('aw_rewardpoints/awarded/product_review_daily_limit', 'website')
            ->willReturn(null);

        $this->assertEquals(0, $this->object->getDailyLimitPointsForReview());
    }

    /**
     * Test isProductReviewOwner method
     */
    public function testIsProductReviewOwnerMethod()
    {
        $this->scopeConfigMock->expects($this->once())
            ->method('isSetFlag')
            ->with('aw_rewardpoints/awarded/is_product_review_owner', 'website')
            ->willReturn(true);

        $this->assertTrue($this->object->isProductReviewOwner());
    }

    /**
     * Test isProductReviewOwner method
     */
    public function testIsProductReviewOwnerMethodNullConfigValue()
    {

        $this->scopeConfigMock->expects($this->once())
            ->method('isSetFlag')
            ->with('aw_rewardpoints/awarded/is_product_review_owner', 'website')
            ->willReturn(null);

        $this->assertFalse($this->object->isProductReviewOwner());
    }

    /**
     * Test getAwardedPointsForNewsletterSignup method
     */
    public function testGetAwardedPointsForNewsletterSignupMethod()
    {
        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with('aw_rewardpoints/awarded/newsletter_signup', 'website')
            ->willReturn(10);

        $this->assertEquals(10, $this->object->getAwardedPointsForNewsletterSignup());
    }

    /**
     * Test isPointsBalanceTopLinkAtFrontend method
     */
    public function testGetFrontendIsPointsBalanceTopLinkMethod()
    {
        $this->scopeConfigMock->expects($this->once())
            ->method('isSetFlag')
            ->with('aw_rewardpoints/frontend/is_points_balance_top_link', 'website')
            ->willReturn(true);

        $this->assertTrue($this->object->isPointsBalanceTopLinkAtFrontend());
    }
}
