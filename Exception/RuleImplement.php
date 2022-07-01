<?php namespace Motokraft\Validator\Exception;

/**
 * @copyright   2022 motokraft. MIT License
 * @link https://github.com/motokraft/validator
 */

use \Motokraft\Validator\Rules\RuleInterface;

class RuleImplement extends \Exception
{
    private $name;
    private $obj;

    function __construct(string $name, object $obj, int $code = 404)
    {
        $this->name = $name;
        $this->obj = $obj;

        $text = 'The %s class must implement the %s interface';

        parent::__construct(sprintf($text,
            get_class($obj), RuleInterface::class
        ), $code);
    }

    function getName() : null|string
    {
        return $this->name;
    }

    function getObject() : null|object
    {
        return $this->obj;
    }
}