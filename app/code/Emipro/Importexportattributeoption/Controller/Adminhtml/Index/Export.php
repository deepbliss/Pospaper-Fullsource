<?php
namespace Emipro\Importexportattributeoption\Controller\Adminhtml\Index;

use Magento\Framework\App\Response\Http\FileFactory;

class Export extends \Magento\Backend\App\Action
{

    private $attrOptionCollectionFactory;
    private $storename;
    private $fileFactory;
    private $eavAttribute;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory $attrOptionCollectionFactory,
        \Magento\Store\Model\ResourceModel\Store\Collection $store,
        \Magento\Catalog\Model\ResourceModel\Eav\Attribute $eavAttribute,
        FileFactory $fileFactory
    ) {
        $this->attrOptionCollectionFactory = $attrOptionCollectionFactory;
        $this->storename = $store;
        $this->fileFactory = $fileFactory;
        $this->eavAttribute = $eavAttribute;
        parent::__construct($context);
    }

    public function execute()
    {

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $id = $this->getRequest()->getParam("attribute");

        $attributename = $this->getRequest()->getParam("attributename");
        $storeid = [];
        foreach ($this->storename->getData("store_id") as $store) {
            array_push($storeid, $store["store_id"]);
        }
        $valuear = [];
        $position = [];
        $tmpStoreId = count($storeid);
        for ($i = 0; $i < $tmpStoreId; $i++) {
            $finalarray = [];
            $ind_position = 0;

            $options = $this->attrOptionCollectionFactory->create()->setAttributeFilter(
                $id
            )->setStoreFilter($storeid[$i]);

            $options->getSelect()->joinLeft(
                ['swatch_table' => $options->getTable('eav_attribute_option_swatch')],
                'swatch_table.option_id = main_table.option_id AND swatch_table.store_id = ' . $storeid[$i],
                'swatch_table.value AS label'
            );
            $productMetadata = $objectManager->get('Magento\Framework\App\ProductMetadataInterface');
            $version = $productMetadata->getVersion();
            $attr_model = $this->getEavAttribute($id);

            if ($version < '2.2.0') {
                if ($attr_model->getAdditionalData() != "") {
                    $unserialData = unserialize($attr_model->getAdditionalData());
                }
            } else {
                if ($attr_model->getAdditionalData() != "") {
                    $serializer = $this->_objectManager->get('Magento\Framework\Serialize\Serializer\Json');
                    $unserialData = $serializer->unserialize($attr_model->getAdditionalData());
                }

            }

            if (!empty($options)) {
                if ($this->getCount($options) > 0) {
                    foreach ($options as $option) {
                        $values[$option->getId()] = $option->getValue();
                        if ($option->getLabel()) {
                            $swatchValue = $option->getLabel();
                        } else {
                            $swatchValue = '';
                        }
                        array_push($finalarray, $swatchValue);
                        array_push($finalarray, $option["store_default_value"]);
                        $ind_position = $option["sort_order"];
                        array_push($position, $ind_position);
                    }

                } else {
                    $this->fileFactory->create('export.csv', "No Options Available", 'var');
                }
            } else {
                $this->fileFactory->create('export.csv', "No Options Available", 'var');
            }
            array_push($valuear, $finalarray);
        }
        if (isset($unserialData['swatch_input_type'])) {
            if ($unserialData['swatch_input_type'] == "text") {
                $csvdata = "";
                $csvdata .= "VisualSwatch,Admin,Adminview";
                $tmpCountStore = count($storeid);
                for ($i = 1; $i < $tmpCountStore; $i++) {
                    $csvdata .= ',TextSwatch' . $i . ',StoreView' . $i;
                }
                $csvdata .= ",Position";
                $csvdata .= "\n";

                /*
                 * attribute option for text swatch
                 */
                $tmpCount1 = count($valuear[0]);
                $tmpI = 0;
                for ($i = 0; $i < $tmpCount1; $i++) {
                    $csvdata .= ",";
                    foreach ($valuear as $val) {
                        $csvdata .= $val[$i] . ",";
                        $csvdata .= $val[$i + 1] . ",";
                    }
                    $i++;
                    $csvdata .= $position[$tmpI] . "\n";
                    $tmpI++;
                }
            } else {
                /*
                 * for header information
                 */
                $csvdata = "";
                $csvdata .= "VisualSwatch,Admin";
                $tmpCountStore = count($storeid);
                for ($i = 1; $i < $tmpCountStore; $i++) {
                    $csvdata .= ',TextSwatch' . $i . ',StoreView' . $i;
                }
                $csvdata .= ",Position";
                $csvdata .= "\n";

                /*
                 * attribute option
                 */
                $tmpCount1 = count($valuear[0]);
                $tmpI = 0;
                for ($i = 0; $i < $tmpCount1; $i++) {
                    foreach ($valuear as $val) {
                        $csvdata .= $val[$i] . ",";
                        $csvdata .= $val[$i + 1] . ",";
                    }
                    $i++;
                    $csvdata .= $position[$tmpI] . "\n";
                    $tmpI++;
                }
            }
        } else {
            /*
             * for header information
             */
            $csvdata = "";
            $csvdata .= "VisualSwatch,Admin";
            $tmpCountStore = count($storeid);
            for ($i = 1; $i < $tmpCountStore; $i++) {
                $csvdata .= ',TextSwatch' . $i . ',StoreView' . $i;
            }
            $csvdata .= ",Position";
            $csvdata .= "\n";

            /*
             * attribute option
             */
            $tmpCount1 = count($valuear[0]);
            $tmpI = 0;
            for ($i = 0; $i < $tmpCount1; $i++) {
                foreach ($valuear as $val) {
                    $csvdata .= $val[$i] . ",";
                    $csvdata .= $val[$i + 1] . ",";
                }
                $i++;
                $csvdata .= $position[$tmpI] . "\n";
                $tmpI++;
            }
        }
        $this->fileFactory->create($attributename . '_Options.csv', $csvdata, 'var');
    }

    private function getCount($count)
    {
        return count($count);
    }

    private function getEavAttribute($id)
    {
        $attr_model = $this->eavAttribute->load($id);
        return $attr_model;
    }
}
