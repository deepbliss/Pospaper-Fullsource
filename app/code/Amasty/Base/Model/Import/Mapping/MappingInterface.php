<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Model\Import\Mapping;

/**
 * @since 1.4.6
 */
interface MappingInterface
{
    /**
     * @return array
     */
    public function getValidColumnNames();

    /**
     * @param string $columnName
     *
     * @throws \Amasty\Base\Exceptions\MappingColumnDoesntExist
     * @return string|bool
     */
    public function getMappedField($columnName);

    /**
     * @throws \Amasty\Base\Exceptions\MasterAttributeCodeDoesntSet
     * @return string
     */
    public function getMasterAttributeCode();
}
