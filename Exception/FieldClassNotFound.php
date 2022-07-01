<?php namespace Motokraft\Validator\Exception;

/**
 * @copyright   2022 motokraft. MIT License
 * @link https://github.com/motokraft/validator
 */

class FieldClassNotFound extends \Exception
{
    private $name;
    private $class;

    function __construct(string $name, string $class, int $code = 404)
    {
        $this->name = $name;
        $this->class = $class;

        $message = sprintf('Field class "%s" not found!', $class);
        parent::__construct($message, $code);
    }

    function getName() : null|string
    {
        return $this->name;
    }

    function getClassName() : null|string
    {
        return $this->class;
    }
}