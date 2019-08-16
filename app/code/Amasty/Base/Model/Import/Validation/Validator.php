<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Model\Import\Validation;

/**
 * Class Validator
 * @package Amasty\Base\Model\Import\Validation
 */
class Validator implements ValidatorInterface
{
    /**
     * @var \Magento\Framework\DataObject
     */
    protected $validationData;

    public function __construct(\Magento\Framework\DataObject $validationData)
    {
        $this->validationData = $validationData;
    }

    protected $errors = [];

    /**
     * @var array
     */
    protected $messageTemplates = [

    ];

    /**
     * @inheritdoc
     */
    public function validateRow(array $rowData, $behavior)
    {
        return true;
    }

    /**
     * Usual behavior at the end of validation. Help function
     *
     * @return array|bool
     */
    public function validateResult()
    {
        if ($this->errors) {
            return $this->errors;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function getErrorMessages()
    {
        return $this->messageTemplates;
    }
}
