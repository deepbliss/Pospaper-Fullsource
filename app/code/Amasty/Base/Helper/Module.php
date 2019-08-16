<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Helper;

use SimpleXMLElement;
use Zend\Http\Client\Adapter\Curl as CurlClient;
use Zend\Http\Response as HttpResponse;
use Zend\Uri\Http as HttpUri;
use Magento\Framework\Json\DecoderInterface;

/**
 * Class Module
 * @package Amasty\Base\Helper
 */
class Module
{
    const EXTENSIONS_PATH = 'ambase_extensions';

    const URL_EXTENSIONS = 'http://amasty.com/feed-extensions-m2.xml';

    /**
     * @var \Amasty\Base\Model\Serializer
     */
    protected $serializer;

    /**
     * @var CurlClient
     */
    protected $curlClient;

    /**
     * @var \Magento\Framework\App\CacheInterface
     */
    protected $cache;

    /**
     * @var array|null
     */
    private $modulesData = null;

    /**
     * @var array
     */
    protected $restrictedModules = [
        'Amasty_CommonRules',
        'Amasty_Router'
    ];

    /**
     * @var \Magento\Framework\Module\Dir\Reader
     */
    private $moduleReader;

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    private $filesystem;

    /**
     * @var DecoderInterface
     */
    private $jsonDecoder;

    /**
     * @var \Magento\Framework\Escaper
     */
    private $escaper;

    public function __construct(
        \Amasty\Base\Model\Serializer $serializer,
        \Magento\Framework\App\CacheInterface $cache,
        \Magento\Framework\Module\Dir\Reader $moduleReader,
        \Magento\Framework\Filesystem\Driver\File $filesystem,
        DecoderInterface $jsonDecoder,
        CurlClient $curl,
        \Magento\Framework\Escaper $escaper
    ) {
        $this->cache = $cache;
        $this->serializer = $serializer;
        $this->curlClient = $curl;
        $this->moduleReader = $moduleReader;
        $this->filesystem = $filesystem;
        $this->jsonDecoder = $jsonDecoder;
        $this->escaper = $escaper;
    }

    /**
     * Get array with info about all Amasty Magento2 Extensions
     *
     * @return bool|mixed
     */
    public function getAllExtensions()
    {
        $serialized = $this->cache->load(self::EXTENSIONS_PATH);
        if ($serialized === false) {
            $this->reload();
            $serialized = $this->cache->load(self::EXTENSIONS_PATH);
        }
        $result = $this->serializer->unserialize($serialized);

        return $result;
    }

    /**
     * Save extensions data to magento cache
     */
    public function reload()
    {
        $feedData = [];
        $feedXml = $this->getFeedData();
        if ($feedXml && $feedXml->channel && $feedXml->channel->item) {
            $moduleInfo = $this->getModuleInfo('Amasty_Base');
            $origin = isset($moduleInfo['extra']['origin']) ? $moduleInfo['extra']['origin'] : null;
            foreach ($feedXml->channel->item as $item) {
                $code = $this->escaper->escapeHtml((string)$item->code);

                if (!isset($feedData[$code])) {
                    $feedData[$code] = [];
                }

                $title = $this->escaper->escapeHtml((string)$item->title);

                $productPageLink = $origin == 'marketplace' ? $item->market_link : $item->link;
                $feedData[$code][$title] = [
                    'name'               => $title,
                    'url'                => $this->escaper->escapeUrl((string)($productPageLink)),
                    'version'            => $this->escaper->escapeHtml((string)$item->version),
                    'conflictExtensions' => $this->escaper->escapeHtml((string)$item->conflictExtensions),
                    'guide'              => $this->escaper->escapeUrl((string)$item->guide),
                ];
            }

            if ($feedData) {
                $this->cache->save($this->serialize($feedData), self::EXTENSIONS_PATH);
            }
        }
    }

    /**
     * Read data from xml file with curl
     *
     * @return bool|SimpleXMLElement
     */
    protected function getFeedData()
    {
        try {
            $curlClient = $this->getCurlClient();

            $location = self::URL_EXTENSIONS;
            $uri = new HttpUri($location);

            $curlClient->setOptions(
                [
                    'timeout' => 8
                ]
            );

            $curlClient->connect($uri->getHost(), $uri->getPort());
            $curlClient->write('GET', $uri, 1.0);
            $data = HttpResponse::fromString($curlClient->read());

            $curlClient->close();

            $xml = new SimpleXMLElement($data->getContent());
        } catch (\Exception $e) {
            return false;
        }

        return $xml;
    }

    /**
     * Returns the cURL client that is being used.
     *
     * @return CurlClient
     */
    public function getCurlClient()
    {
        if ($this->curlClient === null) {
            $this->curlClient = new CurlClient();
        }

        return $this->curlClient;
    }

    public function serialize($data)
    {
        return $this->serializer->serialize($data);
    }

    /**
     * @return array
     */
    public function getRestrictedModules()
    {
        return $this->restrictedModules;
    }

    /**
     * Read info about extension from composer json file
     *
     * @param string $moduleCode
     *
     * @return array
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getModuleInfo($moduleCode)
    {
        try {
            $dir = $this->moduleReader->getModuleDir('', $moduleCode);
            $file = $dir . '/composer.json';

            $string = $this->filesystem->fileGetContents($file);
            $json = $this->jsonDecoder->decode($string);
        } catch (\Magento\Framework\Exception\FileSystemException $e) {
            $json = [];
        }

        return $json;
    }

    /**
     * @param string $moduleCode
     *
     * @return array
     */
    public function getFeedModuleData($moduleCode)
    {
        $moduleData = [];
        if ($this->modulesData === null || $this->modulesData === false) {
            $this->modulesData = $this->getAllExtensions();
        }

        if ($this->modulesData && isset($this->modulesData[$moduleCode])) {
            $module = $this->modulesData[$moduleCode];
            if ($module && is_array($module)) {
                $module = array_shift($module);
            }

            $moduleData = $module;
        }

        return $moduleData;
    }
}
