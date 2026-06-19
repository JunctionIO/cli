<?php

use Georgeff\Schema\Blueprint;
use Meritum\Migrations\SchemaInterface;
use Meritum\Migrations\MigrationInterface;

return new class implements MigrationInterface {
    public function up(SchemaInterface $schema): void
    {
        $schema->create('event_logs', function (Blueprint $blueprint) {
            $blueprint->uuid('id')->unique()->primary();
            $blueprint->uuid('trace_id')->index();
            $blueprint->uuid('event_id')->index();
            $blueprint->json('payload');
            $blueprint->string('source_ip')->nullable()->index();
            $blueprint->string('auth_id')->index();
            $blueprint->timestamp('received_at');
            $blueprint->timestamp('created_at');
        });
    }

    public function down(SchemaInterface $schema): void
    {
        $schema->dropIfExists('event_logs');
    }
};
