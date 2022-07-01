<?php namespace Motokraft\Validator\Rules;

/**
 * @copyright   2022 motokraft. MIT License
 * @link https://github.com/motokraft/validator
 */

use \Motokraft\Validator\Validator;

class BooleanRule extends BaseRule implements RuleInterface
{
    function valid($value, Validator $validator) : bool
    {
        if(!filter_var($value, FILTER_VALIDATE_BOOLEAN))
        {
            return false;
        }

        return in_array($value, [1, '1', 'true', 'yes']);
    }
}