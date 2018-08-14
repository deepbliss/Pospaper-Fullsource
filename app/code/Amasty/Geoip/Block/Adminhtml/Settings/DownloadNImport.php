<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Geoip
 */

namespace Amasty\Geoip\Block\Adminhtml\Settings;

class DownloadNImport extends \Magento\Config\Block\System\Config\Form\Field
{
    /** @var \Amasty\Geoip\Model\Import $import */
    protected $import;

    /**
     * DownloadNImport constructor.
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Amasty\Geoip\Model\Import              $import
     * @param array                                   $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Amasty\Geoip\Model\Import $import,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->import = $import;
    }

    /**
     * Return element html
     *
     * @param  \Magento\Framework\Data\Form\Element\AbstractElement $element
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $importTypes = [
            'location',
            'block'
        ];

        $urls = [];
        foreach ($importTypes as $type) {
            $startDownloadingUrl = $this->getUrl(
                'amasty_geoip/geoip/startDownloading',
                [
                    'type'   => $type,
                    'action' => 'download_and_import'
                ]
            );

            $startUrl = $this->getUrl(
                'amasty_geoip/geoip/start',
                [
                    'type'   => $type,
                    'action' => 'download_and_import'
                ]
            );

            $processUrl = $this->getUrl(
                'amasty_geoip/geoip/process',
                [
                    'type'   => $type,
                    'action' => 'download_and_import'
                ]
            );

            $commitUrl = $this->getUrl(
                'amasty_geoip/geoip/commit',
                [
                    'type'   => $type,
                    'action' => 'download_and_import'
                ]
            );

            $urls[] = [
                'start'    => $startUrl,
                'process'  => $processUrl,
                'commit'   => $commitUrl,
                'download' => $startDownloadingUrl
            ];
        }

        $block = $this->getLayout()
            ->createBlock('Amasty\Geoip\Block\Adminhtml\Template')
            ->setTemplate('Amasty_Geoip::download_n_import.phtml')
            ->setConfig(json_encode($urls));
        $this->setDNIData($block);

        return $block->toHtml();
    }

    public function setDNIData($block)
    {
        if ($block->geoipHelper->isDone() && $this->import->importTableHasData()) {
            $width          = 100;
            $completedClass = "end_downloading_completed";
            $importedClass  = "end_imported";
            $importDate     = $block->_scopeConfig->getValue('amgeoip/import/date_download');
            if (!empty($importDate)) {
                $importDate = __('Last Imported: ') . $importDate;
            }
        } else {
            $width          = 0;
            $completedClass = "end_downloading_not_completed";
            $importedClass  = "end_not_imported";
            $importDate     = '';
        }
        $block->setWidth($width)
            ->setCompletedClass($completedClass)
            ->setImportedClass($importedClass)
            ->setImportDate($importDate);
    }
}
