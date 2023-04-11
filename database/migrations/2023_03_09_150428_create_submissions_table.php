<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
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
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->foreignUuid('submitted_by')->constrained('users');
            $table->foreignUuid('monitored_by')->nullable()->constrained('users');
            $table->foreignUuid('emergency_type_id')->constrained();
            $table->timestamps();

            $table->index(['monitored_by', 'emergency_type_id', 'id', 'submitted_by']);
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
