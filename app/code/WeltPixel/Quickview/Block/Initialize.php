<?php
namespace WeltPixel\Quickview\Block;

/**
 * Quickview Initialize block
 */
class Initialize extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \WeltPixel\QuickView\Helper\Data
     */
    protected $_helper;

    /**
     * @param \WeltPixel\Quickview\Helper\Data $helper
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(\WeltPixel\Quickview\Helper\Data $helper,
                                \Magento\Framework\View\Element\Template\Context $context,
                                array $data = [])
    {
        $this->_helper = $helper;
        parent::__construct($context, $data);
    }

    /**
     * Returns config
     *
     * @return array
     */
    public function getConfig()
    {
        return [
            'baseUrl' => $this->getBaseUrl(),
            'closeSeconds' => $this->_helper->getCloseSeconds(),
            'showMiniCart' => $this->_helper->getScrollAndOpenMiniCart(),
            'showShoppingCheckoutButtons' => $this->_helper->getShoppingCheckoutButtons()
        ];
    }

    /**
     * Return base url.
     *
     * @codeCoverageIgnore
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }
}
