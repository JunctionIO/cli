# Junction CLI

The Junction CLI application. Handles database migrations and operator setup tasks. Built on the [Meritum](https://github.com/MeritumIO) CLI kernel.

## Dev Environment

Requires [Nix](https://nixos.org/download/) and [devenv](https://devenv.sh/getting-started/).

Copy `.env.example` to `.env`, then:

```bash
devenv shell
composer install
php bin/junction
```

## Commands

```bash
composer test     # PHPUnit
composer analyze  # PHPStan
```
