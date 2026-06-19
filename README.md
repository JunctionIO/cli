# Sage

Sage is a `composer create-project` scaffold for building CLI applications with the [Meritum](https://github.com/MeritumIO) ecosystem. It ships a pre-wired [meritum/cli](https://github.com/MeritumIO/cli) kernel with a clean structure ready to build on.

## Requirements

- PHP 8.4+
- Composer

## Getting Started

```bash
composer create-project meritum/sage my-app
cd my-app
```

Copy `.env.example` to `.env` and adjust as needed:

```
APP_NAME='My App'
APP_ENV=local
APP_DEBUG=true
```

Run the application:

```bash
php bin/sage
```

## Dev Environment

Sage ships with a `devenv.nix` for [devenv](https://devenv.sh) — a Nix-based developer environment that provides PHP and Composer without requiring a system install.

### Prerequisites

1. Install [Nix](https://nixos.org/download/) (the package manager, not the OS)
2. Install [devenv](https://devenv.sh/getting-started/)

### Usage

Enter the development shell:

```bash
devenv shell
```

This activates PHP 8.4, Composer, and `vendor/bin` on your `PATH`. Your `.env` file is loaded automatically.

From inside the shell, install dependencies and run the application:

```bash
composer install
php bin/sage
```

### Customising the environment

Open `devenv.nix` to add PHP extensions or services:

```nix
php = pkgs.php84.withExtensions ({ enabled, all }: enabled ++ [
    all.pdo_pgsql
    all.pdo_mysql
]);
```

```nix
# services.postgres = {
#   enable = true;
#   listen_addresses = "127.0.0.1";
# };
# services.redis.enable = true;
```

Uncomment the services you need and run `devenv up` to start them.

## Adding a Command

Create a command class in `src/Command/`:

```php
namespace App\Command;

use Meritum\Cli\ExitCode;
use Meritum\Cli\Command\Command;
use Meritum\Cli\Output\SageStyleInterface;

final class HelloCommand extends Command
{
    public function __invoke(SageStyleInterface $io): ExitCode
    {
        $io->success('Hello, world!');

        return ExitCode::Success;
    }
}
```

Register it in `AppModule::register()`:

```php
use Meritum\Cli\CliKernelOption;

$kernel->define(HelloCommand::class, function (): HelloCommand {
    $command = new HelloCommand();
    $command->setName('hello')->setDescription('Say hello');
    return $command;
})->tag(CliKernelOption::CommandTag->value);
```

## Structure

```
bin/sage                  Entry point
src/
  ModuleRepository.php    Register application modules
  AppModule.php           Register commands and application config
tests/
devenv.nix                Dev environment
```

## Testing

```bash
composer test
```

## Further Reading

- [meritum/cli](https://github.com/MeritumIO/cli) — CLI kernel, commands, IO
- [georgeff/kernel](https://github.com/MikeGeorgeff/kernel) — DI, modules, service tagging
