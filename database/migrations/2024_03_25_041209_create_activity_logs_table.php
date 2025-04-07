<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->longText('note');
            $table->morphs('loggable');
            $table->morphs('source');
            $table->json('new_data')->nullable();
            $table->json('old_data')->nullable();
            $table->string('module')->comment('HRM,Accounts,CRM etc');
            $table->timestamps();
        });

        // For loggable_type column
        DB::statement('ALTER TABLE activity_logs MODIFY COLUMN loggable_type VARCHAR(255)');

        // For loggable_id column
        DB::statement('ALTER TABLE activity_logs MODIFY COLUMN loggable_id VARCHAR(255)');

        // For source_type column
        DB::statement('ALTER TABLE activity_logs MODIFY COLUMN source_type VARCHAR(255)');

        // For source_id column
        DB::statement('ALTER TABLE activity_logs MODIFY COLUMN source_id VARCHAR(255)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
