<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('actions', function(Blueprint $table) {
            $table->id();
            $table->enum('type', ['create', 'update', 'delete']);
            $table->integer('performerable_id');
            $table->index('performerable_id');
            $table->string('performerable_type');
            $table->integer('subjectable_id');
            $table->index('subjectable_id');
            $table->string('subjectable_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actions');
    }
};
