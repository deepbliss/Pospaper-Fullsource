<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Model\Source;

use Magento\Framework\App\Config\ScopeConfigInterface;
use \Magento\Store\Model\ScopeInterface;

class Emails implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Assoc array of configuration variables
     *
     * @var array
     */
    private $configVariables = [];

    /**
     * @var ScopeConfigInterface
     */
    private $config;

    public function __construct(ScopeConfigInterface $config)
    {
        $this->configVariables = [
            ['value' => 'trans_email/ident_general/email', 'label' => __('General Contact')],
            ['value' => 'trans_email/ident_sales/email', 'label' => __('Sales Representative Contact')],
            ['value' => 'trans_email/ident_support/email', 'label' => __('Customer Support Contact')],
            ['value' => 'trans_email/ident_custom1/email', 'label' => __('Custom Email 1 Contact')],
            ['value' => 'trans_email/ident_custom2/email', 'label' => __('Custom Email 2 Contact')],
        ];
        $this->config = $config;
    }

    /**
     * Retrieve option array of store contact variables
     *
     * @return array
     */
    public function toOptionArray()
    {
        $optionArray = [];

        foreach ($this->getData() as $variable) {
            $email = $this->config->getValue($variable['value'], ScopeInterface::SCOPE_STORE);
            $optionArray[] = [
                'value' => $email,
                'label' => $variable['label'],
            ];
        }

        return $optionArray;
    }

    /**
     * Return available config variables
     *
     * @return array
     * @codeCoverageIgnore
     */
    public function getData()
    {
        return $this->configVariables;
    }
}
