<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Freeshippinglabel\Model;

use Aheadworks\Freeshippinglabel\Api\Data\LabelInterface;
use Aheadworks\Freeshippinglabel\Model\Label\Validator as LabelValidator;
use Aheadworks\Freeshippinglabel\Model\ResourceModel\Label as LabelResource;
use Aheadworks\Freeshippinglabel\Model\Source\ContentType;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Pricing\PriceCurrencyInterface;

/**
 * Label model
 */
class Label extends AbstractModel implements LabelInterface
{
    /**
     * @var LabelValidator
     */
    private $validator;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $checkoutSession;

    /**
     * @var \Magento\Store\Api\StoreResolverInterface
     */
    private $storeResolver;

    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @param LabelValidator $validator
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Store\Api\StoreResolverInterface $storeResolver
     * @param PriceCurrencyInterface $priceCurrency
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        LabelValidator $validator,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Store\Api\StoreResolverInterface $storeResolver,
        PriceCurrencyInterface $priceCurrency,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->validator = $validator;
        $this->checkoutSession = $checkoutSession;
        $this->storeResolver = $storeResolver;
        $this->priceCurrency = $priceCurrency;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Get final message - with processed variables
     *
     * @return string
     */
    public function getMessage()
    {
        $messageType = ContentType::EMPTY_CART;

        $goal = $this->priceCurrency->convertAndRound($this->getGoal());
        $leftToGoal = $goal;
        $quote = $this->checkoutSession->getQuote();
        if ($quote && $quote->getItemsCount()) {
            if ($goal > $quote->getGrandTotal()) {
                $messageType = ContentType::NOT_EMPTY_CART;
                $leftToGoal = $goal - $quote->getGrandTotal();
            } else {
                $messageType = ContentType::GOAL_REACHED;
                $leftToGoal = $goal - $quote->getGrandTotal();
            }
        }
        $storeId = $this->storeResolver->getCurrentStoreId();
        $messageTemplate = $this->getResource()
            ->getMessageTemplate($this->getId(), $messageType, $storeId);

        $variables = ['ruleGoal' => $goal, 'ruleGoalLeft' => $leftToGoal];
        return $this->processVars($messageTemplate, $variables);
    }

    /**
     * Process message variables
     *
     * @param string $messageTemplate
     * @param array $variables
     * @return string
     */
    private function processVars($messageTemplate, $variables)
    {
        $processedMessage = $messageTemplate;
        $currencySign = $this->priceCurrency->getCurrencySymbol();
        foreach ($variables as $varName => $value) {
            $processedMessage = str_replace(
                '{{' . $varName . '}}',
                '<span class="goal">' . $currencySign . $value . '</span>',
                $processedMessage
            );
        }

        return $processedMessage;
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(LabelResource::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function getIsEnabled()
    {
        return $this->getData(self::ENABLED);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsEnabled($isEnabled)
    {
        return $this->setData(self::ENABLED, $isEnabled);
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerGroupIds()
    {
        return $this->getData(self::CUSTOMER_GROUP_IDS) ?
            $this->getData(self::CUSTOMER_GROUP_IDS) :
            [];
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomerGroupIds($groupIds)
    {
        return $this->setData(self::CUSTOMER_GROUP_IDS, $groupIds);
    }

    /**
     * {@inheritdoc}
     */
    public function getGoal()
    {
        return $this->getData(self::GOAL);
    }

    /**
     * {@inheritdoc}
     */
    public function setGoal($goal)
    {
        return $this->setData(self::GOAL, $goal);
    }

    /**
     * {@inheritdoc}
     */
    public function getPageType()
    {
        return $this->getData(self::PAGE_TYPE);
    }

    /**
     * {@inheritdoc}
     */
    public function setPageType($pageType)
    {
        return $this->setData(self::PAGE_TYPE, $pageType);
    }

    /**
     * {@inheritdoc}
     */
    public function getPosition()
    {
        return $this->getData(self::POSITION);
    }

    /**
     * {@inheritdoc}
     */
    public function setPosition($position)
    {
        return $this->setData(self::POSITION, $position);
    }

    /**
     * {@inheritdoc}
     */
    public function getDelay()
    {
        return $this->getData(self::DELAY);
    }

    /**
     * {@inheritdoc}
     */
    public function setDelay($delay)
    {
        return $this->setData(self::DELAY, $delay);
    }

    /**
     * {@inheritdoc}
     */
    public function getContent()
    {
        return $this->getData(self::CONTENT);
    }

    /**
     * {@inheritdoc}
     */
    public function setContent($content)
    {
        return $this->setData(self::CONTENT, $content);
    }

    /**
     * {@inheritdoc}
     */
    public function getFontName()
    {
        return $this->getData(self::FONT_NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setFontName($fontName)
    {
        return $this->setData(self::FONT_NAME, $fontName);
    }

    /**
     * {@inheritdoc}
     */
    public function getFontSize()
    {
        return $this->getData(self::FONT_SIZE);
    }

    /**
     * {@inheritdoc}
     */
    public function setFontSize($fontSize)
    {
        return $this->setData(self::FONT_SIZE, $fontSize);
    }

    /**
     * {@inheritdoc}
     */
    public function getFontWeight()
    {
        return $this->getData(self::FONT_WEIGHT);
    }

    /**
     * {@inheritdoc}
     */
    public function setFontWeight($fontWeight)
    {
        return $this->setData(self::FONT_WEIGHT, $fontWeight);
    }

    /**
     * {@inheritdoc}
     */
    public function getFontColor()
    {
        return $this->getData(self::FONT_COLOR);
    }

    /**
     * {@inheritdoc}
     */
    public function setFontColor($fontColor)
    {
        return $this->setData(self::FONT_COLOR, $fontColor);
    }

    /**
     * {@inheritdoc}
     */
    public function getGoalFontColor()
    {
        return $this->getData(self::GOAL_FONT_COLOR);
    }

    /**
     * {@inheritdoc}
     */
    public function setGoalFontColor($goalFontColor)
    {
        return $this->setData(self::GOAL_FONT_COLOR, $goalFontColor);
    }

    /**
     * {@inheritdoc}
     */
    public function getBackgroundColor()
    {
        return $this->getData(self::BACKGROUND_COLOR);
    }

    /**
     * {@inheritdoc}
     */
    public function setBackgroundColor($backgroundColor)
    {
        return $this->setData(self::BACKGROUND_COLOR, $backgroundColor);
    }

    /**
     * {@inheritdoc}
     */
    public function getTextAlign()
    {
        return $this->getData(self::TEXT_ALIGN);
    }

    /**
     * {@inheritdoc}
     */
    public function setTextAlign($textAlign)
    {
        return $this->setData(self::TEXT_ALIGN, $textAlign);
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomCss()
    {
        return $this->getData(self::CUSTOM_CSS);
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomCss($customCss)
    {
        return $this->setData(self::CUSTOM_CSS, $customCss);
    }

    /**
     * {@inheritdoc}
     */
    protected function _getValidationRulesBeforeSave()
    {
        return $this->validator;
    }
}
