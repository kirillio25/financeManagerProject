<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedTinyInteger('debt_direction')->comment('0 — Я должен, 1 — Мне должны');
            $table->string('name', 100);
            $table->decimal('amount', 12, 2);
            $table->string('contact_method', 255)->nullable();
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('status');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};
