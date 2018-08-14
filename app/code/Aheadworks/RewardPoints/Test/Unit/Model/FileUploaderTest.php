<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Model;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\RewardPoints\Model\FileUploader as RewardPointsFileUploader;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Filesystem;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Store\Model\Store;
use Magento\Framework\Filesystem\Directory\ReadInterface;
use Magento\MediaStorage\Model\File\Uploader as FileUploader;

/**
 * Class FileUploaderTest
 * Test for \Aheadworks\RewardPoints\Model\FileUploader
 *
 * @package Aheadworks\RewardPoints\Test\Unit\Model
 */
class FileUploaderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var RewardPointsFileUploader|\PHPUnit_Framework_MockObject_MockObject
     */
    private $model;

    /**
     * @var UploaderFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $uploaderFactoryMock;

    /**
     * @var Filesystem|\PHPUnit_Framework_MockObject_MockObject
     */
    private $filesystemMock;

    /**
     * @var StoreManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $storeManagerMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->uploaderFactoryMock = $this->getMockBuilder(UploaderFactory::class)
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->storeManagerMock = $this->getMockForAbstractClass(StoreManagerInterface::class);
        $this->filesystemMock = $this->getMockBuilder(Filesystem::class)
            ->setMethods(['getDirectoryRead'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->model = $objectManager->getObject(
            RewardPointsFileUploader::class,
            [
                'uploaderFactory' => $this->uploaderFactoryMock,
                'storeManager' => $this->storeManagerMock,
                'filesystem' => $this->filesystemMock
            ]
        );
    }

    /**
     * Testing of saveToTmpFolder method
     */
    public function testSaveToTmpFolder()
    {
        $baseMediaUrl = 'https://ecommerce.aheadworks.com/pub/media/';
        $tmpMediaPath = '/tmp/media';
        $fileName = 'file.csv';
        $fileSize = '123';
        $fileCode = 'img';
        $filePath = '/var/www/mysite/pub/media/aw_rewardpoints/imports';

        $directoryReadMock = $this->getMockForAbstractClass(ReadInterface::class);
        $directoryReadMock->expects($this->once())
            ->method('getAbsolutePath')
            ->with(RewardPointsFileUploader::FILE_DIR)
            ->willReturn($tmpMediaPath);
        $this->filesystemMock->expects($this->once())
            ->method('getDirectoryRead')
            ->with(DirectoryList::MEDIA)
            ->willReturn($directoryReadMock);

        $uploaderMock = $this->getMockBuilder(FileUploader::class)
            ->setMethods(['setAllowRenameFiles', 'setFilesDispersion', 'setAllowedExtensions', 'save'])
            ->disableOriginalConstructor()
            ->getMock();
        $uploaderMock->expects($this->once())
            ->method('setAllowRenameFiles')
            ->with(true)
            ->willReturnSelf();
        $uploaderMock->expects($this->once())
            ->method('setAllowedExtensions')
            ->with(['csv'])
            ->willReturnSelf();
        $uploaderMock->expects($this->any())
            ->method('save')
            ->with($tmpMediaPath)
            ->willReturn([
                'file' => $fileName,
                'size' => $fileSize,
                'name' => $fileName,
                'path' => $filePath
            ]);

        $this->uploaderFactoryMock->expects($this->once())
            ->method('create')
            ->with(['fileId' => $fileCode])
            ->willReturn($uploaderMock);

        $storeMock = $this->getMockBuilder(Store::class)
            ->setMethods(['getBaseUrl'])
            ->disableOriginalConstructor()
            ->getMock();
        $storeMock->expects($this->once())
            ->method('getBaseUrl')
            ->with(UrlInterface::URL_TYPE_MEDIA)
            ->willReturn($baseMediaUrl);
        $this->storeManagerMock->expects($this->once())
            ->method('getStore')
            ->willReturn($storeMock);

        $this->assertEquals(
            [
                'file' => $fileName,
                'size' => $fileSize,
                'name' => $fileName,
                'url' => $baseMediaUrl . RewardPointsFileUploader::FILE_DIR . '/' . $fileName,
                'path' => $filePath,
                'full_path' => $filePath . '/' . $fileName
            ],
            $this->model->saveToTmpFolder($fileCode)
        );
    }

    /**
     * Testing of getOptions method
     */
    public function testGetMediaUrl()
    {
        $baseMediaUrl = 'https://ecommerce.aheadworks.com/pub/media/';
        $fileName = 'file.csv';

        $storeMock = $this->getMockBuilder(Store::class)
            ->setMethods(['getBaseUrl'])
            ->disableOriginalConstructor()
            ->getMock();
        $storeMock->expects($this->once())
            ->method('getBaseUrl')
            ->with(UrlInterface::URL_TYPE_MEDIA)
            ->willReturn($baseMediaUrl);
        $this->storeManagerMock->expects($this->once())
            ->method('getStore')
            ->willReturn($storeMock);

        $expectedPath = $baseMediaUrl . RewardPointsFileUploader::FILE_DIR . '/' . $fileName;
        $this->assertEquals($expectedPath, $this->model->getMediaUrl($fileName));
    }

    /**
     * Testing of getFullPath method
     */
    public function testGetFullPath()
    {
        $fileName = 'file.csv';
        $filePath = '/var/www/mysite/pub/media/aw_rewardpoints/imports';

        $this->assertEquals(
            $filePath . '/' . $fileName,
            $this->model->getFullPath(['file' => $fileName, 'path' => $filePath])
        );
    }

    /**
     * Testing of getAllowedExtensions method
     */
    public function testGetAllowedExtensions()
    {
        $this->assertTrue(is_array($this->model->getAllowedExtensions()));
    }
}
