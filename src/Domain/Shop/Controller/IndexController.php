<?php

declare(strict_types=1);

namespace Planet\InterviewChallenge\Domain\Shop\Controller;

use League\Route\Http\Exception\BadRequestException;
use Smarty\Smarty;
use Planet\InterviewChallenge\Domain\Shop\Cart;
use Planet\InterviewChallenge\Domain\Shop\CartItem;
use Planet\InterviewChallenge\Domain\Shop\Decorator\Smarty\CartSmartyDecorator;
use Planet\InterviewChallenge\Infrastructure\SmartyRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response;

class IndexController
{
    private Smarty $smarty;

    public function __construct(Smarty $smarty)
    {
        $this->smarty = $smarty;
    }

    public function showCart (ServerRequestInterface $request): ResponseInterface
    {
        $params = $request->getQueryParams();


        $items = json_decode($params['items'] ?? '[]');

        if ($items === null) {
            throw new BadRequestException('Invalid items');
        }

        $cart = new Cart();

        foreach ($items as $item) {
            $cart->addItem(new CartItem((int)$item->price, self::valueToMode($item->expires, $modifier), $modifier));
        }

        $renderer = new SmartyRenderer($this->smarty);

        $content = $renderer->render(
            'App.tpl',
            [
                'ShopCart' => new CartSmartyDecorator($this->smarty, $cart),
            ]
        );

        $response = new Response();
        $response->getBody()->write($content);

        return $response;
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
