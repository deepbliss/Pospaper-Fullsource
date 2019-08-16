<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Geoip
 */


namespace Amasty\Geoip\Model;

use Magento\Framework\DB\Select;
use Magento\Framework\Model\AbstractModel;
use Amasty\Geoip\Helper\Data as Helper;
use Magento\Framework\App\ResourceConnection;

/**
 * Class Geolocation
 */
class Geolocation extends AbstractModel
{
    /**
     * @var Helper
     */
    public $geoipHelper;

    /**
     * @var ResourceConnection
     */
    protected $resource;

    /**
     * Geolocation constructor.
     *
     * @param Helper $geoipHelper
     * @param ResourceConnection $resource
     */
    public function __construct(
        Helper $geoipHelper,
        ResourceConnection $resource
    ) {
        $this->geoipHelper = $geoipHelper;
        $this->resource = $resource;
    }

    /**
     * load location data by IP
     *
     * @param $ip
     *
     * @return $this
     *
     * @throws \Exception
     */
    public function locate($ip)
    {
        //        $ip = '213.184.226.82';//Minsk
        $isIpv6 = (bool) filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);

        if ($this->geoipHelper->isDone(false)) {
            if ($isIpv6) {
                $ip = substr($ip, 0, strrpos($ip, ":")) . ':0'; // Mask IP according to EU GDPR law
                $longIP = $this->geoipHelper->getLongIpV6($ip);
                $blockTable = 'amasty_geoip_block_v6';
            } else {
                $ip = substr($ip, 0, strrpos($ip, ".")) . '.0'; // Mask IP according to EU GDPR law
                $longIP = sprintf("%u", ip2long($ip));
                $blockTable = 'amasty_geoip_block';
            }

            if (!empty($longIP)) {
                $connection =  $this->resource->getConnection('read');
                $blockSelect = $connection->select()
                    ->from($this->resource->getTableName($blockTable))
                    ->reset(Select::COLUMNS)
                    ->columns(['geoip_loc_id', 'latitude', 'longitude'])
                    ->where('start_ip_num <= ?', $longIP)
                    ->order('start_ip_num DESC')
                    ->limit(1);

                $select = $connection->select()
                    ->from(['b' => $blockSelect])
                    ->joinInner(
                        ['l' => $this->resource->getTableName('amasty_geoip_location')],
                        'l.geoip_loc_id = b.geoip_loc_id',
                        null
                    )
                    ->reset(Select::COLUMNS)
                    ->columns(['l.*', 'b.latitude', 'b.longitude']);

                if ($result = $connection->fetchRow($select)) {
                    $this->setData($result);
                }
            }
        }

        return $this;
    }
}
