<?php

namespace MageModule\Core\Model\ResourceModel;

use Magento\UrlRewrite\Service\V1\Data\UrlRewrite as UrlRewriteService;

class UrlRewrite extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @var \Magento\Framework\Filter\FilterManager
     */
    private $filterManager;

    /**
     * UrlRewrite constructor.
     *
     * @param \Magento\Framework\Filter\FilterManager           $filterManager
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param null|string                                       $connectionName
     */
    public function __construct(
        \Magento\Framework\Filter\FilterManager $filterManager,
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->filterManager = $filterManager;
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('url_rewrite', 'url_rewrite_id');
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function formatUrlKey($value)
    {
        return $this->filterManager->translitUrl($value);
    }

    /**
     * Checks to see if the desired URL key is already in use by another object
     *
     * @param int|null    $objectId
     * @param int         $storeId
     * @param string      $desiredValue
     * @param string|null $suffix
     *
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function urlKeyExists($objectId, $storeId, $desiredValue, $suffix = null)
    {
        $value      = $this->formatUrlKey($desiredValue) . $suffix;
        $connection = $this->getConnection();
        $select     = $connection->select()->from(
            $this->getMainTable(),
            ['count' => new \Zend_Db_Expr('COUNT(*)')]
        );

        $select->where(UrlRewriteService::STORE_ID . ' = ?', $storeId);
        $select->where(UrlRewriteService::REQUEST_PATH . ' = ?', $value);

        if ($objectId) {
            $select->where(UrlRewriteService::ENTITY_ID . ' <> ?', $objectId);
        }

        return (bool)$connection->fetchOne($select);
    }

    /**
     * If desired URL key is already in use, appends a random 4-digit string to end of desired URL key
     *
     * @param int|null    $objectId
     * @param int         $storeId
     * @param string      $desiredValue
     * @param string|null $suffix
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getUniqueUrlKey($objectId, $storeId, $desiredValue, $suffix = null)
    {
        $value     = $this->formatUrlKey($desiredValue);
        $origValue = $value;

        $i = 1;
        while ($this->urlKeyExists($objectId, $storeId, $value, $suffix) && $i <= 100) {
            $value = $origValue . '-' . substr(uniqid(rand(), true), 0, 4);
            $i++;
        }

        return $value . $suffix;
    }
}
