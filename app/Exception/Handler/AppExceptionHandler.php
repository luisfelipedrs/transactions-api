<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Exception\Handler;

use App\Constants\HttpStatus;
use App\Exception\ResourceNotFoundException;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class AppExceptionHandler extends ExceptionHandler
{
    public function __construct(protected StdoutLoggerInterface $logger)
    {
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $data = [
            'error' => $throwable->getMessage(),
        ];

        if ($throwable instanceof InvalidArgumentException) {
            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus(HttpStatus::UNPROCESSABLE_ENTITY)
                ->withBody(new SwooleStream(json_encode($data)));
        }

        if ($throwable instanceof ResourceNotFoundException) {
            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus(HttpStatus::NOT_FOUND)
                ->withBody(new SwooleStream(json_encode($data)));
        }

        $this->logger->error(sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile()));
        $this->logger->error($throwable->getTraceAsString());

        return $response->withHeader('Server', 'Hyperf')
            ->withStatus(HttpStatus::INTERNAL_SERVER_ERROR)
            ->withBody(new SwooleStream(json_encode(['error' => 'Ocorreu um problema inesperado.'])));
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}