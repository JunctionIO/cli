<?php

use Georgeff\Schema\Blueprint;
use Meritum\Migrations\SchemaInterface;
use Meritum\Migrations\MigrationInterface;

return new class implements MigrationInterface {
    public function up(SchemaInterface $schema): void
    {
        $schema->create('event_log_destinations', function (Blueprint $blueprint) {
            $blueprint->uuid('id')->unique()->primary();
            $blueprint->uuid('trace_id')->index();
            $blueprint->uuid('event_log_id')->index();
            $blueprint->uuid('destination_id')->index();
            $blueprint->string('status')->index();
            $blueprint->timestamp('attempted_at')->nullable();
            $blueprint->text('error')->nullable();
            $blueprint->timestamps();
        });
    }

    public function down(SchemaInterface $schema): void
    {
        $schema->dropIfExists('event_log_destinations');
    }
};

