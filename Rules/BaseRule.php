<?php namespace Motokraft\Validator\Rules;

/**
 * @copyright   2022 motokraft. MIT License
 * @link https://github.com/motokraft/validator
 */

use \Motokraft\Object\Traits\ObjectTrait;

abstract class BaseRule
{
    use ObjectTrait;

    private $name;

    function __construct(string $name, array $data = [])
    {
        $this->setName($name);

        if(!empty($data))
        {
            $this->loadArray($data);
        }
    }

    function setName(string $name) : static
    {
        $this->set('name', $name);
        return $this;
    }

    function getName() : null|string
    {
        return $this->get('name');
    }
}