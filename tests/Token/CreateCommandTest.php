<?php

namespace Junction\Cli\Test\Token;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Junction\Cli\Token\CreateCommand;
use Meritum\Cli\ExitCode;
use Meritum\Cli\Output\SageStyleInterface;
use PHPUnit\Framework\TestCase;

final class CreateCommandTest extends TestCase
{
    private string $secret = 'test-secret-key-for-unit-tests-min32';

    private function makeIo(string $type = 'relay', string $id = 'my-token'): SageStyleInterface
    {
        $io = $this->createMock(SageStyleInterface::class);
        $io->method('option')->willReturnMap([['type', '', $type]]);
        $io->method('argument')->willReturn($id);

        return $io;
    }

    public function test_returns_error_when_secret_is_empty(): void
    {
        $io = $this->createMock(SageStyleInterface::class);
        $io->expects($this->once())->method('error');

        $result = (new CreateCommand(''))->__invoke($io);

        $this->assertSame(ExitCode::Error, $result);
    }

    public function test_returns_error_when_type_is_not_provided(): void
    {
        $io = $this->createMock(SageStyleInterface::class);
        $io->method('option')->willReturnMap([['type', '', '']]);
        $io->expects($this->once())->method('error');

        $result = (new CreateCommand($this->secret))->__invoke($io);

        $this->assertSame(ExitCode::Error, $result);
    }

    public function test_returns_error_when_type_is_invalid(): void
    {
        $io = $this->createMock(SageStyleInterface::class);
        $io->method('option')->willReturnMap([['type', '', 'admin']]);
        $io->expects($this->once())->method('error');

        $result = (new CreateCommand($this->secret))->__invoke($io);

        $this->assertSame(ExitCode::Error, $result);
    }

    public function test_returns_success_for_relay_type(): void
    {
        $result = (new CreateCommand($this->secret))->__invoke($this->makeIo('relay'));

        $this->assertSame(ExitCode::Success, $result);
    }

    public function test_returns_success_for_management_type(): void
    {
        $result = (new CreateCommand($this->secret))->__invoke($this->makeIo('management'));

        $this->assertSame(ExitCode::Success, $result);
    }

    public function test_returns_success_for_system_type(): void
    {
        $result = (new CreateCommand($this->secret))->__invoke($this->makeIo('system'));

        $this->assertSame(ExitCode::Success, $result);
    }

    public function test_accepts_uppercase_type(): void
    {
        $result = (new CreateCommand($this->secret))->__invoke($this->makeIo('RELAY'));

        $this->assertSame(ExitCode::Success, $result);
    }

    public function test_token_payload_contains_correct_type(): void
    {
        $decoded = $this->decodeTokenFromCommand('relay', 'my-token');

        $this->assertSame('relay', $decoded->type);
    }

    public function test_token_payload_contains_id(): void
    {
        $decoded = $this->decodeTokenFromCommand('relay', 'prod-webhook');

        $this->assertSame('prod-webhook', $decoded->id);
    }

    public function test_token_payload_contains_iat(): void
    {
        $before  = time();
        $decoded = $this->decodeTokenFromCommand('relay', 'my-token');

        $this->assertGreaterThanOrEqual($before, $decoded->iat);
        $this->assertLessThanOrEqual(time(), $decoded->iat);
    }

    public function test_token_payload_does_not_contain_exp(): void
    {
        $decoded = $this->decodeTokenFromCommand('relay', 'my-token');

        $this->assertFalse(isset($decoded->exp));
    }

    public function test_type_is_lowercased_in_payload(): void
    {
        $decoded = $this->decodeTokenFromCommand('MANAGEMENT', 'my-token');

        $this->assertSame('management', $decoded->type);
    }

    public function test_outputs_token_string(): void
    {
        $token = $this->captureToken('relay', 'my-token');

        $this->assertNotEmpty($token);
    }

    private function captureToken(string $type, string $id): string
    {
        $captured = '';

        $io = $this->createMock(SageStyleInterface::class);
        $io->method('option')->willReturnMap([['type', '', $type]]);
        $io->method('argument')->willReturn($id);
        $io->method('writeln')->willReturnCallback(function (mixed $msg) use (&$captured) {
            if (is_string($msg)) {
                preg_match('/<comment>(.*?)<\/comment>/', $msg, $matches);
                $captured = $matches[1] ?? '';
            }
        });

        (new CreateCommand($this->secret))->__invoke($io);

        return $captured;
    }

    private function decodeTokenFromCommand(string $type, string $id): object
    {
        $token = $this->captureToken($type, $id);

        return JWT::decode($token, new Key($this->secret, 'HS256'));
    }
}
