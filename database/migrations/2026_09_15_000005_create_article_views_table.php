<?php
// SPDX-License-Identifier: NCSA

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('article_views', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('article_id');
            $table->string('viewer_hash', 64);
            $table->date('viewed_on');
            $table->timestamps();
            $table->unique(['article_id', 'viewer_hash', 'viewed_on'], 'article_views_session_day_unique');
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_views');
    }
};
