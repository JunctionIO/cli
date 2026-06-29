<?php

namespace Junction\Cli;

use Georgeff\Kernel\Environment;
use Georgeff\Kernel\Support\Env;
use Georgeff\Kernel\KernelInterface;
use Meritum\Cli\Exception\ExceptionHandlerInterface;
use Georgeff\Kernel\Module\ConfigurableModuleInterface;

final class AppModule implements ConfigurableModuleInterface
{
    public function register(KernelInterface $kernel): void
    {
        $kernel->define(ExceptionHandlerInterface::class, fn() => new Exception\ExceptionHandler());
    }

    public function config(Environment $env): array
    {
        return [
            'jwt.secret' => Env::get('JWT_SECRET', ''),
        ];
    }
}
