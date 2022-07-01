<?php namespace Motokraft\Validator\Field;

/**
 * @copyright   2022 motokraft. MIT License
 * @link https://github.com/motokraft/validator
 */

use \Motokraft\Validator\Validator;
use \Motokraft\Validator\ValidatorError;
use \Motokraft\Validator\Rules\BaseRule;
use \Motokraft\Validator\Rules\RuleInterface;
use \Motokraft\Validator\Exception\RuleClassNotFound;
use \Motokraft\Validator\Exception\RuleNotFound;
use \Motokraft\Validator\Exception\RuleImplement;
use \Motokraft\Validator\Exception\RuleExtends;
use \Motokraft\Validator\Exception\RuleInvalid;
use \Motokraft\Object\BaseObject;
use \Motokraft\Object\Traits\ObjectTrait;

class BaseField implements FieldInterface
{
    use ObjectTrait;

    private $title;
    private $name;
    private $rules = [];

    function __construct(string $name, array $data = [])
    {
        $this->name = $name;

        if(!empty($data))
        {
            $this->loadArray($data);
        }
    }

    function addRule(string $name, array $options = [])
    {
        if(!Validator::hasRuleClass($name))
        {
            throw new RuleNotFound($name);
        }

        $result = Validator::getRuleClass($name);

        if(!class_exists($result))
        {
            throw new RuleClassNotFound($name, $result);
        }

        $result = new $result($name, $options);

        if(!$result instanceof RuleInterface)
        {
            throw new RuleImplement($name, $result);
        }

        if(!$result instanceof BaseRule)
        {
            throw new RuleExtends($name, $result);
        }

        $this->rules[$name] = $result;
        return $result;
    }

    function getRule(string $name, $default = null)
    {
        if(!$this->hasRule($name))
        {
            return $default;
        }

        return $this->rules[$name];
    }

    function removeRule(string $name) : bool
    {
        if(!$this->hasRule($name))
        {
            return false;
        }

        unset($this->rules[$name]);
        return true;
    }

    function hasRule() : bool
    {
        return isset($this->rules[$name]);
    }

    function getRules() : array
    {
        return $this->rules;
    }

    function setTitle(string $title) : static
    {
        $this->title = $title;
        return $this;
    }

    function getTitle() : null|string
    {
        return $this->title;
    }

    function getName() : null|string
    {
        return $this->name;
    }

    function validate($value, Validator $validator) : bool
    {
        foreach($this->rules as $rule)
        {
            if($rule->valid($value, $validator))
            {
                continue;
            }

            $this->prepareError($rule, $value, $validator);
            return false;
        }

        return true;
    }

    private function prepareError(
        RuleInterface $rule, $value, Validator $validator)
    {
        $field_name = $this->getName();
        $rule_name = $rule->getName();

        $key = $field_name . ':' . $rule_name;

        if(Validator::hasMessage($key))
        {
            $message = Validator::getMessage($key);
        }
        else if (Validator::hasMessage($rule_name))
        {
            $message = Validator::getMessage($rule_name);
        }
        else if (Validator::hasMessage($field_name))
        {
            $message = Validator::getMessage($field_name);
        }
        else
        {
            return false;
        }

        if(!$title = $this->getTitle())
        {
            $title = $this->getName();
        }

        $item = new BaseObject([
            'attribute' => $title,
            'value' => $value
        ]);

        $item->loadArray($this->getArray());
        $item->loadArray($rule->getArray());

        $keys = array_keys($item->getArray());
        $vals = array_values($item->getArray());

        $keys = array_map(function ($key) {
            return ':' . strtolower($key);
        }, $keys);

        $message = str_replace($keys, $vals, $message);

        $validator->setError($key, new RuleInvalid(
            $key, $message, $this, $rule
        ));
    }
}