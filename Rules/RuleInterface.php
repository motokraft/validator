<?php namespace Motokraft\Validator\Rules;

/**
 * @copyright   2022 motokraft. MIT License
 * @link https://github.com/motokraft/validator
 */
use \Motokraft\Validator\Validator;

interface RuleInterface
{
    function valid($value, Validator $validator) : bool;
}