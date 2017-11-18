<?php


namespace Core\Validation;


use Core\Contracts\Database\DatabaseInterface;
use Core\Contracts\Language\LanguageInterface;
use Core\Contracts\Validation\ValidatorInterface;
use Core\Validation\Exception\ValidatorException;


/**
 * Entity validator.
 * @package Core\Validation
 */
class Validator implements ValidatorInterface
{
    /**
     * Database to be used for some tests.
     * @var DatabaseInterface
     */
    private $database;

    /**
     * @var LanguageInterface
     */
    private $language;

    /**
     * Contain associative array of errors if occurred.
     * @var array
     */
    private $errors;

    /**
     * The language prefix for getting the error messages.
     * @var string
     */
    private $languagePrefix;

    /**
     * Validator constructor.
     * @param DatabaseInterface $database
     * @param LanguageInterface $language
     * @param string $languagePrefix
     */
    public function __construct(DatabaseInterface $database, LanguageInterface $language, string $languagePrefix)
    {
        $this->database = $database;
        $this->language = $language;
        $this->languagePrefix = $languagePrefix;
    }

    /**
     * Get validation errors.
     * @return array
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Test the entity against the given rules.
     * @param object $entity
     * @param array $rules
     * @return bool
     */
    public function validate(\object $entity, array $rules): bool
    {
        $valid = true;

        foreach ($rules as $field => $fieldRules) {

            foreach ($fieldRules as $rule => $params) {

                // snake_case to camelCase
                $method = \lcfirst(\implode('', \array_map('ucfirst', \explode('_', $rule))));

                if (!\method_exists($this, $method)) {
                    throw new ValidatorException(\sprintf('Validation rule %s does not exist.', $method));
                }

                $reflectionMethod = new \ReflectionMethod($this, $method);
                $args = [
                    'value' => $entity->$field ?? null
                ];

                foreach ($reflectionMethod->getParameters() as $parameter) {
                    $name = $parameter->getName();
                    if (!\array_key_exists($name, $params)) {
                        throw new ValidatorException(\sprintf('You must specify the %s parameter in %s rule.', $name, $method));
                    }
                    $args[$name] = $params[$name];
                }


                if (!$reflectionMethod->invokeArgs($this, $args)) {
                    $valid = false;
                    $this->errors[$field] =
                        $params['message'] ??
                        $this->language->get($this->languagePrefix . $method, $args);
                }
            }
        }

        return $valid;
    }

    /**
     * Check whether the value contain only alphabetical characters.
     * @param $value
     * @return bool
     */
    public function alpha($value): bool
    {
        return \ctype_alpha($value);
    }

    /**
     * Check whether the value contain only alphanumeric values.
     * @param $value
     * @return bool
     */
    public function alphaNumeric($value): bool
    {
        return \ctype_alnum($value);
    }

    /**
     * Check whether the value is numeric.
     * @param $value
     * @return bool
     */
    public function numeric($value): bool
    {
        return \is_numeric($value);
    }

    /**
     * Check whether the value contains only digits.
     * @param $value
     * @return bool
     */
    public function digits($value): bool
    {
        return \ctype_digit($value);
    }

    /**
     * Check whether the value is a valid date according to the strtotime() php function.
     * @param $value
     * @return bool
     */
    public function date($value): bool
    {
        return \strtotime($value) !== false;
    }

    /**
     * Check whether the value is a valid email address.
     * @param $value
     * @return bool
     */
    public function email($value): bool
    {
        return \filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Check whether the value is not null and is not an empty string or array.
     * @param $value
     * @return bool
     */
    public function required($value): bool
    {
        return !\is_null($value) and
            $value !== '' and
            $value !== [];
    }

    /**
     * Check whether the value is a boolean value (true, false, 1, 0).
     * @param $value
     * @return bool
     */
    public function boolean($value): bool
    {
        return \is_bool($value) or
            \strtolower($value) == 'true' or
            \strtolower($value) == 'false' or
            $value == 1 or
            $value == 0;
    }

    /**
     * Check whether the value is unique according to records in the database table.
     * @param $value
     * @param string $table
     * @param string $column
     * @return bool
     */
    public function unique($value, string $table, string $column): bool
    {
        $query =
            "SELECT * FROM $table ".
            "WHERE $column = :value;";
        $params = [
            'value' => $value
        ];
        $this->database->execute($query, $params);
        return $this->database->count() == 0;
    }

    /**
     * Check whether the value is a date after the valueAfter.
     * The values will be passed into the strtotime() php function.
     * @param $value
     * @param $valueBefore
     * @return bool
     */
    public function after($value, $valueBefore): bool
    {
        return \strtotime($value) > \strtotime($valueBefore);
    }

    /**
     * Check whether the value is a number bigger or equal than min.
     * @param $value
     * @param int $min
     * @return bool
     */
    public function min($value, int $min): bool
    {
        return $value >= $min;
    }

    /**
     * Check whether the value is a number smaller or equal than max.
     * @param $value
     * @param int $max
     * @return bool
     */
    public function max($value, int $max): bool
    {
        return $value <= $max;
    }

    /**
     * Check whether the value is a string with the length smaller or equal the min.
     * @param $value
     * @param int $min
     * @return bool
     */
    public function minLength($value, int $min): bool
    {
        return \count($value) >= $min;
    }

    /**
     * Check whether the value is a string with the length bigger or equal the max.
     * @param $value
     * @param int $max
     * @return bool
     */
    public function maxLength($value, int $max): bool
    {
        return \count($value) <= $max;
    }

    /**
     * Check whether the value matches the given pattern.
     * @param $value
     * @param string $pattern
     * @return bool
     */
    public function regex($value, string $pattern): bool
    {
        return \preg_match($value, $pattern);
    }
}