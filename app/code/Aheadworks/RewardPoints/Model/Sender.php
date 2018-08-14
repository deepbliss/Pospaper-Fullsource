<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model;

use Aheadworks\RewardPoints\Model\Config;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\App\Area;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Aheadworks\RewardPoints\Model\KeyEncryptor;
use Aheadworks\RewardPoints\Model\Source\NotifiedStatus;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\UrlInterface;

/**
 * Class Sender
 *
 * @package Aheadworks\RewardPoints\Model
 */
class Sender
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var StoreRepositoryInterface
     */
    private $storeRepository;

    /**
     * @var KeyEncryptor
     */
    private $keyEncryptor;

    /**
     * @var TimezoneInterface
     */
    private $localeDate;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @param Config $config
     * @param TransportBuilder $transportBuilder
     * @param StoreRepositoryInterface $storeRepository
     * @param KeyEncryptor $keyEncryptor
     * @param TimezoneInterface $localeDate
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        Config $config,
        TransportBuilder $transportBuilder,
        StoreRepositoryInterface $storeRepository,
        KeyEncryptor $keyEncryptor,
        TimezoneInterface $localeDate,
        UrlInterface $urlBuilder
    ) {
        $this->config = $config;
        $this->transportBuilder = $transportBuilder;
        $this->storeRepository = $storeRepository;
        $this->keyEncryptor = $keyEncryptor;
        $this->localeDate = $localeDate;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Send email notification to recipient
     *
     * @param CustomerInterface $customer
     * @param string $comment
     * @param int $points
     * @param int $pointsBalance
     * @param string $moneyBalance
     * @param string $expireDate
     * @param int $storeId
     * @param string $template
     * @return int
     * @throws \Magento\Framework\Exception\MailException
     */
    public function sendNotification(
        $customer,
        $comment,
        $points,
        $pointsBalance,
        $moneyBalance,
        $expireDate,
        $storeId,
        $template
    ) {
        /** @var \Magento\Store\Api\Data\StoreInterface $store */
        $store = $this->storeRepository->getById($storeId);
        $sender = $this->config->getEmailSender($store->getWebsiteId());
        $senderName = $this->config->getEmailSenderName($store->getWebsiteId());
        $customerName = $customer->getFirstname() . ' ' . $customer->getLastname();

        $notifiedStatus = $this->send(
            $template,
            [
                'area' => Area::AREA_FRONTEND,
                'store' => $store->getId()
            ],
            $this->prepareTemplateVars(
                [
                    'store' => $store,
                    'customer' => $customer,
                    'sender_name' => $senderName,
                    'customer_name' => $customerName,
                    'comment' => $comment,
                    'points' => $points,
                    'expire_date' => $expireDate,
                    'points_balance' => $pointsBalance,
                    'money_balance' => $moneyBalance
                ]
            ),
            $sender,
            [$customerName => $customer->getEmail()]
        );
        return $notifiedStatus;
    }

    /**
     * Send email
     *
     * @param string $templateId
     * @param array $templateOptions
     * @param array $templateVars
     * @param string $from
     * @param array $to
     * @return int
     */
    private function send($templateId, array $templateOptions, array $templateVars, $from, array $to)
    {
        try {
            $this->transportBuilder
                ->setTemplateIdentifier($templateId)
                ->setTemplateOptions($templateOptions)
                ->setTemplateVars($templateVars)
                ->setFrom($from)
                ->addTo($to);
            $this->transportBuilder->getTransport()->sendMessage();
        } catch (\Exception $e) {
            return NotifiedStatus::NO;
        }

        return NotifiedStatus::YES;
    }

    /**
     * Prepare template vars
     *
     * @param array $data
     * @return array
     */
    private function prepareTemplateVars($data)
    {
        /** @var $store \Magento\Store\Model\Store */
        $store = $data['store'];
        $customer = $data['customer'];
        $unsubscribeKey = $this->keyEncryptor->encrypt(
            $customer->getEmail(),
            $customer->getId(),
            $store->getWebsiteId()
        );
        $templateVars = [
            'rp_program_url' => $store->getBaseUrl() . $this->config->getFrontendExplainerPage($store->getWebsiteId()),
            'unsubscribe_url' => $store->getBaseUrl() . 'aw_rewardpoints/unsubscribe/index/key/' . $unsubscribeKey,
            'store_name' => $store->getFrontendName(),
            'store_url' => $store->getBaseUrl()
        ];

        if (isset($data['expire_date'])) {
            $now = new \DateTime('now', new \DateTimeZone('UTC'));
            $expireDate = new \DateTime($data['expire_date'], new \DateTimeZone('UTC'));
            $expireInDays = $now->diff($expireDate);

            $templateVars['expire_date'] = $this->localeDate
                ->scopeDate($store, $data['expire_date'], true)
                ->format('d M Y');

            if (($expireInDays = $expireInDays->format('%a')) > 0) {
                $templateVars['expire_in_days'] = $expireInDays;
            }
        }

        if (isset($data['sender_name'])) {
            $templateVars['sender_name'] = $data['sender_name'];
        }
        if (isset($data['customer_name'])) {
            $templateVars['customer_name'] = $data['customer_name'];
        }
        if (isset($data['comment'])) {
            $templateVars['comment'] = $data['comment'];
        }
        if (isset($data['points'])) {
            $templateVars['points'] = $data['points'];
        }
        if (isset($data['points_balance'])) {
            $templateVars['points_balance'] = $data['points_balance'];
        }
        if (isset($data['money_balance'])) {
            $templateVars['money_balance'] = $data['money_balance'];
        }

        return $templateVars;
    }
}
