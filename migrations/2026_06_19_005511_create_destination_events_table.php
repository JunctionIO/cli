<?php

use Georgeff\Schema\Blueprint;
use Meritum\Migrations\SchemaInterface;
use Meritum\Migrations\MigrationInterface;

return new class implements MigrationInterface {
    public function up(SchemaInterface $schema): void
    {
        $schema->create('destination_events', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->uuid('destination_id')->index();
            $blueprint->uuid('event_id')->index();
            $blueprint->timestamp('created_at');
        });
    }

    public function down(SchemaInterface $schema): void
    {
        $schema->dropIfExists('destination_events');
    }
};

