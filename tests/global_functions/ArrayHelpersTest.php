<?php


class ArrayHelpersTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     * @throws Exception
     */
    public function test__array_helpers__array_keys_snake_case__converts_to_snake_case()
    {
        $keys = [
            'fooBar' => 'foo_bar',
            'bar_baz' => 'bar_baz',
            '_baz' => '_baz',
            'fizz' => 'fizz'
        ];

        $arr = [];

        foreach ($keys as $key => $converted) {
            $arr[$key] = true;
        }

        array_keys_snake_case($arr);

        foreach ($keys as $key => $converted) {
            $this->assertTrue($arr[$converted]);
        }
    }

    /**
     * @test
     * @throws Exception
     */
    public function test__array_helpers__array_keys_snake_case__converts_to_camel_case()
    {
        $keys = [
            'foo_bar' => 'fooBar',
            '_bar_baz' => 'barBaz',
            '_baz' => 'baz',
            'fizz_' => 'fizz',
            'quetz' => 'quetz'
        ];

        $arr = [];

        foreach ($keys as $key => $converted) {
            $arr[$key] = true;
        }

        array_keys_camel_case($arr);

        foreach ($keys as $key => $converted) {
            $this->assertTrue($arr[$converted]);
        }
    }
}