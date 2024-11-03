<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Planet\InterviewChallenge\Domain\Shop\Cart;
use Planet\InterviewChallenge\Domain\Shop\CartItem;

class CartTest extends TestCase
{
    private Cart $object;

    public function setUp(): void
    {
        $this->object = new Cart();
    }

    public function tearrDown(): void
    {
        unset($this->object);
    }

    public function testGetState(): void
    {
        $this->object->addItem(new CartItem(12300, -2));
        $state = $this->object->getState();

        $expected = [
            (object)[
                'price' => 12300,
                'expires' => -2,
            ]
        ];
        $this->assertEquals(1, count(json_decode($state)));
        $this->assertEquals($expected, json_decode($state));
    }

    public function testGetItems(): void
    {
        $this->object->addItem(new CartItem(12300, -2));
        $this->object->addItem(new CartItem(45600, 15));
        $items = $this->object->getItems();

        $expected = [
            ["price" => 12300, "expires" => -2],
            ["price" => 45600, "expires" => 15],
        ];

        $this->assertEquals(count($expected), count($items));
        foreach ($expected as $key => $expectedItem) {
            $this->assertEquals($expectedItem["price"], $items[$key]->getPrice());
            $this->assertEquals($expectedItem["expires"], $items[$key]->getExpires());
        }
    }
}
