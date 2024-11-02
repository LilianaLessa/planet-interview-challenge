<?php

namespace Planet\InterviewChallenge\Domain\Shop;

use Planet\InterviewChallenge\App;

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

    public function display(): string
    {
        App::smarty()->assign('items', $this->items);
        return App::smarty()->fetch('shop/Cart.tpl');
    }

    public function getState(): string
    {
        $objectStates = '[';

        while ($item = each($this->items)) {
            $objectStates .= $item['value']->getState() . ',';
        }
        $objectStates = substr($objectStates, 0, -1);
        return $objectStates . ']';
    }
}
