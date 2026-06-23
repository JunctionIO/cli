<?php

use Georgeff\Schema\Blueprint;
use Meritum\Migrations\SchemaInterface;
use Meritum\Migrations\MigrationInterface;

return new class implements MigrationInterface {
    public function up(SchemaInterface $schema): void
    {
        $schema->create('destination_types', function (Blueprint $blueprint) {
            $blueprint->string('id')->primary();
            $blueprint->string('name')->unique();
            $blueprint->string('queue')->unique();
            $blueprint->text('description')->nullable();
            $blueprint->json('config_schema');
            $blueprint->timestamps();
        });
    }

    public function down(SchemaInterface $schema): void
    {
        $schema->dropIfExists('destination_types');
    }
};
