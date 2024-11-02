<?php

namespace Planet\InterviewChallenge;

use Planet\InterviewChallenge\Domain\Shop\Cart;
use Planet\InterviewChallenge\Domain\Shop\CartItem;
use Planet\InterviewChallenge\Domain\Shop\Decorator\Smarty\CartSmartyDecorator;
use Planet\InterviewChallenge\Infrastructure\SmartyRenderer;
use Smarty\Smarty;

class App
{
    private static ?Smarty $smarty = null;

    public static function smarty(): Smarty
    {
        if(self::$smarty === null) {
            self::initSmarty();
        }

        return self::$smarty;
    }

    public static function run(): void
    {
        self::initSmarty();

        self::processIndex();
    }

    private static function initSmarty(): void
    {
        self::$smarty = new Smarty();
        self::$smarty->setTemplateDir([__DIR__, __DIR__ . '/tpl']);
        self::$smarty->setConfigDir(__DIR__ . '/config');
        self::$smarty->setCompileDir(__DIR__ . '/../tmp/templates_c');
        self::$smarty->setCacheDir(__DIR__ . '/../tmp/cache');

        self::$smarty->registerPlugin('modifier', 'format_date', function ($timestamp, $format = 'Y-m-d') {
            return date($format, $timestamp);
        });
    }

    private static function processIndex(): void
    {
        $params = json_decode($_GET['items'] ?? '[]');

        $cart = new Cart();

        foreach ($params as $item) {
            $cart->addItem(new CartItem((int)$item->price, self::valueToMode($item->expires, $modifier), $modifier));
        }

        $renderer = new SmartyRenderer(self::$smarty);

        $content = $renderer->render(
            'App.tpl',
            [
                'ShopCart' => new CartSmartyDecorator(self::smarty(), $cart),
            ]
        );

        echo $content;
    }

    private static function valueToMode($value, &$modifier): int {
        if ($value) {
            if ($value === 'never') {
                return CartItem::MODE_NO_LIMIT;
            }

            if ($value === '60min') {
                $modifier = 60;
                return CartItem::MODE_SECONDS;
            }
        }

        //todo bad request exception if the value passed is invalid.
    }
}
