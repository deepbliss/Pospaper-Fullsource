<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Plugin\Config\Block\System\Config\Form;

use Magento\Config\Block\System\Config\Form\Field as NativeField;

/**
 * Class Field
 * @package Amasty\Base\Plugin\Config\Block\System\Config\Form
 */
class Field
{
    /**
     * @var \Magento\Framework\View\Asset\Repository
     */
    private $assetRepo;

    public function __construct(
        \Magento\Framework\View\Asset\Repository $assetRepo
    ) {
        $this->assetRepo = $assetRepo;
    }

    /**
     * @param NativeField $field
     * @param string $html
     *
     * @return string
     */
    public function afterRender(
        NativeField $field,
        $html
    ) {
        if (strpos($html, 'tooltip-content') !== false) {
            preg_match('/<img.*?src="(Amasty.*?)"/', $html, $result);
            if (count($result) >=2) {
                $path = $result[1];
                $newPath = $this->assetRepo->getUrl($path);
                if ($newPath) {
                    $html = str_replace($path, $newPath, $html);
                }
            }
        }

        return $html;
    }
}
