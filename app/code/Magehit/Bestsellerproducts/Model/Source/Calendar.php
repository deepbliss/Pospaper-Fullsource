<?php
 
namespace Magehit\Bestsellerproducts\Model\Source;
 
use Magento\Framework\Registry;
 
class Calendar extends \Magento\Config\Block\System\Config\Form\Field {
 
    /**
     * @var  Registry
     */
    protected $_coreRegistry;
 
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
    \Magento\Backend\Block\Template\Context $context, Registry $coreRegistry, array $data = []
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
    }
 
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element) {
        $baseURL = $this->getBaseUrl();
        $html = $element->getElementHtml();
        $calpath = $baseURL . 'pub/media/systemcalendar/';
        if (!$this->_coreRegistry->registry('datepicker_loaded')) {
            $html .= '<style type="text/css">input.datepicker { background-image: url(' . $calpath . 'calendar.png) !important; background-position: calc(100% - 8px) center; background-repeat: no-repeat; } input.datepicker.disabled,input.datepicker[disabled] { pointer-events: none; }</style>';
            $this->_coreRegistry->registry('datepicker_loaded', 1);
        }
        $html .= '<script type="text/javascript">
            require(["jquery", "jquery/ui"], function () {
                jQuery(document).ready(function () {
                    jQuery("#' . $element->getHtmlId() . '").datepicker( { dateFormat: "yy-mm-dd" } );
                        
                    var el = document.getElementById("' . $element->getHtmlId() . '");
                    el.className = el.className + " datepicker";
                });
            });
            </script>';
        return $html;
    }
 
}