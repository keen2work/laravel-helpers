<?php


class MenuItemTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @test
     */
    public function test_MenuItem_toArray_returns_an_array()
    {
        $text = "foo";

        $item = new \EMedia\Helpers\Components\Menu\MenuItem();
        $item->setText($text);

        $arr = $item->toArray();

        $this->assertEquals($arr["text"], $text);
    }
}