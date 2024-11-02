<?php

namespace Planet\InterviewChallenge\Shop;

use Planet\InterviewChallenge\App;

class Cart
{
    private array $items;

    public function __construct()
    {
        $this->items = [];

        $params = json_decode($_GET['items'] ?? '[]');

        foreach ($params as $item) {
            $this->addItem(new CartItem((int)$item->price, $this->valueToMode($item->expires, $modifier), $modifier));
        }
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

    private function valueToMode($value, &$modifier): int {

        if ($value) {
            if ($value === 'never') {
                return CartItem::MODE_NO_LIMIT;
            }

            if ($value === '60min') {
                $modifier = 60;
                return CartItem::MODE_SECONDS;
            }
        }
    }
}
