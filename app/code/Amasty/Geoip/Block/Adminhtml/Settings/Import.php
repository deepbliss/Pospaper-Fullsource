<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Geoip
 */

namespace Amasty\Geoip\Block\Adminhtml\Settings;

use Amasty\Geoip\Block\Adminhtml\Template as TemplateBlock;
use Amasty\Geoip\Model\Import as ImportModel;
use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class Import
 */
class Import extends Field
{
    /** @var
     * ImportModel $import
     */
    protected $import;

    /**
     * DownloadNImport constructor.
     * @param Context $context
     * @param ImportModel $import
     * @param array $data
     */
    public function __construct(
        Context $context,
        ImportModel $import,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->import = $import;
    }

    public function _getElementHtml(AbstractElement $element)
    {
        $importTypes = [
            'location',
            'block',
            'block_v6'
        ];

        $urls = [];
        foreach ($importTypes as $type) {
            $startUrl = $this->getUrl(
                'amasty_geoip/geoip/start',
                [
                    'type'   => $type,
                    'action' => 'import'
                ]
            );

            $processUrl = $this->getUrl(
                'amasty_geoip/geoip/process',
                [
                    'type'   => $type,
                    'action' => 'import'
                ]
            );

            $commitUrl = $this->getUrl(
                'amasty_geoip/geoip/commit',
                [
                    'type'   => $type,
                    'action' => 'import'
                ]
            );

            $urls[] = ['start' => $startUrl, 'process' => $processUrl, 'commit' => $commitUrl];
        }

        $block = $this->getLayout()
            ->createBlock(\Amasty\Geoip\Block\Adminhtml\Template::class)
            ->setTemplate('Amasty_Geoip::import.phtml')
            ->setConfig(json_encode($urls))
        ;

        $this->setImportData($block);

        return $block->toHtml();
    }

    /**
     * @param TemplateBlock $block
     */
    public function setImportData($block)
    {
        $importFilesAvailable = false;

        $fileBlockPath = $block->geoipHelper->getCsvFilePath('block');
        $fileBlockV6Path = $block->geoipHelper->getCsvFilePath('block_v6');
        $fileLocationPath = $block->geoipHelper->getCsvFilePath('location');

        $blockFileExist = false;
        $blockV6FileExist = false;
        $locationFileExist = false;

        if ($block->geoipHelper->isFileExist($fileBlockPath)) {
            $blockFileExist = true;
        }
        if ($block->geoipHelper->isFileExist($fileBlockV6Path)) {
            $blockV6FileExist = true;
        }
        if ($block->geoipHelper->isFileExist($fileLocationPath)) {
            $locationFileExist = true;
        }

        if ($blockFileExist && $locationFileExist && $blockV6FileExist) {
            $importFilesAvailable = true;
        }

        $importDate = '';

        if ($block->geoipHelper->isDone() && $this->import->importTableHasData()) {
            $width = 100;
            $importedClass = 'end_imported';
            if ($block->_scopeConfig->getValue('amgeoip/import/date')) {
                $importDate = __('Last Imported: ') . $block->_scopeConfig->getValue('amgeoip/import/date');
            }
        } else {
            $width = 0;
            $importedClass = 'end_not_imported';
        }
        $block
            ->setWidth($width)
            ->setImportFilesAvailable($importFilesAvailable)
            ->setBlockFileExist($blockFileExist)
            ->setBlockV6FileExist($blockV6FileExist)
            ->setLocationFileExist($locationFileExist)
            ->setImportedClass($importedClass)
            ->setImportDate($importDate);
    }
}
