<?php
/**
 * Copyright (c) 2018 MageModule: All rights reserved
 *
 * LICENSE: This source file is subject to our standard End User License
 * Agreeement (EULA) that is available through the world-wide-web at the
 * following URI: http://www.magemodule.com/magento2-ext-license.html.
 *
 * If you did not receive a copy of the EULA and are unable to obtain it through
 * the web, please send a note to admin@magemodule.com so that we can mail
 * you a copy immediately.
 *
 * @author        MageModule admin@magemodule.com
 * @copyright     2018 MageModule
 * @license       http://www.magemodule.com/magento2-ext-license.html
 *
 */

namespace MageModule\Core\Helper;

class File extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    private $directoryList;

    /**
     * @var \Magento\Framework\Filesystem\Io\File
     */
    private $fileIo;

    /**
     * File constructor.
     *
     * @param \Magento\Framework\App\Helper\Context           $context
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Filesystem\Io\File $fileIo
    ) {
        parent::__construct($context);
        $this->directoryList = $directoryList;
        $this->fileIo        = $fileIo;
    }

    /**
     * If the filepath begins with "/" it is considered to already be the absolute path. If it does
     * not begin with a "/" then the Magento root path will be prepended to the filepath
     *
     * @param string $filepath
     *
     * @return string
     */
    public function getAbsolutePath($filepath)
    {
        if (strpos($filepath, DIRECTORY_SEPARATOR) !== 0) {
            $filepath = $this->assembleFilepath([
                $this->directoryList->getRoot(),
                $filepath
            ]);
        }

        return $filepath;
    }

    /**
     * @param string $filepath
     *
     * @return string
     */
    public function getRelativePath($filepath)
    {
        $root = $this->directoryList->getRoot();
        if (strpos($filepath, $root) === 0) {
            $filepath = ltrim(substr($filepath, strlen($root)), DIRECTORY_SEPARATOR);
        }

        return $filepath;
    }

    /**
     * @param array  $parts
     * @param string $glue
     *
     * @return string
     */
    public function assembleFilepath(array $parts, $absolute = true, $glue = DIRECTORY_SEPARATOR)
    {
        $parts = array_map(function ($value) use ($glue) {
            return trim($value, $glue);
        }, $parts);

        $filepath = implode($glue, $parts);
        if ($absolute === true) {
            $filepath = $glue . $filepath;
        }

        return $filepath;
    }

    /**
     * Is file exists
     *
     * @param string $file
     * @param bool   $onlyFile
     *
     * @return bool
     */
    public function fileExists($file, $onlyFile = true)
    {
        return $this->fileIo->fileExists($file, $onlyFile);
    }

    /**
     * @param string $filepath
     *
     * @return string|null
     */
    public function getDirname($filepath)
    {
        $info = $this->fileIo->getPathInfo($filepath);

        return isset($info['dirname']) ? $info['dirname'] : null;
    }

    /**
     * @param string $filepath
     *
     * @return string|null
     */
    public function getBasename($filepath)
    {
        $info = $this->fileIo->getPathInfo($filepath);

        return isset($info['basename']) ? $info['basename'] : null;
    }

    /**
     * @param string $filepath
     *
     * @return string|null
     */
    public function getExtension($filepath)
    {
        $info = $this->fileIo->getPathInfo($filepath);

        return isset($info['extension']) ? $info['extension'] : null;
    }

    /**
     * @param string $filepath
     *
     * @return string|null
     */
    public function getFilename($filepath)
    {
        $info = $this->fileIo->getPathInfo($filepath);

        return isset($info['filename']) ? $info['filename'] : null;
    }
}
