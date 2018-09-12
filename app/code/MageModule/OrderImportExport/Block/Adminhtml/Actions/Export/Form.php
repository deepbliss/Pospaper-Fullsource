<?php
/**
 * Copyright (c) 2018 MageModule, LLC: All rights reserved
 *
 * LICENSE: This source file is subject to our standard End User License 
 * Agreeement (EULA) that is available through the world-wide-web at the 
 * following URI: http://www.magemodule.com/magento2-ext-license.html.  
 *
 * If you did not receive a copy of the EULA and are unable to obtain it through 
 * the web, please send a note to admin@magemodule.com so that we can mail 
 * you a copy immediately.
 *
 * @author       MageModule, LLC admin@magemodule.com 
 * @copyright   2018 MageModule, LLC
 * @license       http://www.magemodule.com/magento2-ext-license.html
 *
 */

namespace MageModule\OrderImportExport\Block\Adminhtml\Actions\Export;

use MageModule\OrderImportExport\Api\Data\ExportConfigInterface;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \MageModule\OrderImportExport\Api\Data\ExportConfigInterface
     */
    private $config;

    /**
     * @var \Magento\Config\Model\Config\Source\Yesno
     */
    private $boolean;

    /**
     * Form constructor.
     *
     * @param \MageModule\OrderImportExport\Api\Data\ExportConfigInterface $config
     * @param \Magento\Config\Model\Config\Source\Yesno                    $boolean
     * @param \Magento\Backend\Block\Template\Context                      $context
     * @param \Magento\Framework\Registry                                  $registry
     * @param \Magento\Framework\Data\FormFactory                          $formFactory
     * @param array                                                        $data
     */
    public function __construct(
        \MageModule\OrderImportExport\Api\Data\ExportConfigInterface $config,
        \Magento\Config\Model\Config\Source\Yesno $boolean,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        array $data = []
    ) {
        $this->config  = $config;
        $this->boolean = $boolean;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            [
                'data' =>
                    [
                        'id'      => 'export_form',
                        'action'  => $this->getUrl('*/*/export'),
                        'method'  => 'post',
                        'enctype' => 'multipart/form-data'
                    ]
            ]
        );

        $fieldset = $form->addFieldset(
            'export_fieldset',
            [
                'legend' => __('Export')
            ]
        );

        $dateFormat = $this->_localeDate->getDateFormat(
            \IntlDateFormatter::GREGORIAN
        );

        $fieldset->addField(
            ExportConfigInterface::FROM,
            'date',
            [
                'name'        => ExportConfigInterface::FROM,
                'label'       => __('From'),
                'title'       => __('From'),
                'required'    => false,
                'date_format' => $dateFormat
            ]
        );

        $fieldset->addField(
            ExportConfigInterface::TO,
            'date',
            [
                'name'        => ExportConfigInterface::TO,
                'label'       => __('To'),
                'title'       => __('To'),
                'required'    => false,
                'date_format' => $dateFormat
            ]
        );

        $fieldset->addField(
            ExportConfigInterface::DELIMITER,
            'text',
            [
                'name'     => ExportConfigInterface::DELIMITER,
                'label'    => __('Field Delimiter'),
                'title'    => __('Field Delimiter'),
                'required' => true,
                'style'    => 'width: 80px;'
            ]
        );

        $fieldset->addField(
            ExportConfigInterface::ENCLOSURE,
            'text',
            [
                'name'     => ExportConfigInterface::ENCLOSURE,
                'label'    => __('Field Enclosure'),
                'title'    => __('Field Enclosure'),
                'required' => true,
                'style'    => 'width: 80px;'
            ]
        );

        $fieldset->addField(
            ExportConfigInterface::DIRECTORY,
            'hidden',
            ['name' => ExportConfigInterface::DIRECTORY]
        );

        $fieldset->addField(
            ExportConfigInterface::FILENAME,
            'hidden',
            ['name' => ExportConfigInterface::FILENAME]
        );

        $button = $this->getLayout()->createBlock(
            \Magento\Backend\Block\Widget\Button::class
        )->setData(
            [
                'label'          => __('Export Orders'),
                'class'          => 'save primary',
                'data_attribute' => [
                    'mage-init' => ['button' => ['event' => 'save', 'target' => '#export_form']]
                ]]
        );

        $fieldset->addField('submit', 'note', ['text' => $button->toHtml()]);

        /** makes sure that all elements have a unique html id since we have 2 forms on the page */
        foreach ($fieldset->getElements() as $element) {
            $element->setHtmlId('export_' . $element->getHtmlId());
        }

        $form->addFieldNameSuffix('export');
        $form->setValues($this->config->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getFormHtml()
    {
        $html  = parent::getFormHtml();
        $block = $this->getLayout()->createBlock(\Magento\Backend\Block\Template::class);
        $block->setTemplate('MageModule_OrderImportExport::actions/form/js.phtml');
        $block->setFormId('export_form');
        $html .= $block->toHtml();

        return $html;
    }
}
