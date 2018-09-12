<?php
/**
 * Copyright (c) 2018 MageModule, LLC: All rights reserved
 *
 * LICENSE: This source file is subject to our standard End User License
 * Agreeement (EULA) that is available through the world-wide-web at the
 * following URI: http://www.magemodule.com/magento2-ext-license.html.
 *
 * If you did not receive a copy of the EULA and are unable to obtain it through
 * the web, please send a note to admin@magemodule.com so that we can mail
 * you a copy immediately.
 *
 * @author       MageModule, LLC admin@magemodule.com
 * @copyright   2018 MageModule, LLC
 * @license       http://www.magemodule.com/magento2-ext-license.html
 *
 */

namespace MageModule\OrderImportExport\Test\Integration;

abstract class AbstractTestCase extends \MageModule\Core\Test\Integration\AbstractTestCase
{
    /**
     * @return string
     */
    private function getSampleFilesDir()
    {
        return $this->getFileHelper()->assembleFilepath(
            [
                $this->getDirectoryReader()->getModuleDir('', 'MageModule_OrderImportExport'),
                '/Test/Integration/Files'
            ],
            true
        );
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    private function getSampleFilepath($filename)
    {
        return $this->getFileHelper()->assembleFilepath(
            [
                $this->getSampleFilesDir(),
                $filename
            ]
        );
    }

    /**
     * @param string $filename
     * @param string $delimiter
     * @param string $enclosure
     *
     * @return array
     * @throws \Exception
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getSampleFileData($filename, $delimiter = ',', $enclosure = '"')
    {
        $this->getCsvProcessor()->setDelimiter($delimiter);
        $this->getCsvProcessor()->setEnclosure($enclosure);

        $data = $this->getCsvProcessor()->getData(
            $this->getSampleFilepath($filename)
        );

        $headers = $data[0];
        unset($data[0]);

        $result = [];

        foreach ($data as &$row) {
            $row = array_combine($headers, $row);
            $result[] = ['input' => $row, 'expected' => $row];
        }

        return $result;
    }
}
