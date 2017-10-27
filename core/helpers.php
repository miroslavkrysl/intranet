<?php

/**
 * Transform multidimensional array into one-dimensional.
 * The nested keys in old array is represented in dot notation in new array.
 * @param array $array
 * @return array
 */
function array_to_dot(array $array): array {
    $result = [];

    foreach ($array as $key => $value) {
        if (!is_array($value)) {
            $result[$key] = $value;
        }
        else {
            $subArray = array_to_dot($value);
            foreach ($subArray as $subKey => $subValue){
                $result[$key.'.'.$subKey] = $subValue;
            }
        }
    }

    return $result;
}