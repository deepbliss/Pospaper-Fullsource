<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_AdvancedLayeredNavigation
 * @author    Webkul
 * @copyright Copyright (c) 2010-2018 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace Webkul\AdvancedLayeredNavigation\Block;

use Magento\Catalog\Model\Layer\Filter\FilterInterface;

class FilterRenderer extends \Magento\LayeredNavigation\Block\Navigation\FilterRenderer
{
    /**
     * @var \Magento\Framework\Session\SessionManager
     */
    protected $_session;

    /**
     * @param \Magento\Backend\Block\Template\Context   $context
     * @param \Magento\Framework\Session\SessionManager $session
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Session\SessionManager $session,
        array $data = []
    ) {
        $this->_session = $session;
        parent::__construct($context, $data);
    }

    /**
     * @param FilterInterface $filter
     * @return string
     */
    public function render(FilterInterface $filter)
    {
        $this->assign('filterItems', $filter->getItems());
        $this->assign('filter', $filter);
        $html = $this->_toHtml();
        $this->assign('filterItems', []);
        return $html;
    }

    /**
     * getSessionData
     *
     * @param string
     * @return string
     */
    public function getSessionData($data)
    {
        return $this->_session->getData($data);
    }

    /**
     * getminValue
     *
     * @return Integer
     */
    public function getMinVal($id)
    {
        return $this->_session->getData('min_val_'.$id);
    }

    /**
     * setMinValue
     * @param Integer
     */
    public function setMinVal($val, $id)
    {
        $this->_session->setData( 'min_val_'.$id, $val);
    }

    /**
     * setMaxValue
     * @param Integer
     */
    public function setMaxVal($val, $id)
    {
        $this->_session->setData('max_val_'.$id, $val);
    }

    /**
     * getMaxValue
     * @return Integer
     */
    public function getMaxVal($id)
    {
        return $this->_session->getData('max_val_'.$id);
    }
}
