<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('categories_expense', function (Blueprint $table) {
            $table->id(); // SERIAL PRIMARY KEY
            $table->unsignedBigInteger('user_id'); // INTEGER NOT NULL
            $table->string('name', 100); // VARCHAR(100) NOT NULL
            $table->timestamp('created_at')->useCurrent(); // DEFAULT CURRENT_TIMESTAMP
            $table->timestamp('updated_at')->useCurrent(); // DEFAULT CURRENT_TIMESTAMP
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories_expense');
    }
};

