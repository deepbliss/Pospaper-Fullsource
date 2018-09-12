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

namespace MageModule\OrderImportExport\Block\Adminhtml\Actions\Import;

use MageModule\OrderImportExport\Api\Data\ImportConfigInterface;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \MageModule\OrderImportExport\Api\Data\ImportConfigInterface
     */
    private $config;

    /**
     * @var \Magento\Config\Model\Config\Source\Yesno
     */
    private $boolean;

    /**
     * Form constructor.
     *
     * @param \MageModule\OrderImportExport\Api\Data\ImportConfigInterface $config
     * @param \Magento\Config\Model\Config\Source\Yesno                    $boolean
     * @param \Magento\Backend\Block\Template\Context                      $context
     * @param \Magento\Framework\Registry                                  $registry
     * @param \Magento\Framework\Data\FormFactory                          $formFactory
     * @param array                                                        $data
     */
    public function __construct(
        \MageModule\OrderImportExport\Api\Data\ImportConfigInterface $config,
        \Magento\Config\Model\Config\Source\Yesno $boolean,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        array $data = []
    ) {
        $this->config            = $config;
        $this->boolean           = $boolean;
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
                        'id'      => 'import_form',
                        'action'  => $this->getUrl('*/*/import'),
                        'method'  => 'post',
                        'enctype' => 'multipart/form-data'
                    ]
            ]
        );

        $fieldset = $form->addFieldset(
            'import_fieldset',
            [
                'legend' => __('Import')
            ]
        );

        $fieldset->addField(
            ImportConfigInterface::DELIMITER,
            'text',
            [
                'name'     => ImportConfigInterface::DELIMITER,
                'label'    => __('Field Delimiter'),
                'title'    => __('Field Delimiter'),
                'required' => true,
                'style'    => 'width: 80px;'
            ]
        );

        $fieldset->addField(
            ImportConfigInterface::ENCLOSURE,
            'text',
            [
                'name'     => ImportConfigInterface::ENCLOSURE,
                'label'    => __('Field Enclosure'),
                'title'    => __('Field Enclosure'),
                'required' => true,
                'style'    => 'width: 80px;'
            ]
        );

        $fieldset->addField(
            ImportConfigInterface::IMPORT_ORDER_NUMBER,
            'select',
            [
                'name'     => ImportConfigInterface::IMPORT_ORDER_NUMBER,
                'label'    => __('Import Order Numbers'),
                'title'    => __('Import Order Numbers'),
                'required' => true,
                'values'   => $this->boolean->toArray(),
                'note'     => __('If the order number already exists in the system, a new order number will be assigned.')
            ]
        );

        $invoice = $fieldset->addField(
            ImportConfigInterface::CREATE_INVOICE,
            'select',
            [
                'name'     => ImportConfigInterface::CREATE_INVOICE,
                'label'    => __('Create Invoices'),
                'title'    => __('Create Invoices'),
                'required' => true,
                'values'   => $this->boolean->toArray()
            ]
        );

        $creditmemo = $fieldset->addField(
            ImportConfigInterface::CREATE_CREDIT_MEMO,
            'select',
            [
                'name'     => ImportConfigInterface::CREATE_CREDIT_MEMO,
                'label'    => __('Create Credit Memos'),
                'title'    => __('Create Credit Memos'),
                'required' => true,
                'values'   => $this->boolean->toArray()
            ]
        );

        $fieldset->addField(
            ImportConfigInterface::CREATE_SHIPMENT,
            'select',
            [
                'name'     => ImportConfigInterface::CREATE_SHIPMENT,
                'label'    => __('Create Shipments'),
                'title'    => __('Create Shipments'),
                'required' => true,
                'values'   => $this->boolean->toArray()
            ]
        );

        $fieldset->addField(
            ImportConfigInterface::ERROR_LIMIT,
            'text',
            [
                'name'     => ImportConfigInterface::ERROR_LIMIT,
                'label'    => __('Error Limit'),
                'title'    => __('Error Limit'),
                'required' => true,
                'style'    => 'width: 80px;',
                'note'     => __('Import will be stopped if this limit is reached.'),
                'class'    => 'validate-greater-than-zero validate-number'
            ]
        );

        $fieldset->addField(
            'file',
            'file',
            [
                'name'     => 'file',
                'label'    => __('Import File'),
                'title'    => __('Import File'),
                'required' => true
            ]
        );

        $button = $this->getLayout()->createBlock(
            \Magento\Backend\Block\Widget\Button::class
        )->setData(
            [
                'label'          => __('Import Orders'),
                'class'          => 'save primary',
                'data_attribute' => [
                    'mage-init' => ['button' => ['event' => 'save', 'target' => '#import_form']]
                ]]
        );

        $fieldset->addField('submit', 'note', ['text' => $button->toHtml()]);

        $fieldset->addField(
            'date_note',
            'note',
            [
                'label'    => __('Notice'),
                'required' => false,
                'text'     => __(
                    'Fields containing dates should be formatted as <strong>%1</strong><br />
                     Fields containing date/times should be formatted as <strong>%2</strong> or <strong>%3</strong><br />
                     Please be aware that when opening a file in Microsoft Excel and then re-saving, Excel may have changed your date format.',
                    date('Y-m-d'),
                    date('Y-m-d H:i:s'),
                    date('Y-m-d H:i')
                )
            ]
        );

        /** makes sure that all elements have a unique html id since we have 2 forms on the page */
        foreach ($fieldset->getElements() as $element) {
            $element->setHtmlId('import_' . $element->getHtmlId());
        }

        $form->addFieldNameSuffix('import');
        $form->setValues($this->config->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        /** @var \Magento\Backend\Block\Widget\Form\Element\Dependence $dependence */
        $dependence = $this->getLayout()->createBlock(\Magento\Backend\Block\Widget\Form\Element\Dependence::class);
        $dependence->addFieldMap($invoice->getHtmlId(), $invoice->getName());
        $dependence->addFieldMap($creditmemo->getHtmlId(), $creditmemo->getName());
        $dependence->addFieldDependence($creditmemo->getName(), $invoice->getName(), '1');

        $this->setChild('form_after', $dependence);

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
        $block->setFormId('import_form');
        $html .= $block->toHtml();

        return $html;
    }
}
