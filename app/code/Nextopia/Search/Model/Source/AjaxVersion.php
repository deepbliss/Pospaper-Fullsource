<?php
/**
 * Date: 10/7/2014
 * Time: 10:54 AM
 */

namespace Nextopia\Search\Model\Source;
use \Magento\Framework\Option\ArrayInterface;

class AjaxVersion implements ArrayInterface {

	/**
	 * Options getter
	 *
	 * @return array
	 */
	public function toOptionArray()
	{
		return [
			['value'=> '1.5.1', 'label'=> __('1.5.1')],
			['value'=> 'nxt-app', 'label'=> __('2.0')],
		];
	}

	/**
	 * Get options in "key-value" format
	 *
	 * @return array
	 */
	public function toArray()
	{
		return [
			'1.5.1' => __('1.5.1'),
			'nxt-app' => __('2.0'),
		];
	}

} 