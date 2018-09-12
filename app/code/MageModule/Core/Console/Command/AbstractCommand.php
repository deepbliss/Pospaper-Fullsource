<?php

namespace MageModule\Core\Console\Command;

abstract class AbstractCommand extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var array
     */
    private $defaultOptions;

    /**
     * AbstractCommand constructor.
     *
     * @param array       $defaultOptions
     * @param null|string $name
     */
    public function __construct(
        $defaultOptions = [],
        $name = null
    ) {
        $this->defaultOptions = $defaultOptions;
        parent::__construct($name);
    }

    /**
     * @param string $option
     *
     * @return string|null
     */
    public function getDefaultOption($option)
    {
        return array_key_exists($option, $this->defaultOptions) ?
            $this->defaultOptions[$option] :
            null;
    }
}
