<?php

namespace Pos\CreditApp\Block\Application;

class Index extends \Magento\Framework\View\Element\Template
{

    protected $date;

    /**
     * Constructor
     *
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        $this->date = $date;
        parent::__construct($context, $data);
    }

    public function getDate()
    {
        return $this->date->gmtDate('m/d/Y');
    }
}
