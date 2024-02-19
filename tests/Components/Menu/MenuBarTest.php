<?php


class MenuBarTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @test
     */
    public function test_MenuBar_menuItems_returns_correct_menu_items()
    {
        $this->assertEquals([], \EMedia\Helpers\Components\Menu\MenuBar::menuItems());

        $text = "foobar";
        $item = new \EMedia\Helpers\Components\Menu\MenuItem('foo');
        $item->setText($text);

        \EMedia\Helpers\Components\Menu\MenuBar::add($item);
        $this->assertEquals(\EMedia\Helpers\Components\Menu\MenuBar::menuItems()[0]->getText(), $item->getText());

        $differentItemText = "bar";
        $differentItem = new \EMedia\Helpers\Components\Menu\MenuItem('foo');
        $differentItem->setText($differentItemText);

        $parent = 'baz';
        \EMedia\Helpers\Components\Menu\MenuBar::add($item, $parent);
        \EMedia\Helpers\Components\Menu\MenuBar::add($item, $parent);
        \EMedia\Helpers\Components\Menu\MenuBar::add($item, $parent);

        $this->assertCount(1, \EMedia\Helpers\Components\Menu\MenuBar::menuItems());

        $this->assertCount(3, \EMedia\Helpers\Components\Menu\MenuBar::menuItems($parent));
    }


    /**
     * @test
     */
    public function test_MenuBar_menuItems_returns_sorted_by_order_then_text()
    {
        $first = new \EMedia\Helpers\Components\Menu\MenuItem();
        $first->setOrder(1)->setText('first');

        $second = new \EMedia\Helpers\Components\Menu\MenuItem();
        $second->setOrder(2)->setText('second');

        $third = new \EMedia\Helpers\Components\Menu\MenuItem();
        $third->setText('a_third');

        $fourth = new \EMedia\Helpers\Components\Menu\MenuItem();
        $fourth->setText('b_fourth');


        \EMedia\Helpers\Components\Menu\MenuBar::add($fourth);
        \EMedia\Helpers\Components\Menu\MenuBar::add($second);
        \EMedia\Helpers\Components\Menu\MenuBar::add($first);
        \EMedia\Helpers\Components\Menu\MenuBar::add($third);


        $this->assertEquals($first->getText(), \EMedia\Helpers\Components\Menu\MenuBar::menuItems()[0]->getText());
        $this->assertEquals($second->getText(), \EMedia\Helpers\Components\Menu\MenuBar::menuItems()[1]->getText());
        $this->assertEquals($third->getText(), \EMedia\Helpers\Components\Menu\MenuBar::menuItems()[2]->getText());
        $this->assertEquals($fourth->getText(), \EMedia\Helpers\Components\Menu\MenuBar::menuItems()[3]->getText());
    }
}