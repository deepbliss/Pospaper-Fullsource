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

use Magento\Swatches\Block\LayeredNavigation\RenderLayered as MageRenderLayered;

/**
 * Class RenderLayered Render Swatches at Layered Navigation
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class RenderLayered extends MageRenderLayered
{
    /**
     * Path to template file.
     *
     * @var string
     */
    protected $_template = 'Webkul_AdvancedLayeredNavigation::renderer.phtml';
}
