<?php namespace Motokraft\Validator\Exception;

/**
 * @copyright   2022 motokraft. MIT License
 * @link https://github.com/motokraft/validator
 */

use \Motokraft\Validator\Field\FieldInterface;
use \Motokraft\Validator\Rules\RuleInterface;

class RuleInvalid extends \Exception
{
    private $name;
    private $field;
    private $rule;

    function __construct(string $name, string $message,
        FieldInterface $field, RuleInterface $rule, int $code = 500)
    {
        $this->name = $name;
        $this->field = $field;
        $this->rule = $rule;

        parent::__construct($message, $code);
    }

    function getName() : null|string
    {
        return $this->name;
    }

    function getField() : FieldInterface
    {
        return $this->field;
    }

    function getRule() : RuleInterface
    {
        return $this->rule;
    }

    function __toString() : string
    {
        return $this->getMessage();
    }
}