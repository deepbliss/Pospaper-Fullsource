<?php

/**
 * Copyright © 2015 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Model\ResourceModel;

/**
 * @copyright Wyomind 2016
 */
class Images extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    protected $_storeId = 0;
    protected $_coreHelper = null;

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Wyomind\Core\Helper\Data $coreHelper,
        $connectionName = null
    )
    {
        $this->_coreHelper = $coreHelper;
        parent::__construct($context, $connectionName);
    }

    public function _construct()
    {
        $this->_init('simplegoogleshopping_feeds', 'id');
    }

    public function getImages()
    {
        if (version_compare($this->_coreHelper->getMagentoVersion(), "2.1.0") == -1) { // Mage version < 2.1.0
            $galValueTable = \Magento\Catalog\Model\ResourceModel\Product\Attribute\Backend\Media::GALLERY_VALUE_TABLE;
            $galTable = \Magento\Catalog\Model\ResourceModel\Product\Attribute\Backend\Media::GALLERY_TABLE;
            $idCol = "entity_id";
        } else {
            $galValueTable = \Magento\Catalog\Model\ResourceModel\Product\Gallery::GALLERY_VALUE_TABLE;
            $galTable = \Magento\Catalog\Model\ResourceModel\Product\Gallery::GALLERY_TABLE;
            $idCol = $this->_coreHelper->moduleIsEnabled("Magento_Enterprise") ? "row_id" : "entity_id";
        }
        $select = $this->getConnection()->select();
        $select->distinct("value")
            ->from(["main" => $this->getTable($galTable)])
            ->joinLeft(["cpemgv" => $this->getTable($galValueTable)], "cpemgv.value_id = main.value_id", ["cpemgv.position", "cpemgv.disabled", "cpemgv." . $idCol])
            ->where("value<>TRIM('') AND(store_id=" . $this->_storeId . " OR  store_id=0)")
            ->order(["position", "value_id"])
            ->group(["value_id"]);

        $gallery = [];
        $mediaGallery = $this->getConnection()->fetchAll($select);
        foreach ($mediaGallery as $media) {
            if ($media["value"] != null && $media["value"] != "") {
                $gallery[$media[$idCol]]["src"][] = $media["value"];
                $gallery[$media[$idCol]]["disabled"][] = $media["disabled"];
            }
        }
        unset($mediaGallery);
        return $gallery;
    }

    public function setStoreId($storeId)
    {
        $this->_storeId = $storeId;
        return $this;
    }
}
