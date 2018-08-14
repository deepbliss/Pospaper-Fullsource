<?php
namespace Emipro\Importexportattributeoption\Block\Adminhtml\Product\Attribute;

class Edit extends \Magento\Catalog\Block\Adminhtml\Product\Attribute\Edit
{

    protected function _construct()
    {
        $entityAttribute = $this->_coreRegistry->registry('entity_attribute');
        if ($entityAttribute->getFrontendInput() == "select" || $entityAttribute->getFrontendInput() == "multiselect") {
            $path = $this->getExportOptionUrl($entityAttribute->getAttributeId(), $entityAttribute->getAttributeCode());
            $this->addButton(
                'export_options',
                [
                    'label' => __('Export Attribute Options'),
                    'class' => 'save',
                    'onclick' => 'setLocation(\'' . $path . '\')',
                ],
                100
            );
        }
        parent::_construct();
    }

    public function getExportOptionUrl($codeId, $codeLabel)
    {
        $url = $this->_urlBuilder->getUrl("importexportattributeoption/index/export");
        $url .= 'attribute' . '/' . $codeId . '/attributename/' . $codeLabel;
        return $url;
    }
}
