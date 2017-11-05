<?php


namespace Core\DotArray;
use Core\DotArray\Exception\ArrayKeyNotExistsException;


/**
 * Represents an array with the values accessible by the dot notation.
 */
class DotArray
{
    /**
     * A stored data as an associative multidimensional array.
     * @var array
     */
    private $data;

    /**
     * DotArray constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the value or an subarray from the array identified by the key in dot notation.
     * @param string $key
     * @return array|mixed
     * @throws ArrayKeyNotExistsException
     */
    public function get(string $key = null): mixed
    {
        $keys = \explode('.', $key);
        $value = $this->data;

        foreach ($keys as $k) {
            if (!is_array($value) || !array_key_exists($k, $value)) {
                throw new ArrayKeyNotExistsException('Array key ' . $key . ' does not exist.');
            }
            $value = $value[$k];
        }
        return $value;
    }

    /**
     * Set the value in array identified by the key in dot notation.
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, mixed $value)
    {
        $keys = \explode('.', $key);
        $array = &$this->data;

        foreach ($keys as $k) {
            if (!\is_array($array)) {
                $array = [];
            }
            if (!\array_key_exists($k, $array)) {
                $array[$key] = null;
            }
            $array = &$array[$k];
        }
        $array[\end($keys)] = $value;
    }

    /**
     * Check if the specified key exists.
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        try {
            $this->get($key);
            return true;
        }
        catch (\Exception $e) {
            return false;
        }
    }
}