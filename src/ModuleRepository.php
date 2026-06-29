<?php

namespace Junction\Cli;

use Georgeff\Kernel\Environment;
use Georgeff\Kernel\Module\ModuleInterface;
use Georgeff\Kernel\Module\ModuleRepositoryInterface;

final class ModuleRepository implements ModuleRepositoryInterface
{
    /**
     * Register application modules
     *
     * @return ModuleInterface[]
     */
    public function modules(Environment $env): array
    {
        return [
            new AppModule(),
            new Token\TokenModule(),
            new \Meritum\Migrations\Module\DatabaseModule(),
            new \Meritum\Migrations\Module\MigrationsModule(),
        ];
    }
}
