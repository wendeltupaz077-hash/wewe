<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            if (Schema::hasTable('users_old')) {
                $existingUsers = DB::table('users')->count();

                if ($existingUsers === 0) {
                    DB::statement(
                        'INSERT INTO users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at, first_name, last_name, middle_name, phone, role, facility_id, is_registered, phone_verified, last_login_at, fullname, profile_picture, status, is_first_login) ' .
                        'SELECT id, name, email, email_verified_at, password, remember_token, created_at, updated_at, first_name, last_name, middle_name, phone, role, facility_id, is_registered, phone_verified, last_login_at, fullname, profile_picture, status, is_first_login FROM users_old'
                    );
                }

                Schema::dropIfExists('users_old');
                return;
            }

            DB::statement('DROP INDEX IF EXISTS users_email_unique');
            DB::statement('DROP INDEX IF EXISTS users_phone_unique');
            DB::statement('ALTER TABLE users RENAME TO users_old');

            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->nullable()->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('middle_name')->nullable();
                $table->string('phone', 20)->nullable()->unique();
                $table->string('role')->default('user');
                $table->unsignedBigInteger('facility_id')->nullable();
                $table->boolean('is_registered')->default(false);
                $table->boolean('phone_verified')->default(false);
                $table->timestamp('last_login_at')->nullable();
                $table->string('fullname')->nullable();
                $table->string('profile_picture')->nullable();
                $table->string('status')->default('active');
                $table->boolean('is_first_login')->default(true);
            });

            DB::statement(
                'INSERT INTO users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at, first_name, last_name, middle_name, phone, role, facility_id, is_registered, phone_verified, last_login_at, fullname, profile_picture, status, is_first_login) ' .
                'SELECT id, name, email, email_verified_at, password, remember_token, created_at, updated_at, first_name, last_name, middle_name, phone, role, facility_id, is_registered, phone_verified, last_login_at, fullname, profile_picture, status, is_first_login FROM users_old'
            );

            Schema::drop('users_old');

            return;
        }

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE users MODIFY email VARCHAR(255) NULL');
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE users ALTER COLUMN email DROP NOT NULL');
            return;
        }

        if ($driver === 'sqlsrv') {
            DB::statement('ALTER TABLE users ALTER COLUMN email VARCHAR(255) NULL');
            return;
        }

        throw new RuntimeException('Unsupported database driver: ' . $driver);
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            DB::statement('ALTER TABLE users RENAME TO users_old');

            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('middle_name')->nullable();
                $table->string('phone', 20)->nullable()->unique();
                $table->string('role')->default('user');
                $table->unsignedBigInteger('facility_id')->nullable();
                $table->boolean('is_registered')->default(false);
                $table->boolean('phone_verified')->default(false);
                $table->timestamp('last_login_at')->nullable();
                $table->string('fullname')->nullable();
                $table->string('profile_picture')->nullable();
                $table->string('status')->default('active');
                $table->boolean('is_first_login')->default(true);
            });

            DB::statement(
                'INSERT INTO users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at, first_name, last_name, middle_name, phone, role, facility_id, is_registered, phone_verified, last_login_at, fullname, profile_picture, status, is_first_login) ' .
                'SELECT id, name, email, email_verified_at, password, remember_token, created_at, updated_at, first_name, last_name, middle_name, phone, role, facility_id, is_registered, phone_verified, last_login_at, fullname, profile_picture, status, is_first_login FROM users_old'
            );

            Schema::drop('users_old');
            return;
        }

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE users MODIFY email VARCHAR(255) NOT NULL');
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE users ALTER COLUMN email SET NOT NULL');
            return;
        }

        if ($driver === 'sqlsrv') {
            DB::statement('ALTER TABLE users ALTER COLUMN email VARCHAR(255) NOT NULL');
            return;
        }

        throw new RuntimeException('Unsupported database driver: ' . $driver);
    }
};
