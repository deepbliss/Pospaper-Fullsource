<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Freeshippinglabel\Test\Unit\Model\Source;

use Magento\Customer\Api\Data\GroupSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Convert\DataObject;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Customer\Api\GroupRepositoryInterface;
use Aheadworks\Freeshippinglabel\Model\Source\CustomerGroups;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

/**
 * Test for \Aheadworks\Freeshippinglabel\Model\Source\CustomerGroups
 */
class CustomerGroupsTest extends TestCase
{
    /**
     * @var CustomerGroups
     */
    private $model;

    /**
     * @var GroupRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $groupRepositoryMock;

    /**
     * @var SearchCriteriaBuilder|\PHPUnit_Framework_MockObject_MockObject
     */
    private $searchCriteriaBuilderMock;

    /**
     * @var DataObject|\PHPUnit_Framework_MockObject_MockObject
     */
    private $objectConverterMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->groupRepositoryMock = $this->getMockBuilder(GroupRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $this->searchCriteriaBuilderMock = $this->createPartialMock(
            SearchCriteriaBuilder::class,
            ['create']
        );
        $this->objectConverterMock = $this->createPartialMock(
            DataObject::class,
            ['toOptionArray']
        );

        $customerGroups = ['group_1', 'group_2'];
        $options = [
            ['label' => 'group_1', 'value' => '1'],
            ['label' => 'group_2', 'value' => '2']
        ];

        $searchCriteriaMock = $this->getMockBuilder(SearchCriteriaInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $searchResultMock = $this->getMockBuilder(GroupSearchResultsInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $this->searchCriteriaBuilderMock->expects($this->once())
            ->method('create')
            ->willReturn($searchCriteriaMock);

        $this->groupRepositoryMock->expects($this->once())
            ->method('getList')
            ->with($searchCriteriaMock)
            ->willReturn($searchResultMock);

        $searchResultMock->expects($this->once())
            ->method('getItems')
            ->willReturn($customerGroups);
        $this->objectConverterMock->expects($this->once())
            ->method('toOptionArray')
            ->with($customerGroups, 'id', 'code')
            ->willReturn($options);

        $this->model = $objectManager->getObject(
            CustomerGroups::class,
            [
                'groupRepository' => $this->groupRepositoryMock,
                'searchCriteriaBuilder' => $this->searchCriteriaBuilderMock,
                'objectConverter' => $this->objectConverterMock
            ]
        );
    }

    /**
     * Testing of toOptionArray method
     */
    public function testToOptionArray()
    {
        $this->assertTrue(is_array($this->model->toOptionArray()));
    }

    /**
     * Testing of getOptionArray method
     */
    public function testGetOptionArray()
    {
        $this->assertTrue(is_array($this->model->getOptionArray()));
    }
}
