<?php

namespace Junction\Cli\Token;

use Meritum\Cli\CliKernelOption;
use Georgeff\Kernel\KernelInterface;
use Psr\Container\ContainerInterface;
use Georgeff\Kernel\Module\ModuleInterface;

final class TokenModule implements ModuleInterface
{
    public function register(KernelInterface $kernel): void
    {
        $kernel->define(CreateCommand::class, fn(ContainerInterface $c) => $this->getCreateCommand($c))
               ->tag(CliKernelOption::CommandTag->value);
    }

    private function getCreateCommand(ContainerInterface $container): CreateCommand
    {
        /** @var array{'jwt.secret': string} */
        $config = $container->get('kernel.config');

        $command = new CreateCommand($config['jwt.secret']);

        $command->setName('token:create')
                ->setDescription('Create an API token');

        $command->addArgument('id')
                ->description('The token identifier');

        $command->addOption('type')
                ->description('The token type.  One of relay|system|management')
                ->required();

        return $command;
    }
}
