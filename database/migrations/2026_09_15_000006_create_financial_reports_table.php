<?php
// SPDX-License-Identifier: NCSA

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('financial_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 191);
            $table->unsignedTinyInteger('month');
            $table->unsignedSmallInteger('year');
            $table->string('stored_path', 255);
            $table->string('display_name', 191);
            $table->string('mime', 100)->default('application/pdf');
            $table->unsignedBigInteger('size')->default(0);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamps();
            $table->index(['year', 'month']);
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_reports');
    }
};
