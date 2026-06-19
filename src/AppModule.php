<?php

namespace Junction\Cli;

use Georgeff\Kernel\KernelInterface;
use Georgeff\Kernel\Module\ModuleInterface;
use Meritum\Cli\Exception\ExceptionHandlerInterface;

final class AppModule implements ModuleInterface
{
    public function register(KernelInterface $kernel): void
    {
        $kernel->define(ExceptionHandlerInterface::class, fn() => new Exception\ExceptionHandler());
    }
}
