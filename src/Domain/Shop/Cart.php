<?php

namespace Planet\InterviewChallenge\Domain\Shop;

class Cart
{
    private array $items;

    public function __construct()
    {
        $this->items = [];
    }

    public function addItem(CartItem $cartItem): void
    {
        $this->items[] = $cartItem;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getState(): string
    {
        $objectStates = '[';

        foreach ($this->items as $item) {
            $objectStates .= $item->getState() . ',';
        }
        $objectStates = substr($objectStates, 0, -1);

        return $objectStates . ']';
    }
}
