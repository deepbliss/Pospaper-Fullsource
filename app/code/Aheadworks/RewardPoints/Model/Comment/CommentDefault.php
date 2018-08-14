<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\Comment;

use Magento\Framework\UrlInterface;
use Aheadworks\RewardPoints\Model\Source\Transaction\EntityType as TransactionEntityType;
use Magento\Framework\Phrase\Renderer\Placeholder;

/**
 * Class Aheadworks\RewardPoints\Model\Comment\CommentDefault
 */
class CommentDefault implements CommentInterface
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $label;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var Placeholder
     */
    private $placeholder;

    /**
     * @param UrlInterface $urlBuilder
     * @param Placeholder $placeholder
     * @param int|null $type
     * @param string|array|null $label
     */
    public function __construct(
        UrlInterface $urlBuilder,
        Placeholder $placeholder,
        $type = null,
        $label = null
    ) {
        $this->type = $type;
        $this->label = $label;
        $this->urlBuilder = $urlBuilder;
        $this->placeholder = $placeholder;
    }

    /**
     *  {@inheritDoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     *  {@inheritDoc}
     */
    public function getLabel($key = null, $arguments = [])
    {
        $label = $this->label;
        if (is_array($this->label)) {
            $label = ($key && isset($this->label[$key]))
                ? $this->label[$key]
                : $label = $this->label['default'];
        }
        return __($label, $arguments);
    }

    /**
     * {@inheritDoc}
     */
    public function renderComment($arguments = [], $key = null, $label = null, $renderingUrl = false, $frontend = false)
    {
        $labelArguments = [];
        foreach ($arguments as $entityType => $entity) {
            switch ($entityType) {
                case TransactionEntityType::ORDER_ID:
                    $labelArguments['order_id'] = '#' . $entity['entity_label'];
                    if ($renderingUrl) {
                        $labelArguments['order_url'] = $this->getOrderUrl($entity['entity_id']);
                        $label = str_replace(
                            '%order_id',
                            '<a href="%order_url">%order_id</a>',
                            $label
                        );
                    }
                    break;
                case TransactionEntityType::CREDIT_MEMO_ID:
                    $labelArguments['creditmemo_id'] = '#' . $entity['entity_label'];
                    if ($renderingUrl) {
                        $labelArguments['creditmemo_url'] = $this->getCreditMemoUrl(
                            $entity['entity_id'],
                            $arguments[TransactionEntityType::ORDER_ID]['entity_id'],
                            $frontend
                        );
                        $label = str_replace(
                            '%creditmemo_id',
                            '<a href="%creditmemo_url">%creditmemo_id</a>',
                            $label
                        );
                    }
                    break;
                case TransactionEntityType::TRANSACTION_ID:
                    $labelArguments['transaction_id'] = $entity['entity_id'];
                    break;
            }
        }

        return $renderingUrl
            ? $this->placeholder->render([$label], $labelArguments)
            : $this->getLabel($key, $labelArguments);
    }

    /**
     * Retrieve order url
     *
     * @param int $orderId
     * @return string
     */
    private function getOrderUrl($orderId)
    {
        return $this->urlBuilder->getUrl('sales/order/view', ['order_id' => $orderId]);
    }

    /**
     * Retrieve credit memo url
     *
     * @param int $creditMemoId
     * @param int $orderId
     * @param bool $frontend
     * @return string
     */
    private function getCreditMemoUrl($creditMemoId, $orderId, $frontend = false)
    {
        if ($frontend) {
            $url = $this->urlBuilder->getUrl('sales/order/creditmemo', ['order_id' => $orderId]);
        } else {
            $url = $this->urlBuilder->getUrl('sales/order_creditmemo/view', ['creditmemo_id' => $creditMemoId]);
        }
        return $url;
    }

    /**
     * {@inheritDoc}
     */
    public function renderTranslatedComment(
        $arguments = [],
        $key = null,
        $label = null,
        $renderingUrl = false,
        $frontend = false
    ) {
        return $this->renderComment($arguments, $key, __($label), $renderingUrl, $frontend);
    }
}
