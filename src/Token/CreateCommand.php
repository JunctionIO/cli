<?php

namespace Junction\Cli\Token;

use Firebase\JWT\JWT;
use Meritum\Cli\ExitCode;
use Meritum\Cli\Command\Command;
use Meritum\Cli\Output\SageStyleInterface;

final class CreateCommand extends Command
{
    public function __construct(private readonly string $secret) {}

    public function __invoke(SageStyleInterface $io): ExitCode
    {
        if ('' === $this->secret) {
            $io->error('Cannot create token, JWT_SECRET env variable is not set');

            return ExitCode::Error;
        }

        $type = $io->option('type', '');

        assert(is_string($type));

        $type = strtolower($type);

        if ('' === $type) {
            $io->error('Token type not provided. Set --type=relay|system|management');

            return ExitCode::Error;
        }

        if (!in_array($type, ['relay', 'system', 'management'], true)) {
            $io->error("Invalid token type [{$type}]. Valid types: relay, system, management");

            return ExitCode::Error;
        }

        $payload = [
            'type' => $type,
            'id'   => $io->argument('id'),
            'iat'  => new \DateTimeImmutable()->getTimestamp(),
        ];

        $token = JWT::encode($payload, $this->secret, 'HS256');

        $io->success('API Token Created');

        $io->writeln("<comment>{$token}</comment>");

        return ExitCode::Success;
    }
}
