<?php
namespace Pos\Manufacturer\Block\Adminhtml;
class Manufacturer extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
		
        $this->_controller = 'adminhtml_manufacturer';/*block grid.php directory*/
        $this->_blockGroup = 'Pos_Manufacturer';
        $this->_headerText = __('Manufacturer');
        $this->_addButtonLabel = __('Add New Entry'); 
        parent::_construct();
		
    }
}
