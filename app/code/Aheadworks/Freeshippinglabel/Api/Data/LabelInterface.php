<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Freeshippinglabel\Api\Data;

/**
 * Label interface
 * @api
 */
interface LabelInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    /**#@+
     * Constants defined for keys of the data array.
     * Identical to the name of the getter in snake case
     */
    const ID = 'id';
    const ENABLED = 'is_enabled';
    const CUSTOMER_GROUP_IDS = 'customer_group_ids';
    const GOAL = 'goal';
    const PAGE_TYPE = 'page_type';
    const POSITION = 'position';
    const DELAY = 'delay';
    const CONTENT = 'content';
    const FONT_NAME = 'font_name';
    const FONT_SIZE = 'font_size';
    const FONT_WEIGHT = 'font_weight';
    const FONT_COLOR = 'font_color';
    const GOAL_FONT_COLOR = 'goal_font_color';
    const BACKGROUND_COLOR = 'background_color';
    const TEXT_ALIGN = 'text_align';
    const CUSTOM_CSS = 'custom_css';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set ID
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Get is enabled
     *
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     * @return bool
     */
    public function getIsEnabled();

    /**
     * Set is enabled
     *
     * @param bool $isEnabled
     * @return $this
     */
    public function setIsEnabled($isEnabled);

    /**
     * Get customer group IDs
     *
     * @return int[]
     */
    public function getCustomerGroupIds();

    /**
     * Set customer group IDs
     *
     * @param array $groupIds
     * @return $this
     */
    public function setCustomerGroupIds($groupIds);

    /**
     * Get goal
     *
     * @return int
     */
    public function getGoal();

    /**
     * Set goal
     *
     * @param int $goal
     * @return $this
     */
    public function setGoal($goal);

    /**
     * Get page type
     *
     * @return string
     */
    public function getPageType();

    /**
     * Set page type
     *
     * @param string $pageType
     * @return $this
     */
    public function setPageType($pageType);

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition();

    /**
     * Set position
     *
     * @param string $position
     * @return $this
     */
    public function setPosition($position);

    /**
     * Get delay
     *
     * @return int
     */
    public function getDelay();

    /**
     * Set delay
     *
     * @param int $delay
     * @return $this
     */
    public function setDelay($delay);

    /**
     * Get content per store view
     *
     * @return \Aheadworks\Freeshippinglabel\Api\Data\LabelContentInterface[]
     */
    public function getContent();

    /**
     * Set content per store view
     *
     * @param \Aheadworks\Freeshippinglabel\Api\Data\LabelContentInterface[] $content
     * @return $this
     */
    public function setContent($content);

    /**
     * Get font name
     *
     * @return string
     */
    public function getFontName();

    /**
     * Set font name
     *
     * @param string $fontName
     * @return $this
     */
    public function setFontName($fontName);

    /**
     * Get font size
     *
     * @return int
     */
    public function getFontSize();

    /**
     * Set font size
     *
     * @param int $fontSize
     * @return $this
     */
    public function setFontSize($fontSize);

    /**
     * Get font weight
     *
     * @return string
     */
    public function getFontWeight();

    /**
     * Set font weight
     *
     * @param string $fontWeight
     * @return $this
     */
    public function setFontWeight($fontWeight);

    /**
     * Get font color
     *
     * @return string
     */
    public function getFontColor();

    /**
     * Set font color
     *
     * @param string $fontColor
     * @return $this
     */
    public function setFontColor($fontColor);

    /**
     * Get goal font color
     *
     * @return string
     */
    public function getGoalFontColor();

    /**
     * Set goal font color
     *
     * @param string $goalFontColor
     * @return $this
     */
    public function setGoalFontColor($goalFontColor);

    /**
     * Get background color
     *
     * @return string
     */
    public function getBackgroundColor();

    /**
     * Set background color
     *
     * @param string $backgroundColor
     * @return $this
     */
    public function setBackgroundColor($backgroundColor);

    /**
     * Get text align
     *
     * @return string
     */
    public function getTextAlign();

    /**
     * Set text align
     *
     * @param string $textAlign
     * @return $this
     */
    public function setTextAlign($textAlign);

    /**
     * Get custom css
     *
     * @return string
     */
    public function getCustomCss();

    /**
     * Set custom css
     *
     * @param string $customCss
     * @return $this
     */
    public function setCustomCss($customCss);
}
