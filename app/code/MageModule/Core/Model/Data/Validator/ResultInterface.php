<?php

namespace MageModule\Core\Model\Data\Validator;

interface ResultInterface
{
    /**
     * @return bool
     */
    public function isValid();

    /**
     * @return null|string
     */
    public function getMessage();

    /**
     * Data about what specifically is invalid. For example, the names of missing columns
     *
     * @return array
     */
    public function getInvalidData();
}
