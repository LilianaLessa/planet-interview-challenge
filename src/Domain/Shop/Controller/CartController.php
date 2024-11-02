<?php

declare(strict_types=1);

namespace Planet\InterviewChallenge\Domain\Shop\Controller;

use League\Route\Http\Exception\BadRequestException;
use Planet\InterviewChallenge\Domain\Shop\Service\CartItemExpirationService;
use Planet\InterviewChallenge\Service\SmartyTemplateService;
use Smarty\Smarty;
use Planet\InterviewChallenge\Domain\Shop\Cart;
use Planet\InterviewChallenge\Domain\Shop\CartItem;
use Planet\InterviewChallenge\Domain\Shop\Decorator\Smarty\CartSmartyDecorator;
use Planet\InterviewChallenge\Infrastructure\SmartyRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response;

class CartController
{
    private Smarty $smarty;
    private CartItemExpirationService $cartItemExpirationService;

    public function __construct(
        SmartyTemplateService $smartyTemplateService,
        CartItemExpirationService $cartItemExpirationService
    ) {
        $this->smarty = $smartyTemplateService->getSmarty();
        $this->cartItemExpirationService = $cartItemExpirationService;
    }

    /**
     * @throws BadRequestException
     */
    public function showCart(ServerRequestInterface $request): ResponseInterface
    {
        $params = $request->getQueryParams();

        $items = json_decode($params['items'] ?? '[]');

        if ($items === null) {
            throw new BadRequestException('Invalid items');
        }

        $cart = new Cart();

        foreach ($items as $item) {
            $expiration = $this->cartItemExpirationService->generateExpiration(
                self::valueToMode($item->expires, $modifier),
                $modifier
            );
            $cart->addItem(new CartItem((int)$item->price, $expiration));
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

    /**
     * @throws BadRequestException
     */
    private static function valueToMode($value, &$modifier): int {

        switch ($value) {
            case 'never':
                return CartItemExpirationService::MODE_NO_LIMIT;
            case '60min':
                $modifier = 60;
                return CartItemExpirationService::MODE_SECONDS;
            default:
                throw new BadRequestException('Invalid expiration');
        }
    }
}
