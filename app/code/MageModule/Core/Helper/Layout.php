<?php
/**
 * Copyright (c) 2018 MageModule: All rights reserved
 *
 * LICENSE: This source file is subject to our standard End User License
 * Agreeement (EULA) that is available through the world-wide-web at the
 * following URI: http://www.magemodule.com/magento2-ext-license.html.
 *
 * If you did not receive a copy of the EULA and are unable to obtain it through
 * the web, please send a note to admin@magemodule.com so that we can mail
 * you a copy immediately.
 *
 * @author       MageModule admin@magemodule.com
 * @copyright   2018 MageModule
 * @license       http://www.magemodule.com/magento2-ext-license.html
 *
 */

namespace MageModule\Core\Helper;

class Layout extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\App\ViewInterface
     */
    private $view;

    /**
     * Layout constructor.
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\App\ViewInterface  $view
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\ViewInterface $view
    ) {
        parent::__construct($context);
        $this->view = $view;
    }

    /**
     * @return \Magento\Framework\App\ViewInterface
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @return \Magento\Framework\View\LayoutInterface
     */
    public function getLayout()
    {
        return $this->getView()->getLayout();
    }

    /**
     * @return \Magento\Framework\View\Layout\ProcessorInterface
     */
    public function getUpdate()
    {
        return $this->getLayout()->getUpdate();
    }

    /**
     * @return array
     */
    public function getHandles()
    {
        $handles = $this->getUpdate()->getHandles();
        $handles[] = $this->getView()->getDefaultLayoutHandle();

        return $handles;
    }

    /**
     * @param string $handle
     *
     * @return bool
     */
    public function isCurrentHandle($handle)
    {
        return in_array($handle, $this->getHandles());
    }

    /**
     * @param string $handle
     *
     * @return $this
     */
    public function addHandle($handle)
    {
        $this->getUpdate()->addHandle($handle);

        return $this;
    }
}
