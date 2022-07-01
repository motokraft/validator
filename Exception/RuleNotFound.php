<?php namespace Motokraft\Validator\Exception;

/**
 * @copyright   2022 motokraft. MIT License
 * @link https://github.com/motokraft/validator
 */

class RuleNotFound extends \Exception
{
    private $name;

    function __construct(string $name, int $code = 404)
    {
        $this->name = $name;

        $message = sprintf('Rule name "%s" not found!', $name);
        parent::__construct($message, $code);
    }

    function getName() : null|string
    {
        return $this->name;
    }
}