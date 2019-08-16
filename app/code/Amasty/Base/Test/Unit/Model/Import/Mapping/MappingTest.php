<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Test\Unit\Model\Import\Mapping;

use Amasty\Base\Model\Import\Mapping\Mapping;
use Amasty\Base\Test\Unit\Traits;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class MappingTest
 *
 * @see Mapping
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @codingStandardsIgnoreFile
 */
class UnsubscribeTest extends \PHPUnit\Framework\TestCase
{
    use Traits\ObjectManagerTrait;
    use Traits\ReflectionTrait;

    /**
     * @covers Mapping::processedMapping
     */
    public function testProcessedMapping()
    {
        $model = $this->getObjectManager()->getObject(Mapping::class);
        $this->setProperty($model, 'processedMapping', null, Mapping::class);
        $this->setProperty($model, 'mappings', ['test', 'key' => 'value']);
        $this->assertEquals(['test' => 'test', 'key' => 'value'], $model->processedMapping());
        $this->setProperty($model, 'processedMapping', 'test', Mapping::class);
        $this->assertEquals('test', $model->processedMapping());
    }
}
