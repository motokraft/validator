<?php namespace Motokraft\Validator;

/**
 * @copyright   2022 motokraft. MIT License
 * @link https://github.com/motokraft/validator
 */

use \Motokraft\Validator\Field\BaseField;
use \Motokraft\Validator\Field\FieldInterface;
use \Motokraft\Validator\Exception\FieldClassNotFound;
use \Motokraft\Validator\Exception\FieldImplement;
use \Motokraft\Validator\Exception\FieldExtends;
use \Motokraft\Validator\Exception\RuleInvalid;
use \Motokraft\Object\BaseObject;

class Validator extends BaseObject
{
    private static $instances = [];

    private static $_rules = [
        'required' => Rules\RequiredRule::class,
        'email' => Rules\EmailRule::class,
        'numeric' => Rules\NumericRule::class,
        'boolean' => Rules\BooleanRule::class,
        'min' => Rules\MinRule::class,
        'max' => Rules\MaxRule::class
    ];

    private static $_fields = [
        '_default' => BaseField::class
    ];

    private static $_messages = [
        'required' => 'The :attribute is required',
        'email' => 'The :attribute is not valid email',
        'numeric' => 'The :attribute must be numeric',
        'boolean' => 'The :attribute must be a boolean',
        'min' => 'The :attribute minimum is :value',
        'max' => 'The :attribute maximum is :value'
    ];

    private $fields = [];
    private $valid_data;
    private $invalid_data;
    private $errors = [];

    function __construct(array $data = [])
    {
        parent::__construct($data);

        $this->valid_data = new BaseObject;
        $this->invalid_data = new BaseObject;
    }

    static function getInstance(string $name) : static
    {
        if(!isset(self::$instances[$name]))
        {
            self::$instances[$name] = new static;
        }

        return self::$instances[$name];
    }

    static function addRuleClass(string $name, string $class)
    {
        self::$_rules[$name] = $class;
    }

    static function getRuleClass(string $name, $default = null)
    {
        if(!self::hasRuleClass($name))
        {
            return $default;
        }

        return self::$_rules[$name];
    }

    static function removeRuleClass(string $name) : bool
    {
        if(!self::hasRuleClass($name))
        {
            return false;
        }

        unset(self::$_rules[$name]);
        return true;
    }

    static function hasRuleClass(string $name) : bool
    {
        return isset(self::$_rules[$name]);
    }

    static function addFieldClass(string $name, string $class)
    {
        self::$_fields[$name] = $class;
    }

    static function getFieldClass(string $name, $default = null)
    {
        if(!self::hasFieldClass($name))
        {
            return $default;
        }

        return self::$_fields[$name];
    }

    static function removeFieldClass(string $name) : bool
    {
        if(!self::hasFieldClass($name))
        {
            return false;
        }

        unset(self::$_fields[$name]);
        return true;
    }

    static function hasFieldClass(string $name) : bool
    {
        return isset(self::$_fields[$name]);
    }

    static function setMessage(string $name, string $text)
    {
        self::$_messages[$name] = $text;
    }

    static function getMessage(string $name, $default = null)
    {
        if(!self::hasMessage($name))
        {
            return $default;
        }

        return self::$_messages[$name];
    }

    static function removeMessage(string $name) : bool
    {
        if(!self::hasMessage($name))
        {
            return false;
        }

        unset(self::$_messages[$name]);
        return true;
    }

    static function hasMessage(string $name) : bool
    {
        return isset(self::$_messages[$name]);
    }

    function addField(string $name, array $options = []) : FieldInterface
    {
        if(!$field = self::getFieldClass($name))
        {
            $field = self::getFieldClass('_default');
        }

        if(!class_exists($field))
        {
            throw new FieldClassNotFound($name, $field);
        }

        $result = new $field($name, $options);

        if(!$result instanceof FieldInterface)
        {
            throw new FieldImplement($name, $result);
        }

        if(!$result instanceof BaseField)
        {
            throw new FieldExtends($name, $result);
        }

        $this->fields[$name] = $result;
        return $result;
    }

    function getField(string $name, $default = null)
    {
        if(!$this->hasField($name))
        {
            return $default;
        }

        return $this->fields[$name];
    }

    function removeField(string $name) : bool
    {
        if($name === '_default')
        {
            return false;
        }

        if(!$this->hasField($name))
        {
            return false;
        }

        unset($this->fields[$name]);
        return true;
    }

    function hasField(string $name) : bool
    {
        return isset($this->fields[$name]);
    }

    function getFields() : array
    {
        return $this->fields;
    }

    function getValidData() : BaseObject
    {
        return $this->valid_data;
    }

    function getInvalidData() : BaseObject
    {
        return $this->invalid_data;
    }

    function setError(string $name, RuleInvalid $error) : static
    {
        $this->errors[$name] = $error;
        return $this;
    }

    function getError(string $name) : bool|RuleInvalid
    {
        if(!$this->hasError($name))
        {
            return false;
        }

        return $this->errors->get($name);
    }

    function removeError(string $name) : bool
    {
        if(!$this->hasError($name))
        {
            return false;
        }

        unset($this->errors[$name]);
        return true;
    }

    function hasError(string $name) : bool
    {
        return isset($this->errors[$name]);
    }

    function getErrors() : array
    {
        return $this->errors;
    }

    function validate() : bool
    {
        $result = [];

        foreach($this->fields as $name => $field)
        {
            $value = $this->get($name);

            if(!$field->validate($value, $this))
            {
                $this->invalid_data->set($name, $value);
                array_push($result, false);
            }
            else if(!empty($value))
            {
                $this->valid_data->set($name, $value);
                array_push($result, true);
            }
        }

        return !in_array(false, $result, true);
    }
}