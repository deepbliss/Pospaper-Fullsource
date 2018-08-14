<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Geoip
 */

namespace Amasty\Geoip\Block\Adminhtml\Settings;

class Import extends \Magento\Config\Block\System\Config\Form\Field
{
    /** @var \Amasty\Geoip\Model\Import $import */
    protected $import;

    /**
     * DownloadNImport constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Amasty\Geoip\Model\Import $import
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Amasty\Geoip\Model\Import $import,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->import = $import;
    }

    public function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $importTypes = array(
            'location',
            'block'
        );

        $urls = [];
        foreach ($importTypes as $type) {
            $startUrl = $this->getUrl('amasty_geoip/geoip/start', array(
                'type' => $type,
                'action' => 'import'
            ))
            ;

            $processUrl = $this->getUrl('amasty_geoip/geoip/process', array(
                'type' => $type,
                'action' => 'import'
            ))
            ;

            $commitUrl = $this->getUrl('amasty_geoip/geoip/commit', array(
                'type' => $type,
                'action' => 'import'
            ))
            ;

            $urls[] = ['start' => $startUrl, 'process' => $processUrl, 'commit' => $commitUrl];
        }

        $block = $this->getLayout()
            ->createBlock('Amasty\Geoip\Block\Adminhtml\Template')
            ->setTemplate('Amasty_Geoip::import.phtml')
            ->setConfig(json_encode($urls))
        ;

        $this->setImportData($block);

        return $block->toHtml();
    }

    public function setImportData($block)
    {
        $importFilesAvailable = false;

        $fileBlockPath = $block->geoipHelper->getCsvFilePath('block', 'import');
        $fileLocationPath = $block->geoipHelper->getCsvFilePath('location', 'import');

        $blockFileExist = false;
        $locationFileExist = false;
        if ($block->geoipHelper->isFileExist($fileBlockPath)) {
            $blockFileExist = true;
        }
        if ($block->geoipHelper->isFileExist($fileLocationPath)) {
            $locationFileExist = true;
        }

        if ($blockFileExist && $locationFileExist) {
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
            ->setLocationFileExist($locationFileExist)
            ->setImportedClass($importedClass)
            ->setImportDate($importDate);
    }
}
