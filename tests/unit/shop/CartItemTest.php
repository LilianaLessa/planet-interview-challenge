<?php

use Planet\InterviewChallenge\Shop\CartItem;

class CartItemTest extends \PHPUnit\Framework\TestCase
{
    protected $object;

    public function tearDown(): void
    {
        unset($this->object);
        $this->object = null;
    }

    public function testIsAvailable()
    {
        $object = new CartItem(123, CartItem::MODE_NO_LIMIT);
        $this->assertTrue($object->isAvailable());

        $object = new CartItem(123, CartItem::MODE_NO_LIMIT, 1);
        $this->assertTrue($object->isAvailable());

        $object = new CartItem(123, CartItem::MODE_SECONDS, 60);
        $this->assertFalse($object->isAvailable());
        sleep(30);
        $this->assertFalse($object->isAvailable());
        sleep(30);
        $this->assertTrue($object->isAvailable());
    }
}
