<?php
// SPDX-License-Identifier: NCSA

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 191);
            $table->text('description')->nullable();
            $table->string('image_url', 2048)->nullable();
            $table->enum('status', ['planned', 'ongoing', 'completed'])->default('planned');
            $table->unsignedSmallInteger('year')->nullable();
            $table->string('related_url', 2048)->nullable();
            $table->timestamps();
            $table->index(['status', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
