<?php

declare(strict_types=1);

namespace Planet\InterviewChallenge\Infrastructure;

use Google\Cloud\Logging\LoggingClient;
use Laminas\Diactoros\Response;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Planet\InterviewChallenge\Service\TemplateService;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Throwable;

class ApplicationLogger
{
    private LoggerInterface $logger;

    private TemplateService $templateService;

    public function __construct(TemplateService $templateService)
    {
        $this->templateService = $templateService;

        $this->initLogger();
    }

    public function handleGenericException(Throwable $exception): Response
    {
        $locator = Uuid::uuid4()->toString();

        self::logException($exception, $locator);

        ob_start();
        try {
            $this->templateService->assign('locator', $locator);
            $this->templateService->display('ErrorPages/500.tpl');
            $content = ob_get_contents();
        } catch (Throwable $e) {
            self::logException($e, $locator);
            $content = sprintf('Something went wrong. Error locator: %s', $locator);
        }
        ob_end_clean();

        if ($content === false) {
            throw new \RuntimeException('Output buffering is not active');
        }

        $response = new Response();
        $response->getBody()->write($content);
        return $response;
    }

    private function logException(Throwable $exception, string $locator): void
    {
        $this->logger->error(
            $exception->getMessage(),
            [
                'locator' => $locator,
                'message' => $exception->getMessage(),
                'exception' => get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTrace()
            ]
        );
    }

    private function initLogger(): void
    {
        if (getenv('ENVIRONMENT') === 'gcp') {
            $this->initGCPLogger();
            return;
        }

        $this->logger = new Logger('application');
        $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../../log/error.log'));
    }

    private function initGCPLogger(): void
    {
        $logging = new LoggingClient([
            'projectId' => getenv('GOOGLE_CLOUD_PROJECT')
        ]);
        $this->logger = $logging->psrLogger('app');
    }
}
