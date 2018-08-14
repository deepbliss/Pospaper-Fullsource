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

namespace Pluginsplanet\Core\Block\Adminhtml;

/**
 * Class Extensions
 */
class Extensions extends \Magento\Framework\View\Element\Template
{
	/**
	 * @var \Magento\Framework\Module\FullModuleList
	 */
	private $moduleList;

	/**
	 * @var \Magento\Framework\App\CacheInterface
	 */
	protected $_cache;

	/**
	 * Extensions constructor.
	 * @param \Magento\Framework\View\Element\Template\Context $context
	 * @param \Magento\Framework\Module\FullModuleList $moduleList
	 * @param array $data
	 */
	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Magento\Framework\Module\FullModuleList $moduleList,
		array $data = []
	)
	{
		parent::__construct($context, $data);

		$this->moduleList = $moduleList;
		$this->_cache     = $context->getCache();
	}

	/**
	 * @return array
	 */
	public function getInstalledModules()
	{
		$mageplza_modules = array();
		foreach ($this->moduleList->getAll() as $moduleName => $info) {
			if (strpos($moduleName, 'Pluginsplanet') !== false) {
				$mageplza_modules[$moduleName] = $info['setup_version'];
			}
		}

		return $mageplza_modules;
	}

	/**
	 * @return bool|mixed|string
	 */
	public function getAvailableModules()
	{
		$url    = 'https://www.pluginsplanet.com/api/getVersions.json';
		$result = $this->_cache->load('Pluginsplanet_extensions');
		if ($result) {
			try {
				$jsonData = file_get_contents($url);
			} catch (\Exception $e) {
				return false;
			}
			$this->_cache->save($jsonData, 'Pluginsplanet_extensions');
			$result = $this->_cache->load('Pluginsplanet_extensions');
		}
		$result = json_decode($result, true); //true return array otherwise object

		return $result;
	}
}
