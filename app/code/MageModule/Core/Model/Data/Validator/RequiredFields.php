<?php

namespace MageModule\Core\Model\Data\Validator;

/**
 * Validates that required fields are present in data. Does not validate presence of a value
 *
 * Class RequiredFields
 *
 * @package MageModule\Core\Model\Data\Validator
 */
class RequiredFields implements \MageModule\Core\Model\Data\ValidatorInterface
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
                    if (array_key_exists($choice, $data)) {
                        $presentFields[] = $choice;
                    } else {
                        $notPresentFields[] = $choice;
                    }
                }

                if (count($presentFields) < $requiredCount) {
                    $choiceMessage = sprintf(
                        '%d of the following fields are required: %s.',
                        $requiredCount,
                        implode(', ', $choices)
                    );

                    if ($presentFields) {
                        $choiceMessage .= sprintf(
                            ' However, only %s %s present.',
                            implode(', ', $presentFields),
                            count($presentFields) === 1 ? 'is' : 'are'
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
            } elseif (!array_key_exists($field, $data)) {
                $missingFields[] = $field;
            }
        }

        if ($missingFields) {
            $message = sprintf(
                'The following required fields are missing: %s',
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
