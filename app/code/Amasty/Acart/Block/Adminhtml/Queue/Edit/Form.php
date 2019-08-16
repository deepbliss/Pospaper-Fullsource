<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */

/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * description
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */

namespace Amasty\Acart\Block\Adminhtml\Queue\Edit;

use Magento\Backend\Block\Widget\Form as WidgetForm;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('edit_form');
        $this->setTitle(__('Queue Item Information'));
    }

    /**
     * @return WidgetForm
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getUrl('amasty_acart/queue/save'),
                    'method' => 'post',
                ],
            ]
        );

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
