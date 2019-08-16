<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Model;

/**
 * Class GetCustomerIp
 * @package Amasty\Base\Model
 */
class GetCustomerIp
{
    /**
     * Local IP address
     */
    const LOCAL_IP = '127.0.0.1';

    protected $addressPath = [
        'HTTP_X_REAL_IP',
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR'
    ];

    /**
     * @var \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress
     */
    private $remoteAddress;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    /**
     * GetCustomerIp constructor.
     *
     * @param \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress  $remoteAddress
     * @param \Magento\Framework\App\RequestInterface               $request
     */
    public function __construct(
        \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $remoteAddress,
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->remoteAddress = $remoteAddress;
        $this->request = $request;
    }

    /**
     * @return string
     */
    public function getCurrentIp()
    {
        foreach ($this->addressPath as $path) {
            $ip = $this->request->getServer($path);
            if ($ip) {
                if (strpos($ip, ',') !== false) {
                    $addresses = explode(',', $ip);
                    foreach ($addresses as $address) {
                        if (trim($address) != self::LOCAL_IP) {
                            return trim($address);
                        }
                    }
                } else {
                    if ($ip != self::LOCAL_IP) {
                        return $ip;
                    }
                }
            }
        }

        return $this->remoteAddress->getRemoteAddress();
    }
}
