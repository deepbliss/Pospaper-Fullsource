<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Plugin\Order;

use \Amasty\Checkout\Helper\CheckoutData;

class Delivery
{

    /**
     * @var \Amasty\Checkout\Helper\Onepage
     */
    protected $onepageHelper;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Amasty\Checkout\Helper\CheckoutData
     */
    protected $checkoutDataHelper;

    /**
     * Delivery constructor.
     * @param \Amasty\Checkout\Helper\Onepage $onepageHelper
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Framework\Registry $registry
     * @param \Amasty\Checkout\Helper\CheckoutData $registry
     * @internal param $ $
     */
    public function __construct(
        \Amasty\Checkout\Helper\Onepage $onepageHelper,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Registry $registry,
        CheckoutData $checkoutDataHelper
    )
    {
        $this->onepageHelper = $onepageHelper;
        $this->request = $request;
        $this->registry = $registry;
        $this->checkoutDataHelper = $checkoutDataHelper;
    }

    /**
     * @param \Magento\Sales\Block\Items\AbstractItems $subject
     * @param $result
     * @return string
     */
    public function afterToHtml(
        \Magento\Sales\Block\Items\AbstractItems $subject, $result
    ) {
        foreach ($subject->getLayout()->getUpdate()->getHandles() as $handle) {
            if (substr($handle, 0, 12) === 'sales_email_') {
                if ($subject->getOrder() && $subject->getOrder()->getId()) {
                    $deliveryBlock = $subject->getLayout()
                        ->createBlock(
                            'Amasty\Checkout\Block\Sales\Order\Email\Delivery',
                            'amcheckout.delivery',
                            [
                                'data' => [
                                    'order_id' => $subject->getOrder()->getId()
                                ]
                            ]
                        );

                    $result = $deliveryBlock->toHtml() . $result;
                }

                $additionalData = $this->onepageHelper->getAdditionalOptions();
                if (array_key_exists('comment', $additionalData)
                    && $additionalData['comment']
                    && $subject->getOrder()
                ) {
                    $comment = $this->registry
                        ->registry(CheckoutData::COMMENT_REGISTRY_KEY_NAME);
                    $this->checkoutDataHelper->addComment($comment, $subject->getOrder());
                    $commentsBlock = $subject->getLayout()
                        ->createBlock(
                            'Amasty\Checkout\Block\Sales\Order\Email\Comments',
                            'amcheckout.comments',
                            [
                                'data' => [
                                    'order' => $subject->getOrder()
                                ]
                            ]
                        );

                    if (!$this->checkoutDataHelper->isCommentSet()) {
                        $this->registry->register(CheckoutData::COMMENT_REGISTRY_KEY_NAME . '_seted', true);
                    }

                    $result = $commentsBlock->toHtml() . $result;
                }
            }
        }

        return $result;
    }
}
