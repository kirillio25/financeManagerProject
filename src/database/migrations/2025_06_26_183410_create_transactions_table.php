<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id(); // SERIAL PRIMARY KEY
            $table->unsignedBigInteger('user_id'); // INTEGER NOT NULL
            $table->decimal('amount', 12, 2); // NUMERIC(12,2) NOT NULL
            $table->unsignedTinyInteger('type_id'); // CHECK (type_id IN(0,1)) NOT NULL
            $table->unsignedBigInteger('category_id')->default(0); // DEFAULT 0
            $table->unsignedBigInteger('account_id'); // INTEGER NOT NULL
            $table->timestamp('created_at')->useCurrent(); // DEFAULT CURRENT_TIMESTAMP
            $table->timestamp('updated_at')->useCurrent(); // DEFAULT CURRENT_TIMESTAMP

        });

        DB::statement("ALTER TABLE transactions ADD CONSTRAINT check_type_id CHECK (type_id IN (0, 1))");
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
