<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Geoip
 */


namespace Amasty\Geoip\Model;

class Geolocation extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @var \Amasty\Geoip\Helper\Data
     */
    public $geoipHelper;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resource;

    /**
     * Geolocation constructor.
     *
     * @param \Amasty\Geoip\Helper\Data                 $geoipHelper
     * @param \Magento\Framework\App\ResourceConnection $resource
     */
    public function __construct(
        \Amasty\Geoip\Helper\Data $geoipHelper,
        \Magento\Framework\App\ResourceConnection $resource
    ) {
        $this->geoipHelper = $geoipHelper;
        $this->resource = $resource;
    }

    /**
     * load location data by IP
     *
     * @param string $ip
     *
     * @return $this
     */
    public function locate($ip)
    {
//        $ip = '213.184.226.82';//Minsk
        $ip = substr($ip, 0, strrpos($ip, ".")) . '.0'; // Mask IP according to EU GDPR law

        if ($this->geoipHelper->isDone(false)) {
            $longIP = sprintf("%u", ip2long($ip));

            if (!empty($longIP)) {
                $connection =  $this->resource->getConnection('read');
                $blockSelect = $connection->select()
                    ->from($this->resource->getTableName('amasty_geoip_block'))
                    ->reset(\Magento\Framework\DB\Select::COLUMNS)
                    ->columns(['geoip_loc_id'])
                    ->where('start_ip_num < ?', $longIP)
                    ->order('start_ip_num DESC')
                    ->limit(1);

                $select = $connection->select()
                    ->from(['b' => $blockSelect])
                    ->joinInner(
                        ['l' => $this->resource->getTableName('amasty_geoip_location')],
                        'l.geoip_loc_id = b.geoip_loc_id',
                        null
                    )
                    ->reset(\Magento\Framework\DB\Select::COLUMNS)
                    ->columns(['l.*']);

                if ($result = $connection->fetchRow($select)) {
                    $this->setData($result);
                }
            }
        }

        return $this;
    }
}
