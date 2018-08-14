<?php
/**
 * Pluginsplanet
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the pluginsplanet.com license that is
 * available through the world-wide-web at this URL:
 * https://www.pluginsplanet.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Pluginsplanet
 * @package     Pluginsplanet_Core
 * @copyright   Copyright (c) 2016 Pluginsplanet (http://www.pluginsplanet.com/)
 * @license     https://www.pluginsplanet.com/LICENSE.txt
 */
namespace Pluginsplanet\Core\Block\Adminhtml\System\Config;

/**
 * Class Head
 * @package Pluginsplanet\Core\Block\Adminhtml\System\Config
 */
class Head extends \Magento\Config\Block\System\Config\Form\Field
{
	/**
	 * @param \Magento\Backend\Block\Template\Context $context
	 * @param array $data
	 */
	public function __construct(
		\Magento\Backend\Block\Template\Context $context,
		array $data = []
	) {
		parent::__construct($context, $data);
	}

	/**
	 * Set template
	 *
	 * @return void
	 */
	protected function _construct()
	{
		parent::_construct();
	}

	/**
	 * Render text
	 *
	 * @param  \Magento\Framework\Data\Form\Element\AbstractElement $element
	 * @return string
	 */
	public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
	{
		$html = '';
		if ($element->getComment()) {
			$html .= '<div style="margin: auto; width: 40%;padding: 10px;">' . $element->getComment() . '</div>';
		}

		return $html;
	}

	/**
	 * Return element html
	 *
	 * @param  \Magento\Framework\Data\Form\Element\AbstractElement $element
	 * @return string
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
	{
		return $this->_toHtml();
	}
}
