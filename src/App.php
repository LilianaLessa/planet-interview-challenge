<?php

declare(strict_types=1);

namespace Planet\InterviewChallenge;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use League\Route\Http\Exception\BadRequestException;
use League\Route\Http\Exception\NotFoundException;
use League\Route\Router;
use Planet\InterviewChallenge\Domain\Shop\Controller\IndexController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Smarty\Smarty;

class App
{
    private static ?Smarty $smarty = null;
    private static ?Router $router = null;

    public static function run(): void
    {
        self::initSmarty();

        self::processRequest();
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

    private static function processRequest(): void
    {
        $request = ServerRequestFactory::fromGlobals(
            $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
        );

        try {
            $response = self::router()->dispatch($request);

        } catch (NotFoundException $e) {
            $response = self::handleNotFoundException();
        } catch (BadRequestException $e) {
            $response = self::handleBadRequestException($e->getMessage());
        }

        (new SapiEmitter)->emit($response);
    }

    private static function smarty(): Smarty
    {
        if(self::$smarty === null) {
            self::initSmarty();
        }

        return self::$smarty;
    }

    private static function router(): Router
    {
        if(self::$router === null) {
            self::initRouter();
        }

        return self::$router;
    }

    private static function initRouter(): void
    {
        self::$router = new Router();

        self::$router->map(
            'GET',
            '/index.php',
            fn (ServerRequestInterface $request): ResponseInterface => (new IndexController(self::smarty()))->showCart($request)
        );
    }

    private static function handleNotFoundException(): Response
    {
        ob_start();
        self::smarty()->display('404.tpl');
        $content = ob_get_contents();
        ob_end_clean();

        $response = new Response();
        $response->getBody()->write($content);
        return $response;
    }

    private static function handleBadRequestException(string $message): Response
    {
        ob_start();
        self::smarty()->assign('message', $message);
        self::smarty()->display('400.tpl');
        $content = ob_get_contents();
        ob_end_clean();

        $response = new Response();
        $response->getBody()->write($content);
        return $response;
    }
}
