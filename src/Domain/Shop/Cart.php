<?php

declare(strict_types=1);

namespace Planet\InterviewChallenge\Domain\Shop;

class Cart implements \JsonSerializable
{
    /** @var CartItem[] */
    private array $items;

    public function __construct()
    {
        $this->items = [];
    }

    public function addItem(CartItem $cartItem): void
    {
        $this->items[] = $cartItem;
    }

    /**
     * @return CartItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getState(): string
    {
        $state = json_encode($this);

        return $state;
    }

    /**
     * @return CartItem[]
     */
    public function jsonSerialize(): array
    {
        return $this->items;
    }
}
