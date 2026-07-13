<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'first_name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('first_name')->nullable()->after('id');
            });
        }

        if (! Schema::hasColumn('users', 'last_name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('last_name')->nullable()->after('first_name');
            });
        }

        if (! Schema::hasColumn('users', 'middle_name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('middle_name')->nullable()->after('last_name');
            });
        }

        if (! Schema::hasColumn('users', 'phone')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('phone', 20)->nullable()->unique()->after('email');
            });
        }

        if (! Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('user')->after('password');
            });
        }

        if (! Schema::hasColumn('users', 'facility_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('facility_id')->nullable()->after('role');
            });
        }

        if (! Schema::hasColumn('users', 'is_registered')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_registered')->default(false)->after('facility_id');
            });
        }

        if (! Schema::hasColumn('users', 'phone_verified')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('phone_verified')->default(false)->after('is_registered');
            });
        }

        if (! Schema::hasColumn('users', 'last_login_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('last_login_at')->nullable()->after('phone_verified');
            });
        }

        if (! Schema::hasTable('facilities')) {
            Schema::create('facilities', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('type')->default('hospital');
                $table->string('address')->nullable();
                $table->string('city')->nullable();
                $table->string('province')->nullable();
                $table->decimal('latitude', 10, 7)->nullable();
                $table->decimal('longitude', 10, 7)->nullable();
                $table->string('contact_phone')->nullable();
                $table->string('contact_email')->nullable();
                $table->string('stock_status')->default('normal');
                $table->boolean('accepts_donations')->default(true);
                $table->boolean('is_locked')->default(false);
                $table->unsignedBigInteger('head_user_id')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('donors')) {
            Schema::create('donors', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('blood_type')->nullable();
                $table->string('verification_status')->default('unverified');
                $table->boolean('document_verified')->default(false);
                $table->string('document_path')->nullable();
                $table->string('donor_status')->default('available');
                $table->decimal('latitude', 10, 7)->nullable();
                $table->decimal('longitude', 10, 7)->nullable();
                $table->timestamp('last_donation_at')->nullable();
                $table->timestamp('cooldown_until')->nullable();
                $table->unsignedBigInteger('verified_by_facility_id')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('donor_deferrals')) {
            Schema::create('donor_deferrals', function (Blueprint $table) {
                $table->id();
                $table->foreignId('donor_id')->constrained()->cascadeOnDelete();
                $table->foreignId('facility_id')->nullable()->constrained()->nullOnDelete();
                $table->string('type')->default('temporary');
                $table->text('reason')->nullable();
                $table->date('eligible_again_at')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('blood_inventory')) {
            Schema::create('blood_inventory', function (Blueprint $table) {
                $table->id();
                $table->foreignId('facility_id')->constrained()->cascadeOnDelete();
                $table->string('blood_type');
                $table->string('component_type')->default('whole_blood');
                $table->unsignedInteger('quantity')->default(1);
                $table->date('collection_date');
                $table->date('expiry_date');
                $table->string('freshness_flag')->default('standard');
                $table->string('status')->default('available');
                $table->foreignId('donor_id')->nullable()->constrained()->nullOnDelete();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('emergency_requests')) {
            Schema::create('emergency_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('facility_id')->constrained()->cascadeOnDelete();
                $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
                $table->string('blood_type');
                $table->string('component_type')->default('whole_blood');
                $table->unsignedInteger('quantity')->default(1);
                $table->string('urgency')->default('normal');
                $table->string('status')->default('pending');
                $table->string('escalation_level')->default('local');
                $table->text('notes')->nullable();
                $table->timestamp('resolved_at')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('otp_codes')) {
            Schema::create('otp_codes', function (Blueprint $table) {
                $table->id();
                $table->string('identifier');
                $table->string('code', 6);
                $table->string('channel')->default('phone');
                $table->timestamp('expires_at');
                $table->boolean('used')->default(false);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('app_notifications')) {
            Schema::create('app_notifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('facility_id')->nullable()->constrained()->nullOnDelete();
                $table->string('type');
                $table->string('title');
                $table->text('message');
                $table->json('data')->nullable();
                $table->boolean('is_read')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('app_notifications');
        Schema::dropIfExists('otp_codes');
        Schema::dropIfExists('emergency_requests');
        Schema::dropIfExists('blood_inventory');
        Schema::dropIfExists('donor_deferrals');
        Schema::dropIfExists('donors');
        Schema::dropIfExists('facilities');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name', 'last_name', 'middle_name', 'phone', 'role',
                'facility_id', 'is_registered', 'phone_verified', 'last_login_at',
            ]);
        });
    }
};
