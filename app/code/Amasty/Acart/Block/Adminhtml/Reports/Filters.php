<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Block\Adminhtml\Reports;

use Amasty\Acart\Model\Config\Source\DataRange;
use Amasty\Acart\Model\Date;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Framework\Convert\DataObject;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;

class Filters extends Generic
{
    /**#@+*/
    const ALL = 'all';
    const WEBSITE = 'website';
    const DATE_RANGE = 'date_range';
    const DATE_FROM = 'date_from';
    const DATE_TO = 'date_to';
    const SUBMIT = 'submit';
    /**#@-*/

    /**
     * @var DataObject
     */
    private $objectConverter;

    /**
     * @var Date
     */
    private $date;

    /**
     * @var DataRange
     */
    private $dataRange;

    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        DataObject $objectConverter,
        Date $date,
        DataRange $dataRange,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);

        $this->objectConverter = $objectConverter;
        $this->date = $date;
        $this->dataRange = $dataRange;
    }

    /**
     * @return \Magento\Backend\Block\Widget\Form\Generic
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('acart_reports_');
        $form->addField(
            self::WEBSITE,
            'select',
            [
                'label' => __('Website:'),
                'title' => __('Website:'),
                'name' => self::WEBSITE,
                'class' => 'amacart-reports-field',
                'values' => $this->getWebsitesArray()
            ]
        );

        $form->addField(
            self::DATE_RANGE,
            'select',
            [
                'label' => __('Date Range:'),
                'title' => __('Date Range:'),
                'name' => self::DATE_RANGE,
                'class' => 'amacart-reports-field',
                'value' => DataRange::LAST_DAY,
                'values' => $this->dataRange->toOptionArray()
            ]
        )->addCustomAttribute('data-amacart-js','data-select');

        $form->addField(
            self::DATE_FROM,
            'date',
            [
                'label' => __('From:'),
                'title' => __('From:'),
                'name' => self::DATE_FROM,
                'required' => true,
                'readonly' => true,
                'class' => 'amacart-reports-field',
                'date_format' => 'M/d/Y',
                'value' => $this->date->getDateWithOffsetByDays(-5),
                'max_date' => $this->date->getDateWithOffsetByDays(0)
            ]
        )->addCustomAttribute('data-amacart-js','data-range');

        $form->addField(
            self::DATE_TO,
            'date',
            [
                'label' => __('To:'),
                'title' => __('To:'),
                'name' => self::DATE_TO,
                'required' => true,
                'readonly' => true,
                'class' => 'amacart-reports-field',
                'date_format' => 'M/d/Y',
                'value' => $this->date->getDateWithOffsetByDays(0),
                'max_date' => $this->date->getDateWithOffsetByDays(0),
            ]
        )->addCustomAttribute('data-amacart-js','data-range');

        $form->addField(
            self::SUBMIT,
            'button',
            [
                'value' => __('Refresh'),
                'title' => __('Refresh'),
                'name' => self::SUBMIT,
                'class' => 'abs-action-primary scalable',
            ]
        )->addCustomAttribute('data-amacart-js','report-submit');

        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * @return array
     */
    private function getWebsitesArray()
    {
        $websites = $this->objectConverter->toOptionArray(
            $this->_storeManager->getWebsites(),
            'website_id',
            'name'
        );

        array_unshift($websites, ['value' => self::ALL, 'label' => __('All Websites')]);

        return $websites;
    }
}
