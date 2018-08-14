<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Block\Adminhtml\System\Config;

use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Module\ModuleListInterface;

class Information extends \Magento\Config\Block\System\Config\Form\Fieldset
{
    /**
     * @var \Amasty\Base\Helper\CssChecker
     */
    private $cssChecker;

    /**
     * @var ModuleListInterface
     */
    private $moduleList;

    /**
     * @var string
     */
    private $userGuide = 'https://amasty.com/docs/doku.php?id=magento_2%3Aone_step_checkout&utm_source=extension&' .
    'utm_medium=link&utm_campaign=osc-guide';

    /**
     * @var array
     */
    private $enemyExtensions = [];

    /**
     * @var string
     */
    private $content;

    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Framework\View\Helper\Js $jsHelper,
        \Amasty\Base\Helper\CssChecker $cssChecker,
        ModuleListInterface $moduleList,
        array $data = []
    ) {
        parent::__construct($context, $authSession, $jsHelper, $data);
        $this->cssChecker = $cssChecker;
        $this->moduleList = $moduleList;
    }

    /**
     * Render fieldset html
     *
     * @param AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $html = $this->_getHeaderHtml($element);
        $this->setContent(__('Please update Amasty Base module. Re-upload it and replace all the files.'));

        $this->_eventManager->dispatch(
            'amasty_base_add_information_content',
            ['block' => $this]
        );

        $html .= $this->getContent();
        $html .= $this->_getChildrenElementsHtml($element);
        $html .= $this->_getFooterHtml($element);
        $html = str_replace(
            'amasty_information]" type="hidden" value="0"',
            'amasty_information]" type="hidden" value="1"',
            $html
        );

        $html = preg_replace('(onclick=\"Fieldset.toggleCollapse.*?\")', '', $html);

        return $html;
    }

    /**
     * @return array|string
     */
    public function getAdditionalModuleContent()
    {
        $corruptedWebsites = $this->cssChecker->getCorruptedWebsites();
        if ($corruptedWebsites) {
            return [[
                'type' => 'message-error',
                'text' =>
                    __(
                        'The default Magento LESS functionality is missing for the following website(s): %1. '
                        . 'To add CSS please set "Include CSS" setting to "Yes".',
                        implode(', ', $corruptedWebsites)
                    )
            ]];
        }

        return '';
    }

    /**
     * @return string
     */
    public function getUserGuide()
    {
        return $this->userGuide;
    }

    /**
     * @param string $userGuide
     */
    public function setUserGuide($userGuide)
    {
        $this->userGuide = $userGuide;
    }

    /**
     * @return array
     */
    public function getEnemyExtensions()
    {
        return $this->enemyExtensions;
    }

    /**
     * @param array $enemyExtensions
     */
    public function setEnemyExtensions($enemyExtensions)
    {
        $this->enemyExtensions = $enemyExtensions;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }
}
