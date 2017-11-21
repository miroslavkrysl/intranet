<?php


namespace Core\Contracts\Validation;


/**
 * Interface for validator.
 */
interface ValidatorInterface
{
    /**
     * Test the entity against the given rules.
     * @param object $entity
     * @param array $rules
     * @return bool
     */
    public function validate($entity, array $rules): bool;

    /**
     * Get validation errors.
     * @return array
     */
    public function errors(): array;

    /**
     * Check whether the value contain only alphabetical characters.
     * @param $value
     * @return bool
     */
    public function alpha($value): bool;

    /**
     * Check whether the value contain only alphanumeric values.
     * @param $value
     * @return bool
     */
    public function alphaNumeric($value): bool;

    /**
     * Check whether the value is numeric.
     * @param $value
     * @return bool
     */
    public function numeric($value): bool;

    /**
     * Check whether the value is an integer.
     * @param $value
     * @return bool
     */
    public function digits($value): bool;

    /**
     * Check whether the value is a valid date according to the strtotime() php function.
     * @param $value
     * @return bool
     */
    public function date($value): bool;

    /**
     * Check whether the value is a valid email address.
     * @param $value
     * @return bool
     */
    public function email($value): bool;

    /**
     * Check whether the value exists and is not null.
     * @param $value
     * @return bool
     */
    public function required($value): bool;

    /**
     * Check whether the value is a boolean value (true, false, 1, 0).
     * @param $value
     * @return bool
     */
    public function boolean($value): bool;

    /**
     * Check whether the value is unique according to records in the database table.
     * @param $value
     * @param string $table
     * @param string $column
     * @return bool
     */
    public function unique($value, string $table, string $column): bool;

    /**
     * Check whether the value is a date after the valueAfter.
     * The values will be passed into the strtotime() php function.
     * @param $value
     * @param $valueAfter
     * @return bool
     */
    public function after($value, $valueAfter): bool;

    /**
     * Check whether the value is a number bigger or equal than min.
     * @param $value
     * @param int $min
     * @return bool
     */
    public function min($value, int $min): bool;

    /**
     * Check whether the value is a number smaller or equal than max.
     * @param $value
     * @param int $max
     * @return bool
     */
    public function max($value, int $max): bool;

    /**
     * Check whether the value is a string with the length smaller or equal the min.
     * @param $value
     * @param int $min
     * @return bool
     */
    public function minLength($value, int $min): bool;

    /**
     * Check whether the value is a string with the length bigger or equal the max.
     * @param $value
     * @param int $max
     * @return bool
     */
    public function maxLength($value, int $max): bool;

    /**
     * Check whether the value matches the given pattern.
     * @param $value
     * @param string $pattern
     * @return bool
     */
    public function regex($value, string $pattern): bool;

    /**
     * Check whether the value exists in the database table column.
     * @param $value
     * @param string $table
     * @param string $column
     * @return bool
     */
    public function exists($value, string $table, string $column): bool;
}