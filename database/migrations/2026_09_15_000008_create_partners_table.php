<?php
// SPDX-License-Identifier: NCSA

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 191);
            $table->string('logo_url', 2048)->nullable();
            $table->text('description')->nullable();
            $table->string('website_url', 2048)->nullable();
            $table->unsignedSmallInteger('cooperation_year')->nullable();
            $table->timestamps();
            $table->index('cooperation_year');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
