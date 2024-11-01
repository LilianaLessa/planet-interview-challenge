<?php

namespace Planet\InterviewChallenge\Shop;

use Exception;
use Throwable;
use Planet\InterviewChallenge\App;

require_once(__DIR__.'/CartItem.php');

class Cart
{
    private array $items;

    public function __construct()
    {
        $this->items = [];

        $params = json_decode($_GET['items'] ?? '[]');

        while ($item = each($params)) {
            $this->addItem(new CartItem((int)$item['value']->price, $this->valueToMode($item['value']->expires, $modifier), $modifier));
        }
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

    public function addItem(CartItem $cartItem): bool
    {
        try {
            $cartItem->isAvailable();
            $this->items[] = $cartItem;
            return true;
        } catch (Throwable|Exception $e) {
            throw $e;
        }
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function clear(): void {
        unset($this->items);
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
