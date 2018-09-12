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
 * @author        MageModule, LLC admin@magemodule.com
 * @copyright     2018 MageModule, LLC
 * @license       http://www.magemodule.com/magento2-ext-license.html
 *
 */

namespace MageModule\Core\Model\Data\Validator;

/**
 * Validates that field(s) have values or if they are null or ''
 *
 * Class RequiredValues
 *
 * @package MageModule\Core\Model\Data\Validator
 */
class RequiredValues implements \MageModule\Core\Model\Data\ValidatorInterface
{
    /**
     * @var \MageModule\Core\Model\Data\Validator\ResultFactory
     */
    private $resultFactory;

    /**
     * @var array
     */
    private $requiredFields;

    /**
     * RequiredFields constructor.
     *
     * @param array $requiredFields
     */
    public function __construct(
        \MageModule\Core\Model\Data\Validator\ResultFactory $resultFactory,
        array $requiredFields
    ) {
        $this->resultFactory  = $resultFactory;
        $this->requiredFields = $requiredFields;
    }

    /**
     * @param array  $data
     * @param string $field
     *
     * @return bool
     */
    private function test(array $data, $field)
    {
        $result = true;
        if (!isset($data[$field])) {
            $result = false;
        } elseif ($data[$field] === null) {
            $result = false;
        } elseif (is_string($data[$field]) && trim($data[$field]) === '') {
            $result = false;
        }

        return $result;
    }

    /**
     * Can validate singular field requirements or a minimum presence of a choice of fields.
     *
     * [
     *     'entity_id', <===== singular required field
     *     'increment_id',
     *     [2 => ['state', 'status', 'not_present_1', 'not_present_2']], <===== 2 of these choices must be present
     *     [1 => ['created_at', 'updated_at', 'not_present_1', 'not_present_2']] <===== 1 of these choices must be present
     * ]
     *
     * @param array $data
     *
     * @return \MageModule\Core\Model\Data\Validator\ResultInterface
     */
    public function validate(array $data)
    {
        $message             = null;
        $choiceMessages      = [];
        $missingFields       = [];
        $missingChoiceFields = [];

        foreach ($this->requiredFields as $field) {
            if (is_array($field)) {
                $requiredCount = key($field);
                $choices       = current($field);

                $presentFields    = [];
                $notPresentFields = [];
                foreach ($choices as $choice) {
                    if (!$this->test($data, $choice)) {
                        $notPresentFields[] = $choice;
                    } else {
                        $presentFields[] = $choice;
                    }
                }

                $presentFieldsCount = count($presentFields);
                if ($presentFieldsCount < $requiredCount) {
                    $choiceMessage = sprintf(
                        '%d of the following fields are required to be present and contain a value: %s.',
                        $requiredCount,
                        implode(', ', $choices)
                    );

                    if ($presentFieldsCount === 1) {
                        $choiceMessage .= sprintf(
                            ' However, only %s contains a value.',
                            implode(', ', $presentFields)
                        );
                    } elseif ($presentFieldsCount > 1) {
                        $choiceMessage .= sprintf(
                            ' However, only %s contain values.',
                            implode(', ', $presentFields)
                        );
                    }

                    $choiceMessages[]      = $choiceMessage;
                    $missingChoiceFields[] = [
                        'required' => $requiredCount,
                        'choices'  => $choices,
                        'present'  => $presentFields,
                        'missing'  => $notPresentFields
                    ];
                }
            } elseif (!$this->test($data, $field)) {
                $missingFields[] = $field;
            }
        }

        if ($missingFields) {
            $message = sprintf(
                'The following required fields are either missing or do not contain a value: %s',
                implode(', ', $missingFields)
            );
        }

        if ($choiceMessages) {
            array_unshift($choiceMessages, $message);
            $choiceMessages = array_filter($choiceMessages, 'strlen');
            $message        = implode(PHP_EOL, $choiceMessages);
        }

        $result = $this->resultFactory->create(
            [
                'isValid'     => empty($missingFields) && empty($missingChoiceFields),
                'message'     => $message,
                'invalidData' => array_merge($missingFields, $missingChoiceFields)
            ]
        );

        return $result;
    }
}
