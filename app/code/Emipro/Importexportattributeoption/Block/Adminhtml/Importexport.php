<?php
namespace Emipro\Importexportattributeoption\Block\Adminhtml;

use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory;

class Importexport extends \Magento\Framework\View\Element\Template
{

    private $eavConfig;
    private $formKey;
    private $filesystem;
    private $uploadFactory;
    private $httpFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        CollectionFactory $collectionFactory,
        \Magento\Framework\Data\Form\FormKey $formKey
    ) {
        $this->eavConfig = $collectionFactory;
        $this->formKey = $formKey;
        parent::__construct($context);
    }

    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    public function getExporturl()
    {
        return $this->_urlBuilder->getUrl("importexportattributeoption/index/export");
    }
    public function getCheckvalidationurl()
    {
        return $this->_urlBuilder->getUrl("importexportattributeoption/index/checkvalidation");
    }

    public function getImporturl()
    {
        return $this->_urlBuilder->getUrl("importexportattributeoption/index/import");
    }

    public function getSelectAttributes()
    {
        $options = [];
        $mod = $this->eavConfig->create();

        foreach ($mod as $item) {
            if (($item->getFrontendInput() == "select" ||
                $item->getFrontendInput() == "multiselect") && $item->getIsUserDefined()) {
                array_push($options, ["label" => $item->getFrontendLabel(), "code" => $item->getAttributeId()]);
            }
        }

        return $options;
    }

    public function getFormKey()
    {
        return $this->formKey->getFormKey();
    }
}
