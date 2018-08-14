<?php

/**
 * Magestore.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Magestore
 * @package     Magestore_Megamenu
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */
namespace Magestore\Megamenu\Controller\Adminhtml;

/**
 * Action Megamenu
 */
abstract class Megamenu extends \Magento\Backend\App\Action
{
    /**
     * @var \Magestore\Megamenu\Model\ResourceModel\Megamenu\CollectionFactory
     */
    protected $_collectionFactory;
    /**
     * @var \Magento\Config\Model\ResourceModel\Config
     */
    protected $_configResoure;
    /**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $_typeListInterface;
    /**
     * @var \Magento\Framework\App\CacheInterface
     */
    protected $_cacheInterface;
    /**
     * @var \Magestore\Megamenu\Helper\Image
     */
    protected $_imageHelper;

    /**
     * Megamenu constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magestore\Megamenu\Helper\Image $imageHelper
     * @param \Magento\Framework\App\CacheInterface $cacheInterface
     * @param \Magento\Framework\App\Cache\TypeListInterface $typeListInterface
     * @param \Magento\Config\Model\ResourceModel\Config $configResoure
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magestore\Megamenu\Helper\Image $imageHelper,
        \Magento\Framework\App\CacheInterface $cacheInterface,
        \Magento\Framework\App\Cache\TypeListInterface $typeListInterface,
        \Magento\Config\Model\ResourceModel\Config $configResoure,
        \Magestore\Megamenu\Model\ResourceModel\Megamenu\CollectionFactory $collectionFactory
    ) {
        parent::__construct($context);
        $this->_imageHelper =$imageHelper;
        $this->_cacheInterface = $cacheInterface;
        $this->_typeListInterface = $typeListInterface;
        $this->_configResoure = $configResoure;
        $this->_collectionFactory = $collectionFactory;
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magestore_Megamenu::magestoremegamenu');
    }
}
