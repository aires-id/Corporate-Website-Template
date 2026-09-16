<?php
// SPDX-License-Identifier: NCSA

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('author_id');
            $table->string('title', 191);
            $table->string('slug', 191)->unique();
            $table->text('excerpt')->nullable();
            $table->string('thumbnail_url', 2048)->nullable();
            $table->mediumText('body_html');
            $table->string('meta_description', 300)->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->unsignedBigInteger('views_count')->default(0);
            $table->timestamps();
            $table->index(['status', 'published_at']);
            $table->foreign('author_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
