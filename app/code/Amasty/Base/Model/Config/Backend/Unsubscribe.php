<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Model\Config\Backend;

use Amasty\Base\Model\Source\NotificationType;

class Unsubscribe extends \Magento\Framework\App\Config\Value implements
    \Magento\Framework\App\Config\Data\ProcessorInterface
{
    const PATH_TO_FEED_IMAGES = 'https://notification.amasty.com/';

    /**
     * @var \Amasty\Base\Model\AdminNotification\Messages
     */
    private $messageManager;

    /**
     * @var NotificationType
     */
    private $notificationType;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Amasty\Base\Model\AdminNotification\Messages $messageManager,
        NotificationType $notificationType,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
        $this->messageManager = $messageManager;
        $this->notificationType = $notificationType;
    }

    public function afterSave()
    {
        if ($this->isValueChanged()) {
            $value = explode(',', $this->getValue());
            if (in_array(NotificationType::UNSUBSCRIBE_ALL, $value)) {
                $changes = [NotificationType::UNSUBSCRIBE_ALL];
            } else {
                $oldValue = explode(',', $this->getOldValue());
                $changes = array_diff($oldValue, $value);
                $changes = array_diff($changes, [NotificationType::UNSUBSCRIBE_ALL]);
            }

            if (!empty($changes)) {
                foreach ($changes as $change) {
                    $message = $this->generateMessage($change);
                    $this->messageManager->addMessage($message);
                }
            } else {
                $this->messageManager->clear();
            }
        }

        return parent::afterSave();
    }

    /**
     * Process config value
     *
     * @param string $value
     * @return string
     */
    public function processValue($value)
    {
        return $value;
    }

    private function generateMessage($change)
    {
        $message = '';
        $titles = $this->notificationType->toOptionArray();
        foreach ($titles as $title) {
            if ($title['value'] == $change) {
                if ($change == NotificationType::UNSUBSCRIBE_ALL) {
                    $label = __('All Notifications');
                } else {
                    $label = $title['label'];
                }

                $message = '<img src="' . $this->generateLink($change) .'"/><span>'
                    . __('You have successfully unsubscribed from %1.', $label) .'</span>';
                break;
            }
        }

        return $message;
    }

    private function generateLink($change)
    {
        $change = mb_strtolower($change);
        return self::PATH_TO_FEED_IMAGES . $change . '.svg';
    }
}
