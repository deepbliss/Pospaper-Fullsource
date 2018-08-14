<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Block\Html;

use Aheadworks\RewardPoints\Block\Html\Pager;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\App\Request\Http as RequestHttp;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Backend\Block\Template\Context;

/**
 * Class Aheadworks\RewardPoints\Test\Unit\Block\Html\PagerTest
 */
class PagerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var  \PHPUnit_Framework_MockObject_MockObject|SearchResultsInterface
     */
    private $searchResultsMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Context
     */
    private $context;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|RequestHttp
     */
    private $requestMock;

    /**
     * @var Pager
     */
    private $object;

    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->requestMock = $this->getMockBuilder(RequestHttp::class)
            ->disableOriginalConstructor()
            ->setMethods(['getParam'])
            ->getMock();

        $this->searchResultsMock = $this->getMockBuilder(SearchResultsInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getItems', 'getTotalCount'])
            ->getMockForAbstractClass();

        $this->context = $objectManager->getObject(
            Context::class,
            ['request' => $this->requestMock]
        );

        $data = ['context' => $this->context];

        $this->object = $objectManager->getObject(Pager::class, $data);
    }

    /**
     * Test template property
     */
    public function testTemplateProperty()
    {
        $class = new \ReflectionClass(Pager::class);
        $property = $class->getProperty('_template');
        $property->setAccessible(true);

        $this->assertEquals('Aheadworks_RewardPoints::html/pager.phtml', $property->getValue($this->object));
    }

    /**
     * Test pageVarName property
     */
    public function testPageVarNameProperty()
    {
        $class = new \ReflectionClass(Pager::class);
        $property = $class->getProperty('pageVarName');
        $property->setAccessible(true);

        $this->assertEquals('p', $property->getValue($this->object));
    }

    /**
     * Test limitVarName property
     */
    public function testLimitVarNameProperty()
    {
        $class = new \ReflectionClass(Pager::class);
        $property = $class->getProperty('limitVarName');
        $property->setAccessible(true);

        $this->assertEquals('limit', $property->getValue($this->object));
    }

    /**
     * Test getLastPageNum method
     */
    public function testGetLastPageNum()
    {
        $this->requestMock->expects($this->any())
            ->method('getParam')
            ->withConsecutive([$this->equalTo('limit')])
            ->willReturn(null);

        $this->searchResultsMock->expects($this->once())
            ->method('getTotalCount')
            ->willReturn(20);

        $this->object->setSearchResults($this->searchResultsMock);
        $this->assertEquals(2, $this->object->getLastPageNum());
    }

    /**
     * Test getCurrentPage method
     */
    public function testGetCurrentPage()
    {
        $this->requestMock->expects($this->any())
            ->method('getParam')
            ->withConsecutive([$this->equalTo('p'), $this->equalTo(1)])
            ->willReturn(3);

        $this->searchResultsMock->expects($this->any())
            ->method('getTotalCount')
            ->willReturn(60);

        $this->object->setSearchResults($this->searchResultsMock);
        $this->assertEquals(3, $this->object->getCurrentPage());
        $this->assertEquals(2, $this->object->getCurrentPage(-1));
        $this->assertEquals(4, $this->object->getCurrentPage(+1));
    }

    /**
     * Test getFirstNum method
     */
    public function testGetFirstNum()
    {
        $this->requestMock->expects($this->any())
            ->method('getParam')
            ->withConsecutive(['limit'], ['p', 1])
            ->willReturnOnConsecutiveCalls(null, 3);

        $this->assertEquals(21, $this->object->getFirstNum());
    }

    /**
     * Test getLastNum method
     */
    public function testGetLastNum()
    {
        $this->requestMock->expects($this->any())
            ->method('getParam')
            ->withConsecutive(['limit'], ['p', 1])
            ->willReturnOnConsecutiveCalls(null, 3);

        $this->searchResultsMock->expects($this->any())
            ->method('getItems')
            ->willReturn([1, 2, 3, 4, 5]);

        $this->object->setSearchResults($this->searchResultsMock);

        $this->assertEquals(25, $this->object->getLastNum());
    }

    /**
     * Test getPages method
     */
    public function testGetPages()
    {
        $this->requestMock->expects($this->any())
            ->method('getParam')
            ->withConsecutive(['limit'], ['limit'], ['p', 1])
            ->willReturnOnConsecutiveCalls(null, null, 3);

        $this->searchResultsMock->expects($this->any())
            ->method('getTotalCount')
            ->willReturn(25);

        $this->searchResultsMock->expects($this->any())
            ->method('getItems')
            ->willReturn([1, 2, 3, 4, 5]);

        $this->object->setSearchResults($this->searchResultsMock);

        $this->assertEquals([1, 2, 3], $this->object->getPages());
    }
}
