<?php
/**
 * Date: 10/7/2014
 * Time: 10:54 AM
 */

namespace Nextopia\Search\Model\Source;
use \Magento\Framework\Option\ArrayInterface;

class Layout implements ArrayInterface {

	/**
	 * Options getter
	 *
	 * @return array
	 */
	public function toOptionArray()
	{
		return [
			['value'=> '1column', 'label'=> __('1 column')],
			['value'=> '2columns-left', 'label'=> __('2 column with Left bar')],
			['value'=> '2columns-right', 'label'=> __('2 column with right bar')],
			['value'=> '3columns', 'label'=> __('3 column')]
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
			'1column' => __('1 column'),
			'2columns-left' => __('2 column with Left bar'),
			'2columns-right' => __('2 column with right bar'),
			'3columns' => __('3 column')
		];
	}

} 