<?php

use Georgeff\Schema\Blueprint;
use Meritum\Migrations\SchemaInterface;
use Meritum\Migrations\MigrationInterface;

return new class implements MigrationInterface {
    public function up(SchemaInterface $schema): void
    {
        $schema->create('destination_events', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('destination_id')->index();
            $blueprint->string('event_id')->index();
            $blueprint->timestamp('created_at')->defaultRaw('CURRENT_TIMESTAMP');
        });
    }

    public function down(SchemaInterface $schema): void
    {
        $schema->dropIfExists('destination_events');
    }
};
