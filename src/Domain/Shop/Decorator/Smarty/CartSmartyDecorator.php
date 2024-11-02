<?php

declare(strict_types=1);

namespace Planet\InterviewChallenge\Domain\Shop\Decorator\Smarty;

use Planet\InterviewChallenge\Domain\Shop\Cart;
use Smarty\Smarty;

class CartSmartyDecorator
{
    private Smarty $smarty;
    private Cart $cart;

    public function __construct(Smarty $smarty, Cart $cart){
        $this->smarty = $smarty;
        $this->cart = $cart;
    }

    public function display(): string
    {
        $decoratedItems = array_map(
            fn ($cartItem) => new CartItemSmartyDecorator($this->smarty, $cartItem),
            $this->cart->getItems()
        );

        $this->smarty->assign('items', $decoratedItems);
        return $this->smarty->fetch('Shop/Cart.tpl');
    }
}
