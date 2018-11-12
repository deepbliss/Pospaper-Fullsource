<?php
namespace WeltPixel\GoogleTagManager\Block\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class Api
 * @package WeltPixel\GoogleTagManager\Block\System\Config
 */
class Api extends Field
{

    protected $_template = 'WeltPixel_GoogleTagManager::system/config/api_container.phtml';

    /**
     * @var \WeltPixel\GoogleTagManager\Model\Api
     */
    protected $apiModel = null;

    /**
     * @var string
     */
    protected $itemPostUrl = null;

    /**
     * Version constructor.
     * @param \WeltPixel\GoogleTagManager\Model\Api $apiModel
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        \WeltPixel\GoogleTagManager\Model\Api $apiModel,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->apiModel = $apiModel;
    }


    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->_toHtml();
    }


    /**
     * @return \WeltPixel\GoogleTagManager\Model\Api
     */
    public function getApiModel()
    {
        return $this->apiModel;
    }

    /**
     * @return string
     */
    public function getUrlForItemsCreation() {
        $this->itemPostUrl = $this->_urlBuilder->getUrl('googletagmanager/items/create');
        return $this->itemPostUrl;
    }
}