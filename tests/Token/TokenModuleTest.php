<?php

namespace Junction\Cli\Test\Token;

use Georgeff\Kernel\DI\DefinitionInterface;
use Georgeff\Kernel\KernelInterface;
use Junction\Cli\Token\CreateCommand;
use Junction\Cli\Token\TokenModule;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

final class TokenModuleTest extends TestCase
{
    public function test_registers_one_definition(): void
    {
        $definition = $this->createMock(DefinitionInterface::class);
        $definition->method('tag')->willReturn($definition);

        $kernel = $this->createMock(KernelInterface::class);
        $kernel->expects($this->exactly(1))
            ->method('define')
            ->willReturn($definition);

        (new TokenModule())->register($kernel);
    }

    public function test_definition_is_tagged_as_command(): void
    {
        $definition = $this->createMock(DefinitionInterface::class);
        $definition->expects($this->once())
            ->method('tag')
            ->with('cli.commands')
            ->willReturn($definition);

        $kernel = $this->createMock(KernelInterface::class);
        $kernel->method('define')->willReturn($definition);

        (new TokenModule())->register($kernel);
    }

    public function test_factory_produces_create_command(): void
    {
        [$factories] = $this->captureFactories();

        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')->with('kernel.config')->willReturn(['jwt.secret' => 'test-secret']);

        $this->assertInstanceOf(CreateCommand::class, $factories[CreateCommand::class]($container));
    }

    public function test_command_is_named_token_create(): void
    {
        [$factories] = $this->captureFactories();

        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')->with('kernel.config')->willReturn(['jwt.secret' => 'test-secret']);

        $command = $factories[CreateCommand::class]($container);

        $this->assertSame('token:create', $command->getName());
    }

    /**
     * @return array{array<string, callable>}
     */
    private function captureFactories(): array
    {
        $factories  = [];
        $definition = $this->createMock(DefinitionInterface::class);
        $definition->method('tag')->willReturn($definition);

        $kernel = $this->createMock(KernelInterface::class);
        $kernel->method('define')
            ->willReturnCallback(function (string $id, callable $f) use ($definition, &$factories) {
                $factories[$id] = $f;
                return $definition;
            });

        (new TokenModule())->register($kernel);

        return [$factories];
    }
}
