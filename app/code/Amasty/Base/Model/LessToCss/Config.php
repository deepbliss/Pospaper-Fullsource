<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Model\LessToCss;

use Magento\Framework\Config\CacheInterface;
use Amasty\Base\Model\LessToCss\Config\Reader;

/**
 * Extension attributes config
 */
class Config extends \Magento\Framework\Config\Data
{
    const CACHE_ID = 'amasty_less_to_css';

    /**
     * Initialize reader and cache.
     *
     * @param Reader $reader
     * @param CacheInterface $cache
     */
    public function __construct(
        Reader $reader,
        CacheInterface $cache
    ) {
        parent::__construct($reader, $cache, self::CACHE_ID);
    }
}
