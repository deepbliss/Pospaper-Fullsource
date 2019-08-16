<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Geoip
 */


namespace Amasty\Geoip\Test\Unit\Model;

use Amasty\Geoip\Model\Import;
use Amasty\Geoip\Test\Unit\Traits;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\App\Config\ConfigResource\ConfigInterface;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class ImportTest
 *
 * @see ImportTest
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @codingStandardsIgnoreFile
 */
class ImportTest extends \PHPUnit\Framework\TestCase
{
    use Traits\ObjectManagerTrait;
    use Traits\ReflectionTrait;

    const TEST_FILE = BP . '/' . 'index.php';

    /**
     * @var \Magento\Framework\App\Config\ConfigResource\ConfigInterface|MockObject
     */
    private $config;

    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface|MockObject
     */
    private $connection;

    /**
     * @var \Magento\Framework\App\ResourceConnection|MockObject
     */
    private $resource;

    /**
     * @var Import
     */
    private $model;

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    private $fileDriver;

    public function setUp()
    {
        /** @var File $fileDriver */
        $this->fileDriver = $this->getObjectManager()->getObject(File::class);
        /** @var ConfigInterface|MockObject $config */
        $this->config = $this->createMock(ConfigInterface::class);
        $this->config->expects($this->any())->method('saveConfig')->willReturn($this->config);

        /** @var \Magento\Framework\DB\Adapter\AdapterInterface|MockObject connection */
        $this->connection = $this->createMock(\Magento\Framework\DB\Adapter\AdapterInterface::class);

        /** @var \Magento\Framework\App\ResourceConnection|MockObject resource */
        $this->resource = $this->createMock(\Magento\Framework\App\ResourceConnection::class);
        $this->resource->expects($this->any())->method('getConnection')->willReturn($this->connection);
        $this->resource->expects($this->any())->method('getTableName')->willReturn('amasty_geoip_search_test');

        /** @var Import|MockObject $model */
        $this->model = $this->getObjectManager()->getObject(Import::class,
            [
                'configInterface' => $this->config,
                'resource' => $this->resource,
                'driverFile' => $this->fileDriver
            ]
        );
    }

    /**
     * @covers Import::commitProcess
     *
     * @throws \ReflectionException
     */
    public function testCommitProcess()
    {
        $this->connection->expects($this->any())->method('isTableExists')->willReturn(true);

        $this->assertTrue($this->model->commitProcess('test_table'));
    }

    /**
     * @covers Import::doProcess
     *
     * @dataProvider startProcessAndDoProccessDataProvider
     *
     * @throws \ReflectionException
     */
    public function testDoProcess($key)
    {
        $result = $this->model->doProcess('test_table', self::TEST_FILE, 'import');

        $this->assertArrayHasKey($key, $result);
    }

    /**
     * @covers Import::startProcess
     *
     * @dataProvider startProcessAndDoProccessDataProvider
     *
     * @throws \ReflectionException
     */
    public function testStartProcess($key, $expectedResult = null)
    {
        $this->connection->expects($this->any())->method('getTables')->willReturn(['table1, table2']);

        /** @var Import|MockObject $model */
        $model = $this->getMockBuilder(Import::class)
            ->disableOriginalConstructor()
            ->setMethods(['_prepareImport', '_getRowsCount', '_saveInDb'])
            ->getMock();

        $helper = $this->createMock(\Amasty\Geoip\Helper\Data::class);
        $helper->expects($this->any())->method('flushConfigCache');

        $this->setProperty($model, 'driverFile', $this->fileDriver, Import::class);
        $this->setProperty($model, 'resource', $this->resource, Import::class);
        $this->setProperty($model, 'helper', $helper, Import::class);
        $this->setProperty($model, 'configInterface', $this->config, Import::class);

        $model->expects($this->any())->method('_prepareImport')->willReturn(true);
        $model->expects($this->any())->method('_getRowsCount')->willReturn(10);
        $model->expects($this->any())->method('_saveInDb')->willReturn(true);

        $result = $model->startProcess('block', self::TEST_FILE, 'import');

        $this->assertArrayHasKey($key, $result);
    }

    /**
     * Data provider for startProcess and doProcess test
     * @return array
     */
    public function startProcessAndDoProccessDataProvider()
    {
        return [
            ['position'],
            ['tmp_table'],
            ['rows_count'],
            ['current_row']
        ];
    }
}
