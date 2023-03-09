<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->longText('description');
            $table->integer('status');
            $table->boolean('submitter_notify')->default(false);
            $table->string('submitter_email')->nullable();
            $table->foreignUuid('monitored_by')->constrained('users');
            $table->foreignUuid('emergency_type_id')->constrained();
            $table->timestamps();

            $table->index(['monitored_by', 'emergency_type_id', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
