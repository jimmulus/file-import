<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class ArrayKeyCaseConverter
{

    /**
    * Loops trhough array and perform doSnakeCase
    *
    * @param array $array
    *
    * @return array
    */
    public function snakeCase(array $array): array
    {
        return array_map(
            function ($item) {
                if (is_array($item)) {
                    $item = $this->snakeCase($item);
                }
                return $item;
            },
            $this->doSnakeCase($array)
        );
    }

    /**
    * Converts camelCase keys to snake_case keys
    *
    * @param array $array
    *
    * @return array
    */
    private function doSnakeCase(array $array): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            $key = Str::snake($key);
            $result[$key] = $value;
        }
        return $result;
    }
}
