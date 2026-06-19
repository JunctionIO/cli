<?php

use Georgeff\Schema\Blueprint;
use Meritum\Migrations\SchemaInterface;
use Meritum\Migrations\MigrationInterface;

return new class implements MigrationInterface {
    public function up(SchemaInterface $schema): void
    {
        $schema->create('destinations', function (Blueprint $blueprint) {
            $blueprint->uuid('id')->unique()->primary();
            $blueprint->string('name');
            $blueprint->text('description')->nullable();
            $blueprint->uuid('destination_type_id')->index();
            $blueprint->json('config');
            $blueprint->string('status')->index();
            $blueprint->timestamps();
        });
    }

    public function down(SchemaInterface $schema): void
    {
        $schema->dropIfExists('destinations');
    }
};
