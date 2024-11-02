<?php

namespace Planet\InterviewChallenge\Domain\Shop;

use stdClass;

class CartItem implements \JsonSerializable
{
    private int $expires;

    private int $price;

    public function __construct(int $price, int $expires)
    {
        $this->expires = $expires;
        $this->price = $price;
    }

    public function isAvailable(): bool
    {
        return $this->expires <= time();
    }


    public function getPrice(): int
    {
        return $this->price;
    }

    public function getExpires(): int
    {
        return $this->expires;
    }

    /**
     * Returns the state representation of the object.
     *
     * @param int $format Constant from the class CartItem
     * @return string|object State representation of the class.
     */
    public function getState(): string
    {
        return json_encode($this);
    }

    public function jsonSerialize(): object
    {
        $state = new StdClass();
        $state->price = $this->price;
        $state->expires = $this->expires;

        return $state;
    }
}
