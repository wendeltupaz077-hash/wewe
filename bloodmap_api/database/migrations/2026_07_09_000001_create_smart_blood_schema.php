<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('id');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('middle_name')->nullable()->after('last_name');
            $table->string('phone', 20)->nullable()->unique()->after('email');
            $table->string('role')->default('user')->after('password');
            $table->unsignedBigInteger('facility_id')->nullable()->after('role');
            $table->boolean('is_registered')->default(false)->after('facility_id');
            $table->boolean('phone_verified')->default(false)->after('is_registered');
            $table->timestamp('last_login_at')->nullable()->after('phone_verified');
        });

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

        Schema::create('otp_codes', function (Blueprint $table) {
            $table->id();
            $table->string('identifier');
            $table->string('code', 6);
            $table->string('channel')->default('phone');
            $table->timestamp('expires_at');
            $table->boolean('used')->default(false);
            $table->timestamps();
        });

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
