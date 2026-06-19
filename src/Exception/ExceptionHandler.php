<?php

namespace Junction\Cli\Exception;

use Throwable;
use Meritum\Cli\Output\SageStyleInterface;
use Meritum\Cli\Exception\ExceptionHandlerInterface;

final class ExceptionHandler implements ExceptionHandlerInterface
{
    public function handle(Throwable $exception, SageStyleInterface $io): void
    {
        $io->error($exception->getMessage());
    }
}
