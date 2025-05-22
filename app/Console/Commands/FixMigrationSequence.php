<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class FixMigrationSequence extends Command
{
    protected $signature = 'migrations:fix-sequence';
    protected $description = 'Fix migration sequence for organization and users tables';

    public function handle()
    {
        $this->info('Fixing migration sequence...');

        // Check if tables exist
        if (!Schema::hasTable('organizations')) {
            $this->createOrganizationsTable();
        }

        // Add foreign keys if they're missing
        if (Schema::hasTable('users') && !$this->hasForeignKey('users', 'users_organization_id_foreign')) {
            $this->addForeignKeyToUsers();
        }

        $this->info('Migration sequence fixed successfully!');
        return Command::SUCCESS;
    }

    private function createOrganizationsTable()
    {
        $this->info('Creating organizations table...');

        Schema::create('organizations', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->uuid('uuid')->unique();
            $table->timestamps();
        });
    }

    private function addForeignKeyToUsers()
    {
        $this->info('Adding foreign key to users table...');

        Schema::table('users', function ($table) {
            $table->foreign('organization_id', 'users_organization_id_foreign')
                ->references('id')
                ->on('organizations')
                ->onDelete('cascade');
        });
    }

    private function hasForeignKey($table, $keyName)
    {
        $foreignKeys = DB::select(
            "SELECT * FROM information_schema.TABLE_CONSTRAINTS
             WHERE CONSTRAINT_TYPE = 'FOREIGN KEY'
             AND TABLE_SCHEMA = DATABASE()
             AND TABLE_NAME = '{$table}'
             AND CONSTRAINT_NAME = '{$keyName}'"
        );

        return count($foreignKeys) > 0;
    }
}
