<?php

declare(strict_types=1);

namespace Planet\InterviewChallenge\Domain\Shop\Decorator\Smarty;

use Planet\InterviewChallenge\Domain\Shop\CartItem;
use Smarty\Smarty;

class CartItemSmartyDecorator
{
    private Smarty $smarty;
    private CartItem $cartItem;

    public function __construct(Smarty $smarty, CartItem $cartItem){
        $this->smarty = $smarty;
        $this->cartItem = $cartItem;
    }

    public function display(): string
    {
        $this->smarty->assign('price', $this->cartItem->getPrice());
        $this->smarty->assign('expires', $this->cartItem->getExpires());

        return $this->smarty->fetch('Domain/Shop/CartItem.tpl');
    }
}
