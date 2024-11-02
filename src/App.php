<?php

declare(strict_types=1);

namespace Planet\InterviewChallenge;

use DI\Container;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Planet\InterviewChallenge\Infrastructure\ApplicationLogger;
use Planet\InterviewChallenge\Infrastructure\RouteHandler;
use Throwable;

class App
{
    private static ?Container $container = null;

    public static function run(): void
    {
        try {
            self::container()->get(RouteHandler::class)->processRequest();
        } catch (Throwable $e) {
            $response = self::container()->get(ApplicationLogger::class)->handleGenericException($e);

            (new SapiEmitter)->emit($response);
        }
    }

    public static function container(): Container
    {
        if (null === self::$container) {
            self::$container = new Container();
        }

        return self::$container;
    }
}
