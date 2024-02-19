<?php

if (!function_exists('array_keys_snake_case'))
{
    /**
     * Convert camelCase type array keys to snake case
     *
     * @param array $mixed
     * @param bool|true $recursive
     */
    function array_keys_snake_case(&$mixed, $recursive = true)
    {
        if (!method_exists(\Illuminate\Support\Str::class, 'snake')) throw new \Exception("Function '\Illuminate\Support\Str::snake' is undefined.");
        array_keys_replace($mixed, '\Illuminate\Support\Str::snake', $recursive);
    }
}


/**
 * Convert array keys to camelCase
 *
 * @param $mixed
 * @param bool|true $recursive
 * @throws Exception
 */
if (!function_exists('array_keys_camel_case'))
{
    function array_keys_camel_case(&$mixed, $recursive = true)
    {

        if (!method_exists(\Illuminate\Support\Str::class, 'camel')) throw new \Exception("Function '\Illuminate\Support\Str::camel' is undefined.");
        array_keys_replace($mixed, '\Illuminate\Support\Str::camel', $recursive);
    }
}